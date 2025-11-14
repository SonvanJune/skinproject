# 🎨 Skin Project – Digital File Selling Platform (Laravel)

Skin Project is a web application that allows users to browse, purchase, and download digital files such as themes, skins, plugins, or designs through a clean and user-friendly interface. It is built using the [Laravel](https://laravel.com/) PHP framework.

---

## 🚀 Features

- 🔐 User authentication (register/login)
- 🛒 Shopping cart & digital file purchases
- 📁 Upload and download file management
- 🗃️ Product and category management
- ✉️ Email sending functionality (e.g. notifications, receipts)
- 📬 Email configuration & management
- 🌐 Multi-language support (English, Vietnamese, etc.)
- 🛠️ Admin panel for managing users, files, categories, emails, and languages
- 💳 **PayPal** payment gateway integration (Optional)

---

## 🧰 Technologies Used

- PHP 8+
- Laravel 10+
- MySQL
- Bootstrap 5 / Tailwind CSS

---

## 🛠️ Installation

```bash
git clone https://gitlab.com/Szers222/skinproject.git
cd skinproject

# Install PHP and JS dependencies
composer install

# Set up environment
cp .env.example .env

# Configure database in .env, then run:
php artisan key:generate
php artisan migrate --seed

# Start the server
php artisan queue:work --sleep=3 --tries=3 --backoff=10
php artisan serve
```
---
## 📁 Project Structure Overview
app/           # Application logic (controllers, models, etc.)
routes/        # Route definitions (web & API)
resources/     # Blade templates, JS, CSS
database/      # Migrations & seeders
public/        # Public files (images, assets, index.php)

---
## 📄 License

This project is licensed under the **MIT License**.

### MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is provided to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## 🧑‍💻 Author

- **Nguyen Van Le Son**  
  - GitHub: [SonvanJune](https://github.com/SonvanJune)
  - Email: [sonthu3333@gmail.com]

- **Le Viet Khanh**  
  - Gitlab: [levietkhanh2k4](https://gitlab.com/levietkhanh2k4)
  - Email: [levietkhanh2k4@gmail.com]

- **Vu Minh Chuan**  
  - Gitlab: [NatswarChuan](https://gitlab.com/NatswarChuan)
