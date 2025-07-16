pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer-cache"
    }

    stages {
        stage('Setup PHP') {
            steps {
                sh 'sudo apt-get update && sudo apt-get install -y php php-cli php-xml'
            }
        }
        stage('Check versions') {
            steps {
                sh 'php -v'
                sh 'composer -V'
            }
        }
        stage('Run tests') {
            steps {
                sh 'composer install'
                sh 'php vendor/bin/phpunit --log-junit tests/results.xml'
            }
        }
    }

    post {
        always {
            junit 'tests/results.xml'
        }
    }
}
