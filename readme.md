## Installation Instructions

Requirements:
 - PHP webserver with database
 - Composer
 
1. Clone the repo to your local computer
2. Run 'composer install'
3. Copy the settings from .env.example to a new .env file and add database settings
4. Create a blank SQL database
5. Run 'php artisan migrate --seed'
6. Run 'php artisan serve' to start the development server
7. Go to the admins table in the newly created database and update the user column to your durham username
