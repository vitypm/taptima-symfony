# taptima-symfony
### Для корректного запуска необходимо выполнить следующие команды:
* composer install
* php bin/console make:migration
* php bin/console doctrine:migrations:migrate

####Маршруты:

* "/" - Домашняя страница
* "/book" - каталог книг
* "/author" - каталог авторов
* "/login" - Вход в аккаунт
* "/register" - Регистрация аккаунта
   
#### Маршруты Admin-sonata:
  
* "/admin/login" - Вход
* "/admin/dashboard" - Главное меню 
* "/admin/app/author/list" - каталог авторов
* "/admin/app/book/list" - каталог книг
* "/admin/app/sonatauseruser/list" - список пользователей
* "/admin/app/sonatausergroup/list" - список групп
* "/admin/search" - поиск объектов

###Основные страницы:
Каталоги в которых содержится CRUD:
* книги
* авторы

<details>
  <summary>Мануал установки, на чистом Symfony, используя Sonata-admin</summary>

1. Устанавливаем Проект через compser:
- composer create-project symfony/website-skeleton:"^4.4" my_project_name
- composer create-project symfony/skeleton:"^4.4" my_project_name

2. Меняем composer.json на twig 2.4
   "twig/extra-bundle": "^2.9",
   "twig/twig": "^2.9"

3. Обновляем через composer изменения
- composer update

4. Для production устанавливаем .htaccess в корне проекта:
###########################################################
DirectoryIndex /public/index.php
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^(.+) $1 [L]

RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -f
RewriteRule ^(.+) /public/$1 [L]

Options +SymLinksIfOwnerMatch
RewriteRule ^(.*)$ /public/ [QSA,L]
###########################################################

5. Устанавливаем плагин, для того чтобы все добавленные машруты добавлялись
- composer require symfony/apache-pack
  После запуска команды, попросит добавить рецепт .htaccess в папку public, чтобы добавить просто соглашаемся

6. Устанавливаем и подключаем  admin-bundle
- composer require sonata-project/admin-bundle
  Очищаем кэш после установки
- php bin/console cache:clear
  Устанавливаем ресурсы для того чтобы все настройки плагина провезялись правильно
- php bin/console assets:install

7. Настраиваем Базу данных в .ENV

8. Подключаем Sonata doctrine-orm-admin-bundle, для прямой поддержки с работой базы данной.
   composer require sonata-project/doctrine-orm-admin-bundle

9. Создаем Сущности (Модели, Entity)
- php bin/console make:Entity

При выборе type field, если хотим создать сущности-> ManyToMany, OneToMany, OneToOne

При создании сущностей создать

public function __toString() {
return $this->getName(); // Своё поле
}


10. Создаем миграцию и отправляем в базу данных
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate

11. Для того чтобы быстро взаимодействовать с Admin-sonata после создании сущности:
    Данная команда генерирует Admin/Controller, в ней соглашаемся y, так как нужно будет привезать пользовательские crud
- php bin/console make:sonata:admin
  Выводит список созданных Сущностей через Sonata
- php bin/console sonata:admin:list

12.Устанавливаем doctrine-orm-admin-bundle, для взаимодействии Базы Данных
- composer require sonata-project/doctrine-orm-admin-bundle

13. Устанавливаем user-bundle
- composer require sonata-project/user-bundle

Дальше настраиваем все конфиги:
````
# Создаем в config/packages Создаем fos_user.php
//////////////////////////////////////////////////////////////////////////////////////////////////
fos_user:
db_driver: orm
firewall_name: main
user_class: App\Entity\User
group:
group_class: App\Entity\SonataUserGroup
group_manager: sonata.user.orm.group_manager
service:
user_manager: sonata.user.orm.user_manager
from_email:
address: "vitypm@gmail.com"
sender_name: "vitypm@gmail.com"
#
#default
#   user_class: App\Entity\SonataUserUser
# address: "%mailer_user%"
#    sender_name: "%mailer_user%"

    #   Возможно  user_class: App\Entity\User
    #   from_email:
    #    address: "vitypm@gmail.com"
    #    sender_name: "123123as123"
//////////////////////////////////////////////////

# Заходим в файл: framework.yaml
Добавляем ->
templating:
engines: ['twig','php']

//////////////////////////////////////////////////
# Заходим в файл: routes.yaml
Добавляем ->
fos_user:
resource: "@FOSUserBundle/Resources/config/routing/all.xml"
sonata_user_admin_security:
resource: '@SonataUserBundle/Resources/config/routing/admin_security.xml'
prefix: /admin

sonata_user_admin_resetting:
resource: '@SonataUserBundle/Resources/config/routing/admin_resetting.xml'
prefix: /admin/resetting

//////////////////////////////////////////////////
# Заходим в файл: security.yaml
Добавляем ->
encoders:
FOS\UserBundle\Model\UserInterface: auto
role_hierarchy:
ROLE_ADMIN:       [ROLE_USER, ROLE_SONATA_ADMIN]
ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
SONATA:
- ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT  # if you are using acl then this line must be commented

providers:
fos_userbundle:
id: fos_user.user_provider.username
firewalls:
admin:
pattern:            /admin(.*)
context:            user
form_login:
provider:       fos_userbundle
login_path:     /admin/login
use_forward:    false
check_path:     /admin/login_check
failure_path:   null
logout:
path:           /admin/logout
target:         /admin/login
anonymous:          true
main:
pattern: ^/
user_checker: security.user_checker
form_login:
provider: fos_userbundle
csrf_token_generator: security.csrf.token_manager
anonymous: true
logout: true
provider: users_in_memory

    access_control:
        - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/logout$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/login_check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: [ROLE_ADMIN, ROLE_SONATA_ADMIN] }
        - { path: ^/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }

//////////////////////////////////////////////////
# Создаем Entity SonataUserGroup.php, SonataUserUser.php
Переходим к экземпляром, особо внимательно смотрим на пункт
/**
* @ORM\Entity
* @ORM\Table(name="fos_user__user")
  */

ссылка: https://sonata-project.org/bundles/user/4-x/doc/reference/installation.html



//////////////////////////////////////////////////
# Создаем sonata_user.yaml
sonata_user:

    class:
        user: App\Entity\SonataUserUser
        group: App\Entity\SonataUserGroup

//////////////////////////////////////////////////
# 
ссылка: http://blog.jmoz.co.uk/symfony2-fosuserbundle-roles/
Создаем user:
php app/console fos:user:create
Поднимаем user уровень ->
php bin/console fos:user:promote vitypmm --super
//////////////////////////////////////////////////
# Создаем doctrine.yaml
mappings:
SonataUserBundle: ~
FOSUserBundle: ~

````
</details>

Полезные ссылки:
* [документация sonata-admin](https://sonata-project.org/bundles/admin/3-x/doc/index.html)
* [документация Symfony](https://symfony.com/doc/current/index.html)
