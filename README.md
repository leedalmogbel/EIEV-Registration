### INSTALL
- PHP 8
- MySQL 8
- yarn

### INSTALL

- Run `php artisan key:generate`
- Edit `.env` file to set database
- Run `yarn install --modules-folder ./public/components`
- Run `php artisan migrate`
- Run `php artisan db:seed --class=UserSeeder` and `php artisan db:seed --class=RoleSeeder`


