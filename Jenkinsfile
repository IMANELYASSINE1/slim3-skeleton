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

        stage('Install Composer and Dependencies') {
            steps {
                sh '''
                    
                    curl -sS https://getcomposer.org/installer | php
                    mv composer.phar ./composer

                    
                    ./composer config --no-plugins allow-plugins.cyclonedx/cyclonedx-php-composer true

                    
                    ./composer require --dev cyclonedx/cyclonedx-php-composer

                    
                    ./composer install --no-interaction --prefer-dist --dev

                    
                    ./composer -V
                    ./composer show cyclonedx/cyclonedx-php-composer || echo "Plugin cyclonedx non trouvé"
                '''
            }
        }

       stage('Generate SBOM') {
    steps {
        sh '''
            ./composer cyclonedx:make --output-format=json --output-file=bom.json
            echo "--- Contenu de bom.json ---"
            cat bom.json
        '''
    }
}


        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarServer') {
                    sh '''
                        set -e
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
        stage('Create Project in Dependency-Track') {
    steps {
        sh '''
            curl -X PUT "http://172.17.0.2:8080/api/v1/project" \
              -H "X-Api-Key: $DT_API_KEY" \
              -H "Content-Type: application/json" \
              -d '{
                    "name": "slim3-skeleton",
                    "version": "1.0.0"
                  }'
        '''
    }
}


       stage('Upload SBOM to Dependency-Track') {
    steps {
        sh '''
            base64 -w 0 bom.json > bom.base64
            curl -X PUT "http://172.17.0.2:8080/api/v1/bom" \
                -H "X-Api-Key: $DT_API_KEY" \
                -H "Content-Type: application/json" \
                -d '{
                      "projectName": "slim3-skeleton",
                      "projectVersion": "1.0.0",
                      "bom": "'$(cat bom.base64)'"
                    }'
        '''
    }
}

    }
}
