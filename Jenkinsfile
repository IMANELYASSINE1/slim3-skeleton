pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}\\.composer-cache"
    }

    stages {
        stage('Check PHP version') {
            steps {
                bat 'php -v'
            }
        }

        stage('Check Composer version') {
            steps {
                bat 'composer -V'
            }
        }

        stage('Install dependencies') {
            steps {
                bat 'composer install'
            }
        }

        stage('Run PHPUnit tests') {
            steps {
                bat 'vendor\\bin\\phpunit'
            }
        }
    }
}
