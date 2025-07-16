pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}\\.composer-cache"  // Backslash pour Windows
    }

    stages {
        stage('Check PHP version') {
            steps {
                bat 'php -v'  // Remplacé 'sh' par 'bat'
            }
        }

        stage('Check Composer version') {
            steps {
                bat 'composer -V'  // Remplacé 'sh' par 'bat'
            }
        }

        stage('Install dependencies') {
            steps {
                bat 'composer install'  // Remplacé 'sh' par 'bat'
            }
        }

        stage('Run PHPUnit tests') {
            steps {
                bat 'vendor\\bin\\phpunit'  // Backslash et suppression du './'
            }
        }
    }

    post {
        always {
            junit 'tests\\*.xml'  // Backslash pour Windows
        }
    }
}
