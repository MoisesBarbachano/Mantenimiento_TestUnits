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
                    bat 'call vendor/bin/phpunit.bat core/test --log-junit results/phpunit.xml'
                }
            }
            post {
                success {
                    echo 'Testing successful'
                    bat 'cd C:/xampp12082019/htdocs/Memorama-mamaster & git pull origin'

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