# Изучение фреймворка Laravel на примере разработки интернет магазина.

* Клонирование проекта:
    - cd project
    - git clone https://github.com/RomanNikolaevich/laravel1.git

* Установите все зависимости, которые определены в файле composer.json, запустив в терминале команду composer install. Это установит все необходимые пакеты, которые проект использует.
* Создайте файл .env на основе файла .env.example и заполните его соответствующими значениями. Файл .env содержит конфигурационные параметры, такие как параметры подключения к базе данных, настройки почтового сервера и т.д.
* Запустите команду php artisan key:generate, чтобы сгенерировать новый ключ приложения Laravel, который используется для шифрования данных.
* Запустите Docker Desktop, если он не запущен (если ваша основная OS Linux, то там можно без установленного Docker Desktop работать прекрасно, но Docker долшжен быть установлен)

* Запустить сервер:
    - sail up -d
* Запустить миграцию и сиды:
    - sail artisan migrate:refresh --seed
* В браузере перейти на lockalhost
* Нажать на кнопку "Скинути проект у почтаковий стан"
* Если не отобразились картинки, то в тепрминале пишем:
    - sail artisan storage:link
 

