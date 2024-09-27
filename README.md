# Apple Shop Admin Panel

This admin panel allows you to manage brands, products, and categories for the Apple Shop. Follow the steps below to set up the project:

1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Run `cp .env.example .env`
5. Run `php artisan key:generate`
6. Set your database credentials in the `.env` file
7. Set your mail credentials in the `.env` file
8. Run `php artisan migrate`
9. Run `php artisan serve` to start the test server
10. Run `npm run dev` to compile the assets for development

> PostMan API Documentation is available in the root directory of the project as a file named `Ecommerce.postman_collection.json`

-   Root URL: `http://127.0.0.1:8000`
