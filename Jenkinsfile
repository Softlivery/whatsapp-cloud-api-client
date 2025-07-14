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
                sh "${PHP_PATH} vendor/bin/phpunit --coverage-xml=coverage-xml/"
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
                    tools: [[parser: 'JACOCO', pattern: 'coverage-xml/*.xml']],
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
                        branch pattern: 'feature/.*', comparator: 'REGEXP'
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

        stage('Validate Release Creation') {
            when {
                branch 'develop'
            }
            steps {
                script {
                    def openRelease = sh(
                        script: "git ls-remote --heads origin 'refs/heads/release/*' | wc -l",
                        returnStdout: true
                    ).trim()

                    if (openRelease != '0') {
                        error("A release branch already exists. Close it before creating a new one.")
                    }

                    def nextVersion = sh(script: "./scripts/next-version.sh", returnStdout: true).trim()
                    def releaseBranch = "release/RC-${nextVersion}"
                    sh "git checkout -b ${releaseBranch}"
                    sh "git push origin ${releaseBranch}"
                }
            }
        }

        stage('Tag Final Release') {
            when {
                allOf {
                    branch pattern: 'release/.*', comparator: 'REGEXP'
                    not { changeRequest() }
                }
            }
            steps {
                script {
                    // Extract version like v1.2.3 from RC-v1.2.3
                    def version = env.BRANCH_NAME.replaceAll(/^release\\/RC-/, 'v')
                    sh """
                    git tag ${version}
                    git push origin ${version}
                    """
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
                    gh pr create --base ${base} --head ${env.BRANCH_NAME} --title "Release Final: ${env.BRANCH_NAME}" --body "Final production release."
                    """
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
