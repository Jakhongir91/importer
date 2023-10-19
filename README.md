# There is console command for importing csv files

`php artisan import:csv`

Put file for import in the root in `dumps` directory.


## Deploy instructions
- git clone
- composer install
- install redis
- check redis env params in .env
  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  REDIS_CLIENT=predis
