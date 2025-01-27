# PHP Library for Validating Telegram Mini App Users

This PHP library provides functionality for validating users of Telegram Mini Apps based on the `initData` string sent to the backend by the Telegram Web App. The validation process ensures the integrity of the received data using Telegram's guidelines.

## Installation

To install the library, use Composer:

```bash
composer require alexvkokin/telegram-mini-app-validation
```

## How It Works

The library performs the following steps:

1. **Parse and decode the `initData` string** received in the request.
2. **Validate the hash** using the bot token and the received data.
3. **Resolve the user data** into a structured `WebAppUser` object if the validation is successful.

## Code Example

### Backend Implementation

Here's how you can use the library in your backend application:

```php
<?php

declare(strict_types=1);

use Alexvkokin\TelegramMiniAppValidation\InitDataService;
use Alexvkokin\TelegramMiniAppValidation\WebAppUser;

$token = 'YourBotTokenHere'; // Replace with your bot token

// Fetch the initData string from the request header
$initData = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

try {
    // Create an InitDataService instance
    $initDataService = new InitDataService($token, $initData);

    // Validate the data
    if (!$initDataService->validate()) {
        throw new RuntimeException('Access Denied: User validation failed.');
    }

    // Resolve the user data
    $webAppUser = $initDataService->resolve();

    // Output the resolved user
    header('Content-Type: application/json');
    echo json_encode($webAppUser, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => $e->getMessage()]);
}
```

### Frontend Example

On the frontend, you can send a request to the backend as follows:

```javascript
const sendDataToBackend = async (initData) => {
    const response = await fetch('https://your-backend-url.com/endpoint', {
        method: 'POST',
        headers: {
            'Authorization': initData
        }
    });

    if (!response.ok) {
        console.error('Failed to validate user:', await response.json());
        return;
    }

    const userData = await response.json();
    console.log('Validated user data:', userData);
};

// Obtain initData from Telegram WebApp
const initData = window.Telegram.WebApp.initData;
sendDataToBackend(initData);
```

## Resources

- [Telegram Mini App Documentation](https://core.telegram.org/bots/webapps#initializing-mini-apps)
- [Validating Data Received via the Mini App](https://core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app)


