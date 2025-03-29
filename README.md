# Whatsapp Cloud API Client

An open-source Whatsapp Cloud API client for PHP that simplifies webhook handling and event dispatching.

---

## Features

- Parses and validates webhook payloads.
- Verifies webhook challenges through Facebook's Hub Verification token.

---

## Installation

You can install the package using `composer`:

```bash
composer require softlivery/whatsapp-cloud-api-client
```

---

## Webhook Handling Guide

When integrating the Whatsapp Cloud API webhook, there are typically two operations to handle:

1. Handling Facebook's **Webhook Verification**.
2. Parsing and validating webhook events received on the endpoint.

This library provides the `WebhookEventHelper` to aid with these tasks.

---

## Using the `WebhookEventHelper`

The `WebhookEventHelper` provides the following key methods:

- **`isHubVerifyTokenValid(string $hubVerifyToken): bool`:** Validates the verification token sent by Facebook during
  webhook setup.
- **`validateAndParse(string $payload, array $serverHeaders): array`:** Parses and validates incoming webhook payloads.

---

### Example of Webhook Usage

The following example demonstrates how to build a webhook handler using `WebhookEventHelper`. The solution includes
lines for releasing the client connection before continuing request processing, allowing long-running operations to
continue efficiently.

```php
<?php

require 'vendor/autoload.php';

use Softlivery\WhatsappCloudApiClient\Webhook\WebhookEventHelper;
use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Softlivery\WhatsappCloudApiClient\Events\WebhookEvent;
use Softlivery\WhatsappCloudApiClient\Exception\InvalidSignatureException;
use Softlivery\WhatsappCloudApiClient\Exception\InvalidVerificationTokenException;

// Assuming $logger and $dispatcher are already initialized (PSR-compliant implementations):
$logger = new YourLoggerImplementation();
$dispatcher = new YourEventDispatcherImplementation();

// Initialize the WebhookEventHelper
$helper = new WebhookEventHelper('your_verify_token', 'your_app_secret');

try {
    if (array_key_exists(WebhookEventHelper::HUB_VERIFY_TOKEN_KEY, $_GET)) {
        // Handle Webhook Verification
        $hubVerifyToken = $_GET[WebhookEventHelper::HUB_VERIFY_TOKEN_KEY];
        
        if ($helper->isHubVerifyTokenValid($hubVerifyToken)) {
            echo $_GET['hub_challenge'];
        } else {
            throw new InvalidVerificationTokenException('Invalid Hub Verify Token');
        }

        exit;
    }

    // Ensure the script continues processing even if the client disconnects
    ignore_user_abort(true);
    ob_start();

    // Handle Incoming Webhook Events
    $payload = file_get_contents('php://input'); // Retrieve webhook payload
    $event = $helper->validateAndParse($payload, $_SERVER); // Parse & validate payload

    // Send a response to the client and close the connection
    header("Connection: close");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();

    // Process the event after releasing the client connection
    $dispatcher->dispatch(new WebhookEvent($event));
} catch (InvalidVerificationTokenException $e) {
    $logger->error('Verification failed: ' . $e->getMessage());
} catch (InvalidSignatureException $e) {
    $logger->error('Security signature check failed: ' . $e->getMessage());
} catch (Exception $e) {
    $logger->error('An unexpected error occurred: ' . $e->getMessage());
}
```

---

### Explanation of the Code

1. **Releasing the Client Connection:**
    - The `ignore_user_abort(true)` ensures that the script execution will continue even if the client disconnects
      during processing.
    - The `ob_start()` enables output buffering, while the `header`, `ob_end_flush()`, and `flush()` commands ensure
      that the response is sent to the client as early as possible, effectively closing the connection from the client's
      perspective.

2. **Continue Processing After Releasing the Client:**
    - After the client connection is closed, the server continues processing the webhook event in the same thread.
    - This is particularly useful when handling long-running tasks within the same request thread.

3. **Webhook Verification and Event Parsing:**
    - The verification process ensures secure communication between Facebook and your server during webhook setup.
    - The payload is safely parsed and processed into an event object, which can then be dispatched for further
      handling.

---

### Testing the Webhook Handler

You can test your webhook endpoint with tools like [ngrok](https://ngrok.com/) to expose a local development environment
to a public URL. Once exposed, set up the webhook in the Facebook Developer Console and monitor the events.

---

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for more details.

---

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.
