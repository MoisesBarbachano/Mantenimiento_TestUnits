pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building..'
            }
        }
        stage('Test') {
            steps {
                catchError {
                    echo 'Testing..'
                    bat './vendor/bin/phpunit core/test --log-junit results/phpunit.xml'
                }
            }
            post {
                success {
                    echo 'Testing successful'
                    bat 'cd C:/xampp/htdocs/Proyecto_Mantenimiento & git pull origin'

                }
                failure {
                    echo 'Testing stage failed'
                }
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}