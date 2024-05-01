# Todo API

>

### Requirements

- PHP >= 8.2
- Composer
- Laravel 11.x
- Docker
- Docker Compose

### Installation

1. Clone the repository

    ```sh
    https://github.com/usyeimar/backend-exam-test-solati.git
    ```

2. Install dependencies

    ```sh
    cd backend  && composer install
    ```

3. Create a copy of the .env file

    ```sh
    cp .env.example .env
    ```

4. Generate the application key

    ```sh
    php artisan key:generate
    ```

5. Run the migrations and seeders

    ```
    php artisan migrate --seed
    ```

6. Project comes with Passport include as the default authenticatin method. You should now install it using this
   command.

    ```
    php artisan passport:install
    ```

7. Copy-paste the generated secrets and IDs into your `.env` file like so.

    ```
    PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
    PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=mR7k7ITv4f7DJqkwtfEOythkUAsy4GJ622hPkxe6
    
    ```

8. Run the application

    ```sh
    php artisan serve
    ```

9. Run Postman API

   [<img src="https://run.pstmn.io/button.svg" alt="Run In Postman" style="width: 128px; height: 32px;">](https://app.getpostman.com/run-collection/14969501-31fac7be-60ad-4188-a53d-2863a977eab5?action=collection%2Ffork&source=rip_markdown&collection-url=entityId%3D14969501-31fac7be-60ad-4188-a53d-2863a977eab5%26entityType%3Dcollection%26workspaceId%3D08af0b8c-1618-460b-a1d3-f902ca38ca53)

### Code Quality Tools

- [Laravel Pint](https://github.com/laravel/pint)

### ✅ Tests execution

```sh
    php artisan test
```
