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

## Installation
#### 1. Clone the repository:
    ```sh
    git clone https://github.com/SimplyBrandon/shortify.git
    cd shortify
    ```
#### 2. Install dependencies:
    ```sh
    composer install
    ```
#### 3. Copy the `.env.example` file to `.env` and generate a new application key:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```
#### 4. Update the `.env` file with your MySQL credentials and other necessary configurations (APP_URL, SHORT_URL_PATH, etc).
#### 5. Run database migrations to create the necessary schema:
    ```sh
    php artisan migrate
    ```

## Usage
The service provides three main endpoints:

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
