
# Jeep Track System

The Jeep Track System is a web application designed to replace the manual process of managing jeep permits for off-road activities. Users can apply for permits online, track their application status, and get updates via email. The system also simplifies the work for administrators by providing tools to review, approve, or reject applications, as well as automate tasks like calculating permit fees and assigning permit numbers.

The project has gone through several revisions based on feedback from stakeholders, ensuring it meets the needs of both users and administrators. While still in development, it aims to streamline the entire permit process, making it more efficient and accessible. Comprehensive documentation is provided to support future updates and maintenance.

# Installation Guide

## Prerequisites

Before you begin, make sure you have the following installed on your system:

- PHP >= 8.0
- Composer
- MySQL or PostgreSQL (depending on your database choice)
- Node.js and npm (for frontend assets)
- Laravel >= 8.x

## Steps


1. Clone the repository
```
git clone https://github.com/yourusername/jeep-track-system.git
cd jeep-track-system
```

2. Install dependencies
* Install PHP dependencies using Composer
```
composer install
```

* Install Node.js dependencies
```
npm install
```

3. Set up environment variables
* Copy the `.env.example` file to create a new `.env` file
```
cp .env.example .env
```

* Open the `.env` file and configure your database and other settings


4. Generate application key
```
php artisan key:generate
```


5. Run database migrations
```
php artisan migrate
```

6. Seed the database (optional)
* If you have seed data available, you can seed the database. 

```
php artisan db:seed
```

7. Compile frontend assets
* Compile the frontend assets using Laravel Mix. 
```
npm run dev
```

8. Run the application

* Start the Laravel development server. 
```
php artisan serve
```

* Open your browser and go to `http://localhost:8000`. 


    
## Demo

### Customer Interface


### Admin Interface

https://github.com/vincentchoows/Jeep-Track-System/blob/main/public/github-image/image_5.png

## License

[MIT](https://choosealicense.com/licenses/mit/)

