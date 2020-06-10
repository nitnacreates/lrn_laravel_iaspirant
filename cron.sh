#/bin/bash
cd /var/www/vhosts/envphp/seven/iaspirant.stg.icu/htdocs/
php artisan schedule:run | awk '{ print strftime("%Y-%m-%d %H:%M:%S"), $0; fflush(); }' | grep -v 'No scheduled commands are ready to run.' >> /var/www/vhosts/envphp/seven/iaspirant.stg.icu/htdocs/storage/logs/cron.log 2>&1
