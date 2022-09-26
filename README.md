1. git clone git@github.com:chesser25/server.git
2. cd server
3. docker-compose up
4. docker exec -ti <id_of_app_container> bash (for Ubuntu)
5. composer install
6. php bin/console doctrine:migrations:migrate
