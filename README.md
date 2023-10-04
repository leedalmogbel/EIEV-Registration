### INSTALL
- PHP 8
- MySQL 8
- yarn

### INSTALL
- Run `composer install`
- Run `cp .env.example .env`
- Run `php artisan key:generate`
- Edit `.env` file to set database
- Run `yarn install --modules-folder ./public/components` (optional)
- Run `php artisan migrate`
- Run `php artisan db:seed --class=UserSeeder && php artisan db:seed --class=RoleSeeder`


### SYNC COMMANDS
php artisan command:syncevents --ip=eievadmin --host=admineiev
php artisan command:syncentries --ip=eievadmin --host=admineiev
php artisan command:syncstables --ip=eievadmin --host=admineiev
php artisan command:syncowners --ip=eievadmin --host=admineiev
php artisan command:synctrainers --ip=eievadmin --host=admineiev
php artisan command:syncprofiles --ip=eievadmin --host=admineiev
php artisan command:syncriders --ip=eievadmin --host=admineiev
php artisan command:synchorses --ip=eievadmin --host=admineiev
### COMMANDS TO ADD
UniqueID Generator

### TESTING / DEV ENVIRONMENT
- This enviroment is only for testing purposes, the data here is different from the production environment.
- clone the repository in your local machine with the dev/test branch ( NOT THE MASTER BRANCH!!! )
- make sure you have also the enviroment variables available for the tests on your local machine
- for the user account for development you can only use this USER account
- username: `admin@alwathbastables.com`
- password: `eiev123456`
- for the admin account for development
- username: `eiev@eiev.ae`
- password: `eievteam1234$`
- There is also APIs to fetch the DATA and merge it into your local machine. Be informered that you need to get a copy of the existing DATA from the live and import it to the local machine.
- {domain name}/api/eievsync?sync=entries to fetch the updated ENTRIES FEDERATION DATA and merge it into your local machine.
- {domain name}/api/eievsync?sync=events to fetch the updated EVENTS FEDERATION DATA and merge it into your local machine.
- {domain name}/api/eievsync?sync=horses to fetch the updated ENTRIES FEDERATION DATA and merge it into your local machine.
- {domain name}/api/eievsync?sync=trainers to fetch the updated EVENTS FEDERATION DATA and merge it into your local machine.
- {domain name}/api/eievsync?sync=owners to fetch the updated ENTRIES FEDERATION DATA and merge it into your local machine.
- {domain name}/api/eievsync?sync=stables to fetch the updated EVENTS FEDERATION DATA and merge it into your local machine.
