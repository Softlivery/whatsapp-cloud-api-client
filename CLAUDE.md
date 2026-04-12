# WhatsApp Cloud API Client — Claude Guide

## Project Overview

PHP 8.1+ library wrapping the Meta WhatsApp Cloud API. Provides typed clients for messages, templates, media, webhooks, and WABA management. Used by `contact-center-platform` as its sole transport layer to Meta.

## Key Commands

```bash
composer test          # phpunit (no coverage)
composer lint          # php -l on all src/ and tests/
```

## Architecture

```
src/
  ApiClient.php            # entry point — wraps all clients
  WhatsappCloudClient.php  # low-level HTTP client
  Client/
    MessagesClient.php     # send messages
    TemplatesClient.php    # CRUD + list templates
    MediaClient.php        # upload/download media
    WebhooksClient.php     # webhook verification helpers
    WabaClient.php         # WABA account queries
  Dto/Webhook/             # typed DTOs for incoming webhook payloads
  Exception/               # GraphApiException, ApiResponseException
  PayloadMapper.php        # reflection-based array → DTO hydration
  Webhook/WebhookEventHelper.php  # signature validation + parse
```

## Conventions

- All API responses return typed response objects; callers use `->json()` or `->getData()`
- `GraphApiException` carries `getGraphCode()`, `getGraphSubcode()`, `getPayload()` — always check these, not just the HTTP status
- `PayloadMapper` hydrates DTOs via reflection — add properties to DTO classes to capture new webhook fields
- No framework dependency — pure PHP, PSR-compliant
