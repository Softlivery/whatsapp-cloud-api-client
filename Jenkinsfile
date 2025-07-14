pipeline {
    agent any

    environment {
        PHP_PATH = '/usr/bin/php'
        COMPOSER_PATH = '/usr/local/bin/composer'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh "${COMPOSER_PATH} install"
            }
        }

        stage('Run PHPUnit Tests') {
            steps {
                sh "${PHP_PATH} vendor/bin/phpunit --coverage-clover=coverage.xml"
            }
        }

        stage('Publish Coverage') {
            steps {
                recordCoverage tools: [genericCoverage(pattern: 'coverage.xml', parser: 'COBERTURA')]
            }
        }

        stage('Create/Approve Pull Request') {
            when {
                branch pattern: 'feature/.*', comparator: 'REGEXP'
            }
            steps {
                script {
                    def branchName = env.BRANCH_NAME
                    def targetBranch = 'dev'
                    sh """
                    gh pr create --base ${targetBranch} --head ${branchName} --title "Auto PR: ${branchName}" --body "Created automatically after tests passed"
                    """
                }
            }
        }

        stage('Merge Pull Request') {
            when {
                branch pattern: 'feature/.*', comparator: 'REGEXP'
            }
            steps {
                script {
                    def prUrl = sh(script: "gh pr list --state open --head ${env.BRANCH_NAME} --json url -q '.[0].url'", returnStdout: true).trim()
                    if (prUrl) {
                        sh "gh pr merge ${prUrl} --auto --merge"
                    }
                }
            }
        }

        stage('PR to Release/Main') {
            when {
                branch 'dev'
            }
            steps {
                script {
                    sh """
                    gh pr create --base release/0.0 --head dev --title "Release Prep" --body "Merging dev into release/0.0"
                    """
                }
            }
        }
    }
}