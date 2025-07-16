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

        stage('Run tests') {
            steps {
                sh './vendor/bin/phpunit --configuration phpunit.xml --coverage-clover=coverage.xml'
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarServer') {
                    sh '''
                        curl -sSL -o sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip
                        unzip sonar-scanner.zip
                        export PATH=$PWD/sonar-scanner-4.8.0.2856-linux/bin:$PATH

                        sonar-scanner \
                          -Dsonar.projectKey=slim3-skeleton \
                          -Dsonar.sources=app/src \
                          -Dsonar.host.url=$SONAR_HOST_URL \
                          -Dsonar.login=$SONAR_TOKEN \
                          -Dsonar.php.coverage.reportPaths=coverage.xml
                    '''
                }
            }
        }
    }
}
