# Выгрузка данных из API https://github.com/cy322666/wb-api

## Установка
```bash
git clone git@github.com:vamcart/fetch-api-data.git
cd fetch-api-data 
composer install
cp .env.example .env
php artisan key:generate
```
## Запускаем миграции
```bash
php artisan migrate
```

## Команды для выгрузки данных
Command `php artisan app:fetch-stocks`

## Запуск
Стартуем встроенный веб-сервер:
```bash
php artisan serve
```
Смотрим записанные в базу данные `http://127.0.0.1:8000/stocks`.

## Запуск по расписанию
Добавляем в `app/routes/console.php`:
```php
Schedule::command('app:fetch-stocks')->everyFiveMinutes();
```
Запускаем плашировщик: `php artisan schedule:work` , либо добавляем в cron строку запуска планировщика.

Каждые 5 минут будет выгружаться сток.

