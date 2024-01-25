docker-compose up -d

sleep 20

docker-compose exec backend php /var/www/html/artisan migrate
