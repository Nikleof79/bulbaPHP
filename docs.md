# BulbaPHP Documentation

BulbaPHP is a lightweight PHP micro-framework designed for rapid web development. It provides a simple structure for handling HTTP requests, routing, and middleware.

## Setup

- **1** Copy file ``` BulbaPHP.php ```
- **2** Paste it into ``` /lib/bulba/BulbaPHP.php ```

## Main Class: BulbaApp

The `BulbaApp` class is the core of the framework. It manages routing and the execution of middleware.

### Main Methods:

- **`__construct()`**: Initializes the application and sets the request URL.
  
- **`use($url, $param, $callback)`**: Adds a middleware to the application.
  
- **`setFreeFolders($folders)`**: Sets publicly accessible folders.
  
- **`req($url, $param, $function)`**: Defines a route with its callback function.

### Middleware Handling

Middlewares can be added using the `use()` method. They can be configured to run for specific URLs or under certain conditions.

## Class: BulbaAppRes

This class handles HTTP responses.

### Main Methods:

- **`send($x)`**: Sends a plain text response.
  
- **`sendJson($x)`**: Sends a JSON response.
  
- **`include($x)`**: Includes a PHP file.
  
- **`sendFile($x)`**: Sends a file to the client.
  
- **`render($x, $file_extension = '.php')`**: Renders a view file.
  
- **`redirect($x)`**: Redirects to a specified URL.
  
- **`header($type, $value)`**: Sets a custom header for the response.

## Class: BulbaAppReq

This class encapsulates HTTP request data.

### Main Properties:

- **`url`**: The URL of the request.
  
- **`ip`**: The client's IP address.
  
- **`body`**: The body of the request (GET/POST data).
  
- **`files`**: Uploaded files.
  
- **`param`**: Route parameters.
  
- **`session`**: Session data.

## Usage

1. Create an instance of `BulbaApp`.
2. Define your routes using `req()`.
3. Add middleware as needed using `use()`.
4. Set public folders using `setFreeFolders()`.

### Example

```php
$app = new BulbaApp();

// Define a route for the home page
$app->req('/', 'd', function($req, $res) {
    $res->send('Hello World!');
});

// Add session middleware
$app->use([], '*', $app->session_init());

// Set publicly accessible folders
$app->setFreeFolders(['public']);
