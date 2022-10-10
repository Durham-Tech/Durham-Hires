# Durham Hires

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

## Docker development

1. Copy .env.docker.example to .env
2. Update .env with GOOGLE_APP_ID and GOOGLE_APP_SECRET
3. Run `docker-compose up --build`
4. Access the site at `http://localhost:8000`
