## Installation Instructions

Requirements:
 - PHP webserver with database
 - Composer

1. Clone the repo to your local computer
2. Run 'composer install'
3. Create a blank SQL database (utf8mb4)
4. Copy the settings from .env.example to a new .env file and add database settings
5. Run 'php artisan key:generate'
6. Run 'php artisan migrate --seed'
7. Go to the admins table in the newly created database and update the filler text (name, CIS username, email)
8. Run 'php artisan serve' to start the development server
9. Go to /admin and create your first site
