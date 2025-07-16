pipeline {
    agent any

    environment {
        SONAR_TOKEN = credentials('mon_token_sonar')
        DT_API_KEY = credentials('mon_token_dependencytrack')
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Generate SBOM') {
            steps {
                sh '''
                    composer global require cyclonedx/cyclonedx-php-composer
                    ~/.composer/vendor/bin/cyclonedx-php-composer make --output-format xml --output-file bom.xml
                '''
            }
        }

        stage('Send to Dependency-Track') {
            steps {
                sh '''
                    curl -X "POST" "http://localhost:8081/api/v1/bom" \
                      -H "X-Api-Key: $DT_API_KEY" \
                      -H "Content-Type: multipart/form-data" \
                      -F "project=83bbd561-b5e2-4d91-a596-78d584098b37" \
                      -F "bom=@bom.xml"
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarServer') {
                    sh '''
                        curl -sSL -o sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip
                        unzip -oq sonar-scanner.zip
                        rm -f sonar-scanner.zip  
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
    }
}
