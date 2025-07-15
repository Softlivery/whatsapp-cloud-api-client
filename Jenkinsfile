pipeline {
    agent any

    environment {
        PHP_PATH = '/usr/bin/php'
        COMPOSER_PATH = '/usr/local/bin/composer'
    }

    options {
        skipDefaultCheckout(true)
    }

    stages {
        stage('Checkout') {
            steps {
                cleanWs()
                checkout scm
            }
        }

        stage('Install Dependencies') {
            when {
                anyOf {
                    changeRequest()
                    branch pattern: 'release/.*', comparator: 'REGEXP'
                }
            }
            steps {
                sh "${COMPOSER_PATH} install"
            }
        }

        stage('Run PHPUnit Tests') {
            when {
                anyOf {
                    changeRequest()
                    branch pattern: 'release/.*', comparator: 'REGEXP'
                }
            }
            steps {
                sh "${PHP_PATH} vendor/bin/phpunit --coverage-cobertura=coverage.xml"
            }
        }

        stage('Publish Coverage') {
            when {
                anyOf {
                    changeRequest()
                    branch pattern: 'release/.*', comparator: 'REGEXP'
                }
            }
            steps {
                discoverReferenceBuild()
                recordCoverage(
                    tools: [[parser: 'COBERTURA', pattern: 'coverage.xml']],
                    sourceCodeRetention: 'MODIFIED',
                    qualityGates: [
                        [threshold: 60.0, metric: 'LINE', baseline: 'PROJECT'],
                        [threshold: 60.0, metric: 'BRANCH', baseline: 'PROJECT']
                    ])
            }
        }

        stage('Create Pull Request') {
            when {
                allOf {
                    not { changeRequest() }
                    anyOf {
                        branch pattern: 'hotfix/.*', comparator: 'REGEXP'
                    }
                }
            }
            steps {
                script {
                    def existingPr = sh(
                        script: "gh pr list --state open --head ${env.BRANCH_NAME} --json number -q '.[].number'",
                        returnStdout: true
                    ).trim()

                    if (existingPr) {
                        echo "Pull request already exists for ${env.BRANCH_NAME}. Skipping creation."
                    } else {
                        sh """
                        gh pr create --base develop --head ${env.BRANCH_NAME} --title "Auto PR: ${env.BRANCH_NAME}" --body "Automatically created for review and testing."
                        """
                    }
                }
            }
        }

        stage('Tag Release Candidate') {
            when {
                allOf {
                    branch pattern: 'release/.*', comparator: 'REGEXP'
                    not { changeRequest() }
                }
            }
            steps {
                script {
                    def version = env.BRANCH_NAME.replaceAll(/^release\\/RC-/, 'v')
                    def tag = "${version}-rc"
                    def tagExists = sh(
                        script: "git fetch --tags && git tag -l ${tag}",
                        returnStdout: true
                    ).trim()

                    if (tagExists) {
                        error "Tag ${tag} already exists. Aborting tagging stage."
                    } else {
                        sh """
                        git config user.name "jenkins"
                        git config user.email "ci@softlivery.com"
                        git remote set-url origin git@github.com:Softlivery/whatsapp-cloud-api-client.git
                        git tag -a ${tag} -m "Release ${tag}"
                        git push origin ${tag}
                        """
                    }
                }
            }
        }

        stage('Auto PR: Release to Main') {
            when {
                allOf {
                    branch pattern: 'release/.*', comparator: 'REGEXP'
                    not { changeRequest() }
                }
            }
            steps {
                script {
                    def base = 'main'
                    sh """
                    gh pr create --base ${base} --head ${env.BRANCH_NAME} --title "Release Final: ${env.BRANCH_NAME}" --fill-verbose
                    """
                }
            }
        }

        stage('Tag Final on Main') {
            when {
                branch 'main'
            }
            steps {
                script {
                    def version = sh(script: "cat VERSION", returnStdout: true).trim()
                    def tag = "v${version}"
                    def tagExists = sh(
                        script: "git fetch --tags && git tag -l ${tag}",
                        returnStdout: true
                    ).trim()

                    if (tagExists) {
                        error "Tag ${tag} already exists. Aborting tagging stage."
                    } else {
                        sh """
                        git config user.name "jenkins"
                        git config user.email "ci@softlivery.com"
                        git remote set-url origin git@github.com:Softlivery/whatsapp-cloud-api-client.git
                        git tag -a ${tag} -m "Release ${tag}"
                        git push origin ${tag}
                        """
                    }
                }
            }
        }
    }

    post {
        success {
            script {
                echo "Build completed successfully."
            }
        }
    }
}
