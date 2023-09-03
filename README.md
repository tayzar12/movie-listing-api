## Installation

Clone the repository

```bash
  git clone git_project_url
  cd my_project
```

Install all the dependencies using composer

```bash
  composer install
```

Copy the example env file and make the required configuration changes in the .env file

```bash
  cp .env.example .env
```

Generate a new application key

```bash
  php artisan key:generate
```

Run the database migrations and seeders(**Set the database connection in .env before migrating**)

```bash
  php artisan migrate --seed
```

Run the command to create the encryption keys needed to generate secure access token

```bash
  php artisan passport:install
```

To create the symbolic link

```bash
  php artisan storage:link
```

Start the local development server

```bash
  php artisan serve
```

You can now access the server at http://localhost:8000

---

To refresh/fresh the database(**Run php artisan passport:install after refresh the database**)

```bash
   php artisan migrate:fresh --seed
```

## Demo Account

**Email:** admin@example.com

**Password:** password
