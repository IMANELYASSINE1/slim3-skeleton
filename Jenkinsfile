pipeline {
    agent any

    environment {
        SONAR_TOKEN = credentials('mon_token_sonar')
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarServer') {
                    sh '''
                        # Téléchargement et décompression sonar-scanner
                        curl -sSL -o sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip
                        unzip sonar-scanner.zip
                        export PATH=$PWD/sonar-scanner-4.8.0.2856-linux/bin:$PATH

                        # Lancer l'analyse SonarQube
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
