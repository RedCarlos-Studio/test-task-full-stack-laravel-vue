# To run locally 
1. Run 
`docker-compose up -d`

2. Next 
   `docker-compose exec backend php /var/www/html/artisan migrate`
If it cause error try to rerun it again. 

3. Go to  `localhost:3001`

# Run backend feature test
`docker-compose exec backend php /var/www/html/artisan test`