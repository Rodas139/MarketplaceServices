<p align="center"><a href="https://enovo.pt" target="_blank"><img src="https://enovo.pt/assets/img/logo/logo.svg" width="400"></a></p>

# Projeto Base

Admin credentials: suporte@enovo.pt,  password - to change `database\seeders\BaseSeeder.php`

## To Do
- Run `composer install`
- Configure the .env.example and change the name for .env
    - Update Master DB Conection params
- Run  `php artisan migrate`
- Run  `php artisan db:seed`
- Set up _.env_
    - Update `APP_URL`
    - Update `APP_SOURCE`
    - Update `APP_TITLE`
    - Update `APP_SKIN`- css files are at `public\assets\admin\css\skins`
- Search in all project for 'projetoBase' and change as needed

## Documentation
A list of some package's documentation is available at `documentation.md`

## Deployment
- Update .env
    - Update `APP_URL`
    - Update `APP_ENV` to __production__
    - Update `APP_DEBUG` to __false__
    - Update Master DB Conection params
- Update logos `public\assets\img\logo`
- Go to /start of your application, to run needed migrations. (Don't forget to remove that route in Route/web.php)


I recommend to use source control.
Good luck!
