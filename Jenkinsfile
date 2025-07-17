pipeline {
    agent any
    environment {
        DT_API_KEY = credentials('mon_token_dependencytrack')
        SONAR_TOKEN = credentials('mon_token_sonar')
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Create Project in Dependency-Track') {
            steps {
                sh '''
                    API_KEY=$DT_API_KEY
                    curl -X POST "http://172.17.0.4:8080/api/v1/project" \
                        -H "Content-Type: application/json" \
                        -H "X-Api-Key: $API_KEY" \
                        -d '{
                            "name": "slim3-skeleton",
                            "version": "1.0.0",
                            "description": "Projet créé automatiquement via Jenkins pipeline"
                        }' || echo "Projet déjà existant ou erreur lors de la création"
                '''
            }
        }

        stage('Install Node.js (for Sonar)') {
            steps {
                sh '''
                    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
                    apt-get install -y nodejs
                    node -v
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarServer') {
                    sh '''
                        curl -sSL -o sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip
                        unzip -oq sonar-scanner.zip
                        export PATH=$PWD/sonar-scanner-4.8.0.2856-linux/bin:$PATH
                        sonar-scanner \
                          -Dsonar.projectKey=slim3-skeleton \
                          -Dsonar.sources=. \
                          -Dsonar.host.url=$SONAR_HOST_URL \
                          -Dsonar.login=$SONAR_TOKEN
                    '''
                }
            }
        }

        stage('Install Composer') {
            steps {
                sh '''
                    curl -sS https://getcomposer.org/installer | php
                    mv composer.phar /usr/local/bin/composer
                    composer -V
                '''
            }
        }

        stage('Generate and Upload SBOM to Dependency-Track') {
            steps {
                sh '''
                    composer require --dev cyclonedx/cyclonedx-php-composer
                    vendor/bin/cyclonedx-composer make --output-format json --output-file bom.json
                '''

                sh '''
                    API_KEY=$DT_API_KEY
                    curl -X PUT "http://172.17.0.4:8080/api/v1/bom" \
                        -H "X-Api-Key: $API_KEY" \
                        -F "projectName=slim3-skeleton" \
                        -F "projectVersion=1.0.0" \
                        -F "bom=@bom.json"
                '''
            }
        }
    }
}
