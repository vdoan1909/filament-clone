# Setup Project

### Update composer dependencies

```bash
composer update
```

### Create a copy of your .env file

```bash
cp .env.example .env
```

### Generate an app encryption key

```bash
php artisan key:generate
```

### Create an empty database for our application

Create a database in your local machine and update the database credentials in .env file

### Migrate the database

```bash
php artisan migrate
```

### Seed the database (Optional, If any)

```bash
php artisan db:seed
```

### Start the development server

```bash
php artisan serve
```
