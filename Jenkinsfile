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
                script {
                    def projectName = "slim3-skeleton"
                    def projectVersion = "1.0.0"
                    def projectDescription = "Projet créé automatiquement via Jenkins pipeline"

                    sh """
                    curl -X POST "http://172.17.0.2:8080/api/v1/project" \
                        -H "Content-Type: application/json" \
                        -H "X-Api-Key: ${DT_API_KEY}" \
                        -d '{
                            "name": "${projectName}",
                            "version": "${projectVersion}",
                            "description": "${projectDescription}"
                        }' || echo "Projet déjà existant ou erreur lors de la création"
                    """
                }
            }
        }
        stage('Get Project UUID') {
            steps {
                script {
                    def projectName = "slim3-skeleton"
                    def projectVersion = "1.0.0"

                    def uuid = sh(
                        script: """
                        curl -s -X GET "http://172.17.0.2:8080/api/v1/project?name=${projectName}&version=${projectVersion}" \
                            -H "X-Api-Key: ${DT_API_KEY}" | jq -r '.[0].uuid'
                        """,
                        returnStdout: true
                    ).trim()

                    if (uuid == "null" || uuid == "") {
                        error("❌ Le projet n'existe pas ou le UUID n'a pas pu être récupéré.")
                    }

                    echo "✅ UUID du projet récupéré : ${uuid}"
                }
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
    }
}  
