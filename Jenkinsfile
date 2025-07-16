pipeline {
    agent {
        docker {
            image 'php:8.2-cli'  // Image officielle PHP avec tout le nécessaire
            args '-v /tmp:/tmp'
        }
    }
    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer-cache"
        SONAR_TOKEN = credentials('mon_token_sonar')
    }
    stages {
        stage('Check Versions') {
            steps {
                sh 'php -v'
                sh 'composer --version'
            }
        }
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
            }
        }
        stage('Run Tests') {
            steps {
                sh 'php vendor/bin/phpunit --log-junit tests/results.xml'
            }
        }
        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh 'sonar-scanner -Dsonar.login=${SONAR_TOKEN}'
                }
            }
        }
    }
}
