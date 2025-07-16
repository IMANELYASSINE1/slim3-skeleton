pipeline {
    agent {
        docker {
            image 'php:8.1-cli'  // Image officielle PHP CLI
            args '-u root:root'  // exécuter en root (optionnel, facilite l'install)
        }
    }

    environment {
        SONAR_TOKEN = credentials('mon_token_sonar')
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install composer') {
            steps {
                sh '''
                php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
                php composer-setup.php --install-dir=/usr/local/bin --filename=composer
                rm composer-setup.php
                '''
            }
        }

        stage('Install dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Run Tests') {
            steps {
                sh './vendor/bin/phpunit --configuration phpunit.xml --log-junit tests/reports/phpunit-report.xml || true'
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarServer') {
                    sh """
                    sonar-scanner \
                        -Dsonar.projectKey=slim3-skeleton \
                        -Dsonar.sources=. \
                        -Dsonar.host.url=$SONAR_HOST_URL \
                        -Dsonar.login=$SONAR_TOKEN
                    """
                }
            }
        }
    }

    post {
        always {
            junit 'tests/reports/phpunit-report.xml'
        }
    }
}
