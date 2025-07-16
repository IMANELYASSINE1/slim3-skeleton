pipeline {
    agent any

    environment {
        COMPOSER_CACHE_DIR = "${WORKSPACE}/.composer-cache"
        SONAR_TOKEN = credentials('sonarqube_token') // ID de vos credentials SonarQube dans Jenkins
    }

    stages {
        // Installation des dépendances
        stage('Setup Environment') {
            steps {
                sh 'sudo apt-get update && sudo apt-get install -y php php-cli php-xml unzip'
                sh 'wget https://getcomposer.org/installer -O composer-setup.php'
                sh 'php composer-setup.php --install-dir=/usr/local/bin --filename=composer'
            }
        }

        // Vérification des versions
        stage('Check Versions') {
            steps {
                sh 'php -v'
                sh 'composer --version'
            }
        }

        // Installation des dépendances PHP
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        // Exécution des tests avec génération du rapport JUnit
        stage('Run Tests') {
            steps {
                sh 'php vendor/bin/phpunit --log-junit tests/results.xml'
            }
            post {
                always {
                    junit 'tests/results.xml'
                }
            }
        }

        // Analyse SonarQube (nécessite le scanner Sonar pour PHP)
        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') { // Nom de votre configuration Sonar dans Jenkins
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
