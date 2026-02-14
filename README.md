## Зависимости
```shell
composer install
```
Локальная версия npm `10.9.3`
```shell
npm install
```

## Развёртывание
- Для локального развёртывания проекта я использовал `laravel sail`:
```shell
sail up -d
```
- Миграции
```shell
sail artisan migrate
```
- Сборка фронта:
```shell
npm run build
```
- Запуск фронта:
```shell
npm run dev
```
- Создание тестового пользователя:
```shell
sail artisan db:seed
```
Тестовый пользователь создаётся с кредами:
- test@gmail.com
- qwe123

## Запуск Job и очереди
Я локально открывал в двух консольках команды:
```shell
sail artisan schedule:work
```
```shell
sail artisan queue:work
```

## ENV
локальный `.env` файл:
```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:+YVSvqQHVPdS8khBa/tes/eykhhKcQFBmPrS0MKALog=
APP_DEBUG=true
APP_URL=http://localhost:80

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=click_to_mice
DB_USERNAME=sail
DB_PASSWORD=password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis

CACHE_STORE=redis
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

CATALOG_SERVICES_BASE_URL=https://app.evgenybelkin.ru
CATALOG_SERVICES_API_TOKEN=21test105key

ORDER_SERVICE_BASE_URL=https://app.evgenybelkin.ru
ORDER_SERVICE_API_TOKEN=21test105key
```

## Краткое описание

### Обработка заказа
Когда создаётся заказ и отправляется "на обработку", наша Job (PollExternalServiceJob.php) отрабатывается 1 раз в минуту
получая актуальный статус заказа на внешнем сервисе, синхронизируя локальный статус и статус получаемый с сервиса.

### Получение услуг
Есть ServiceCatalog сервис, который получает их по http, изначально я думал сделать таблички в базе, чтобы тоже синхронизовать
локально и между внешним сервисом услуги, но потом подумал что не хочу создавать доп таблицу в бд + связь с orders и Job, 
поэтому сделал просто http клиент + кэширование на 10 минут, чтобы не ддосить внешний сервис запросами и ускорить получение
услуг тем, что получаю их из кэша `redis`.

### Фронт
После авторизации и создания первого заказа, страница dashboard делает запрос на получение заказов каждые 30 секунд,
сделано для удобства, чтобы когда заказ перейдёт из статуса "на обработке" в "готово" не нужно было вручную обновлять страницу.

На реальном проекте, можно было бы придумать реализацию через вебсокеты.
