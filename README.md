# crypto_api

![crypto_api Logo](public/defaultLogo.svg)

## About

`crypto_api` is a backend API for a cryptocurrency trading platform built using the Laravel framework. It provides a robust backend infrastructure to support trading functionalities, user accounts, assets, deposits, withdrawals, and more.

### Setup

1. Clone the repository:

    ```bash
     git clone https://github.com/striker1101/crypto_api.git

    ```

2. Install dependencies:

    ```bash
     cd crypto_api
     composer install
    ```

3. Configure environment variables:
   Update `.env` with your database credentials, API keys, and other configuration settings.

    ```bash
     cp .env.example .env
    ```

4.Run migrations and seed the database:

    ```bash
     php artisan migrate --seed
    ```

5. php artisan serve

    ```bash
     php artisan serve
    ```

## Features

-   **User Management**: Create, update, and delete user accounts. Secure authentication and authorization.

-   **Trading Functionality**: Implement trading features such as buying and selling of assets.

-   **Assets Management**: Manage various types of assets, including stocks, cryptocurrencies, and commodities.

-   **Deposit and Withdrawal**: Allow users to deposit funds into their accounts and withdraw funds through cryptocurrency or bank transfer.

-   **KYC (Know Your Customer)**: Collect and manage user information, including social security numbers, for compliance and security.

-   **Notifications**: Send notifications to users for important events or updates.

## API Endpoints

### Authentication

-   `POST /api/auth/login`: Login users.
-   `POST /api/logout`: Logout users.
-   `POST /api/auth/register`: Register a new user.
-   `POST /api/sendPasswordResetLink`: send email to user to reset password.
-   `POST /api/resetPassword`: Reset user password.
-   `GET /api/user-profile`: see user profile.
-   `GET /api/sendEmailVerificationLink`: send email for verification.
-   `POST /api/verifyEmail`: verify user email
-   `POST /api/sendEmail`: send user email

### User Management:

-   `GET /api/auth/user`: Get all users.
-   `GET /api/auth/user/{id}`: Get a specific user.
-   `POST /api/auth/user`: Create a new user.
-   `PUT /api/auth/user/{id}`: Update a user.
-   `DELETE /api/auth/user/{id}`: Delete a user.

### Account:

-   `GET /api/account`: Get all user accounts.
-   `GET /api/account/{id}`: Get details of a specific account.
-   `POST /api/account`: Create a new account.
-   `PUT /api/account/{id}`: Update account details.
-   `DELETE /api/account/{id}`: Close an account.

### Assets Management:

-   `GET /api/asset`: Get all assets.
-   `GET /api/asset/{id}`: Get details of a specific asset.
-   `POST /api/asset`: Add a new asset.
-   `PUT /api/asset/{id}`: Update asset information.
-   `DELETE /api/asset/{id}`: Remove an asset.

### Deposits:

-   `GET /api/deposit`: Get all deposit transactions.
-   `GET /api/deposit/{id}`: Get details of a specific deposit.
-   `POST /api/deposit`: Initiate a new deposit.
-   `PUT /api/deposit/{id}`: Update deposit status.
-   `DELETE /api/deposit/{id}`: Cancel a deposit.

### Withdrawals:

-   `GET /api/withdrawal`: Get all withdrawal transactions.
-   `GET /api/withdrawal/{id}`: Get details of a specific withdrawal.
-   `POST /api/withdrawal`: Initiate a new withdrawal.
-   `PUT /api/withdrawal/{id}`: Update withdrawal status.
-   `DELETE /api/withdrawal/{id}`: Cancel a withdrawal.

### Debit Card:

-   `GET /api/debit-card`: Get all user debit cards.
-   `GET /api/debit-card/{id}`: Get details of a specific debit card.
-   `POST /api/debit-card`: Add a new debit card.
-   `PUT /api/debit-card/{id}`: Update debit card information.
-   `DELETE /api/debit-card/{id}`: Remove a debit card.

### KYC (Know Your Customer):

-   `GET /api/kyc-info`: Get KYC information for the current user.
-   `POST /api/kyc-info`: Submit new KYC information.
-   `PUT /api/kyc-info`: Update existing KYC information.
-   `DELETE /api/kyc-info`: Delete KYC information.

### Notifications:

-   `GET /api/notification`: Get all notifications.
-   `GET /api/notification/{id}`: Get details of a specific notification.
-   `POST /api/notification`: Send a new notification.
-   `PUT /api/notification/{id}`: Mark a notification as read.
-   `DELETE /api/notification/{id}`: Delete a notification.

### Dashboard:

-   _Define dashboard-related endpoints here._


# Crypto_app Project Deployment Guide

## 1. Configure Environment Variables:

Ensure that your `.env` file is configured properly for production. Update database credentials, set `APP_ENV` to `production`, and configure other necessary settings.

```bash
cp .env.example .env
```

## 2. Generate Application Key:
Generate a new application key for your production environment.

   ```bash
     php artisan key:generate --env=production
   ```

## 3. Optimize Autoloader:
Optimize the Composer autoloader to improve performance.

  ```bash
     composer install --optimize-autoloader --no-dev
   ```
## 4. Migrate Database:
Run database migrations and seed the production database.

   ```bash
     php artisan migrate --seed --env=production
   ```
## 5. Configure Caching:
Cache configuration and routes for better performance.

   ```bash
     php artisan config:cache
     php artisan route:cache
   ```

## 6. Set Permissions:
Ensure proper file and folder permissions for security.

  ```bash
     chmod -R 755 storage bootstrap/cache
  ```
## 7. Configure Web Server:
Configure your web server (e.g., Nginx or Apache) to point to the public directory of your Laravel project.

## Example for Nginx:

  ```bash
     # Nginx configuration file
     # /etc/nginx/sites-available/your-domain

     server {
     listen 80;
     server_name your-domain.com;
     root /path/to/your/laravel/public;

     index index.php index.html index.htm;

     location / {
        try_files $uri $uri/ /index.php?$query_string;
     }

     location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
     }
  
     error_log /var/log/nginx/your-domain_error.log;
     access_log /var/log/nginx/your-domain_access.log;
     }
  ```
## 8. Configure HTTPS:
If not already configured, consider setting up HTTPS for secure communication.

## 9. Enable Queue Worker (Optional):
If your application uses queues, configure a queue worker for background processing.

  ```bash
     php artisan queue:work --env=production --daemon
   ```

## 10. Monitor Logs:
Check your server logs for any errors or issues.

## 11. Backup:
Before deploying to production, ensure you have a backup of your database and important files.

## 12. Monitoring and Scaling:
Consider setting up monitoring tools and scaling options based on your production requirements.

## 13. Update DNS Records:
If applicable, update your DNS records to point to the production server.

## 14. Test:
Perform thorough testing on the production environment to ensure everything is working as expected.

## 15. Documentation:
Update your project's documentation with production-specific information, such as server details and configurations.

## 16. Security:
Implement additional security measures, such as setting up firewalls, securing sensitive data, and monitoring for security threats.

These steps provide a general guideline, and you may need to adapt them based on your specific hosting environment and requirements. Always refer to the documentation of your web server and hosting provider for specific details.

   ``
     You can save this content in a file with a `.md` extension, such as `deployment_guide.md`. Feel free to customize it further based on your project's specific details and requirements.
   ``




