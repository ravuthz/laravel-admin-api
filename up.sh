#/bin/sh

docker rm -f $(docker ps -aq)

docker rm -f ravuthz/laravel-admin

docker build -f ./Dockerfile -t ravuthz/laravel-admin .

docker run -d --name laravel-admin -p 9191:80 ravuthz/laravel-admin

docker ps

curl -H "Accept: application/json" http://localhost:9191/api | jq

sleep 1

docker exec -it laravel-admin bash -c "
    nginx -t;
    php-fpm -t;
    service --status-all;
    composer -V;
    # node -v;
    # npm -v;
    # yarn -v;
    # cat /etc/nginx/nginx.conf;
    # cat /etc/nginx/sites-available/default;
    # cat /etc/supervisor/conf.d/supervisord.conf;
"

# find / -name php-fpm;
