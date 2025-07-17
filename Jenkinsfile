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

          
          ./composer require --dev cyclonedx/cyclonedx-php-composer

          
          ./composer install --no-interaction --prefer-dist --dev

          ./composer -V
          ./composer show cyclonedx/cyclonedx-php-composer || echo "Plugin cyclonedx non trouvé"
        '''
    }
}

stage('Generate SBOM') {
    steps {
        sh './composer cyclonedx:make --output-format=json --output-file=bom.json'
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

        stage('Upload SBOM to Dependency-Track') {
            steps {
                sh '''
                    set -e
                    curl -X PUT "http://172.17.0.4:8080/api/v1/bom" \
                        -H "X-Api-Key: $DT_API_KEY" \
                        -F "projectName=slim3-skeleton" \
                        -F "projectVersion=1.0.0" \
                        -F "bom=@bom.json"
                '''
            }
        }
    }
}
