
## User Management API

A Laravel 12 based REST API for managing users with multiple email addresses and sending welcome notifications. Built with Docker (Sail), PostgreSQL, and Mailpit.
## Requirements
- Docker & Docker Desktop
- Git

## Getting Started

1. Install dependencies:
   Using a temporary Docker container to run Composer:
```bash
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
2. Environment Setup:
```bash
   cp .env.example .env
```
3. Launch the application:
```bash
   ./vendor/bin/sail up -d
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate:fresh
```
4. Testing
```bash
  ./vendor/bin/sail test
```
