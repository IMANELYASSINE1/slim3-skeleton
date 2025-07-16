pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer-cache"
        SONAR_TOKEN = credentials('mon_token_sonar') // Doit correspondre exactement à l'ID
    }

    stages {
        stage('Setup') {
            steps {
                sh 'sudo apt-get update && sudo apt-get install -y php php-cli php-xml'
                sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
            }
        }

        stage('Test') {
            steps {
                sh 'composer install'
                sh 'php vendor/bin/phpunit --log-junit tests/results.xml'
            }
            post {
                always {
                    junit 'tests/results.xml'
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') { // Doit correspondre au nom configuré
                    sh '''
                        sonar-scanner \
                          -Dsonar.projectKey=your-php-project \
                          -Dsonar.sources=. \
                          -Dsonar.host.url=http://votre-serveur-sonar:9000 \
                          -Dsonar.login=${SONAR_TOKEN} \
                          -Dsonar.php.coverage.reportPaths=tests/results.xml
                    '''
                }
            }
        }
    }
}
