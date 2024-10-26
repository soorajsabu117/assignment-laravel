clone the repository
rename .env.example to .env
create a mysql database and add db name,user and passwords in env
run composer install
php artisan migrate

run
php artisan serve --port=9000

make sure u are running in port 9000 else replace the new port in env and reacts src/config.js file also
