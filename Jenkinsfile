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
                    def localBranchExists = sh(script: "git branch --list ${releaseBranch}", returnStdout: true).trim()

                    if (localBranchExists) {
                        echo "Deleting existing local branch: ${releaseBranch}"
                        sh "git branch -D ${releaseBranch}"
                    }

                    sh """
                    sed -i -E 's/"version": *"[0-9a-zA-Z\\.-]+"/"version": "'"${nextVersion}"'"/' composer.json

                    git config user.name "jenkins"
                    git config user.email "ci@softlivery.com"
                    git add VERSION composer.json
                    git commit -m "Bump version to ${nextVersion}"
                    git checkout -b ${releaseBranch}
                    git remote set-url origin git@github.com:Softlivery/whatsapp-cloud-api-client.git
                    git push origin release/RC-${nextVersion}
                    """
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
                    def version = env.BRANCH_NAME.replaceAll(/^release\\/RC-/, 'v')
                    sh """
                    gh pr create --base ${base} --head ${env.BRANCH_NAME} --title "Release ${version}"  --body "Final production release from ${env.BRANCH_NAME} to main."
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

        stage('Backport: Main to Develop') {
            when {
                branch 'main'
            }
            steps {
                script {
                    sh """
                    git fetch origin main
                    git fetch origin develop
                    """

                    def prExists = sh(
                        script: "gh pr list --state open --head main --base develop --json number -q '.[].number'",
                        returnStdout: true
                    ).trim()

                    if (prExists) {
                        echo "Backport PR from main to develop already exists."
                    } else {
                        sh """
                        gh pr create \\
                          --base develop \\
                          --head main \\
                          --title "Backport: Sync main to develop" \\
                          --body "Automatically created backport PR after release."
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
