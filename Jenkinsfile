pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer-cache"
    }

    stages {
        stage('Check PHP version') {
            steps {
                sh 'php -v'
            }
        }

        stage('Check Composer version') {
            steps {
                sh 'composer -V'
            }
        }

        stage('Install dependencies') {
            steps {
                sh 'composer install'
            }
        }

        stage('Run PHPUnit tests') {
            steps {
                sh './vendor/bin/phpunit'
            }
        }
    }

    post {
        always {
            junit 'tests/*.xml' // si rapport XML généré
        }
    }
}
