# crypto_api

![crypto_api Logo](public/logo.png)

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

````bash
php artisan migrate --seed

5. php artisan serve

 ```bash
    php artisan serve



## Features

- **User Management**: Create, update, and delete user accounts. Secure authentication and authorization.

- **Trading Functionality**: Implement trading features such as buying and selling of assets.

- **Assets Management**: Manage various types of assets, including stocks, cryptocurrencies, and commodities.

- **Deposit and Withdrawal**: Allow users to deposit funds into their accounts and withdraw funds through cryptocurrency or bank transfer.

- **KYC (Know Your Customer)**: Collect and manage user information, including social security numbers, for compliance and security.

- **Notifications**: Send notifications to users for important events or updates.


## API Endpoints

### Authentication

- `POST /api/auth/login`: Login users.
- `POST /api/auth/logout`: Logout users.
- `POST /api/auth/register`: Register a new user.
- `POST /api/auth/forgot-password`: Request a password reset link.
- `POST /api/auth/reset-password`: Reset user password.

### User Management:

- `GET /api/users`: Get all users.
- `GET /api/users/{id}`: Get a specific user.
- `POST /api/users`: Create a new user.
- `PUT /api/users/{id}`: Update a user.
- `DELETE /api/users/{id}`: Delete a user.

### Account:

- `GET /api/accounts`: Get all user accounts.
- `GET /api/accounts/{id}`: Get details of a specific account.
- `POST /api/accounts`: Create a new account.
- `PUT /api/accounts/{id}`: Update account details.
- `DELETE /api/accounts/{id}`: Close an account.

### Assets Management:

- `GET /api/assets`: Get all assets.
- `GET /api/assets/{id}`: Get details of a specific asset.
- `POST /api/assets`: Add a new asset.
- `PUT /api/assets/{id}`: Update asset information.
- `DELETE /api/assets/{id}`: Remove an asset.

### Deposits:

- `GET /api/deposits`: Get all deposit transactions.
- `GET /api/deposits/{id}`: Get details of a specific deposit.
- `POST /api/deposits`: Initiate a new deposit.
- `PUT /api/deposits/{id}`: Update deposit status.
- `DELETE /api/deposits/{id}`: Cancel a deposit.

### Withdrawals:

- `GET /api/withdrawals`: Get all withdrawal transactions.
- `GET /api/withdrawals/{id}`: Get details of a specific withdrawal.
- `POST /api/withdrawals`: Initiate a new withdrawal.
- `PUT /api/withdrawals/{id}`: Update withdrawal status.
- `DELETE /api/withdrawals/{id}`: Cancel a withdrawal.

### Notification:

- `GET /api/notifications`: Get all notifications.
- `GET /api/notifications/{id}`: Get details of a specific notification.
- `POST /api/notifications`: Send a new notification.
- `PUT /api/notifications/{id}`: Mark a notification as read.
- `DELETE /api/notifications/{id}`: Delete a notification.

### Debit Card:

- `GET /api/debit-cards`: Get all user debit cards.
- `GET /api/debit-cards/{id}`: Get details of a specific debit card.
- `POST /api/debit-cards`: Add a new debit card.
- `PUT /api/debit-cards/{id}`: Update debit card information.
- `DELETE /api/debit-cards/{id}`: Remove a debit card.

### Dashboard:

- *Define dashboard-related endpoints here.*

### KYC (Know Your Customer):

- `GET /api/kyc-info`: Get KYC information for the current user.
- `POST /api/kyc-info`: Submit new KYC information.
- `PUT /api/kyc-info`: Update existing KYC information.
- `DELETE /api/kyc-info`: Delete KYC information.

### Notifications:

- `GET /api/notifications`: Get all notifications.
- `GET /api/notifications/{id}`: Get details of a specific notification.
- `POST /api/notifications`: Send a new notification.
- `PUT /api/notifications/{id}`: Mark a notification as read.
- `DELETE /api/notifications/{id}`: Delete a notification.
````
