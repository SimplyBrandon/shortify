# URL Shortening Service

This project was created as part of the recruitment process for the position of Laravel Developer. The task was to create a URL shortening service using Laravel that provides the following functionality:
- A `/encode` endpoint that accepts a URL and returns a shortened version of that URL.
- A `/decode` endpoint that accepts a shortened URL alias and returns the original URL.
- Both endpoints return JSON responses.

## Additional Features
I have added a few extra features to enhance the project:
- **Persistent URLs**: URLs are stored in a MySQL database to reflect a real-world scenario.
- **Configurable Redirection Route**: Users can be redirected to the original URL via a configurable route. By default, this is `/go/{alias}` but can be changed in the `.env` file.
- **Usage Tracking**: A 'uses' column in the database increments each time a shortened URL is accessed, allowing tracking of URL popularity.
- **Custom Alias**: Users can provide a custom alias for their shortened URL. If the alias is already in use, an error message is returned.
- **Vue.js Frontend**: A simple Vue.js frontend is included which allows users to shorten URLs via a form and list the shortened URLs by recently created.
- **Easily Reusable**: This project could be used to easily create a URL shortening service for any website. The frontend can be easily modified to match the branding of the website.

## Installation
1. Clone the repository:
    ```sh
    git clone https://github.com/SimplyBrandon/shortify.git
    cd shortify
    ```
2. Install dependencies:
    ```sh
    composer install
    ```
3. Copy the `.env.example` file to `.env` and generate a new application key:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```
4. Update the `.env` file with your MySQL credentials and other necessary configurations (APP_NAME, APP_URL, SHORT_URL_PATH, etc).
5. Run database migrations to create the necessary schema:
    ```sh
    php artisan migrate
    ```
6. Build the frontend assets:
    ```sh
    npm install && npm run build
    ```

## Usage
The service provides four main endpoints:

### `/`
- **Method**: GET
- **Description**: Displays the Vue.js frontend for the URL shortening service.

### `/encode`
- **Method**: GET
- **Parameters**:
  - `url` (required): The original URL to be shortened.
  - `alias` (optional): A custom alias for the shortened URL.
- **Response**:
  - On success: JSON response with a `short_url` key containing the shortened URL.
  - On error: JSON response with an error message if the alias is already in use or the URL is invalid.

### `/decode`
- **Method**: GET
- **Parameters**:
  - `alias` (required): The alias of the shortened URL.
- **Response**:
  - On success: JSON response with a `original_url` key containing the original URL.
  - On error: JSON response with a 404 status if the alias is not found.

### `/go/{alias}`
- **Method**: GET
- **Description**: Redirects the user to the original URL.
- **Response**:
  - On success: Redirects to the original URL.
  - On error: Returns a 404 status if the alias is not found.

## Running Tests
To run the tests for this service, use the following command:
```sh
php artisan test
```

## Notes
- The technical test specified that the route endpoints should be /encode and /decode. However, in a real-world scenario, especially since these routes behave like API endpoints, I would recommend using /api/encode and /api/decode, the standard for Laravel API's routes.
- The technical test specified that the URLs did not need to be persistent, however to better reflect a real-world scenario for this type of service, I have made the URLs persistent in a MySQL database.
- The local dev environment for this project that I used had the project accessible at http://shortify.test. Shortened links were generated at http://shortify.test/go/{alias}. This makes the short links... not very short. In a real-world scenario, the shortened links would be generated at a much shorter URL, such as http://sho.rt/{alias}.
- The frontend for this project is quite basic, merely serving as a way to interact with the encode endpoint easily and view the shortened URLs.

## Time Spent
- 10-20m: Planning and researching (tinyurl.com, bit.ly)
- 1h30m: Implementing technical test requirements
- ~4h: Additional features & Vue.js frontend
### Total: ~6h
