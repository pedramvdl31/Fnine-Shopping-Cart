**Server Setup & Deployment on Ubuntu 24 (AWS)**
================================================

This guide provides step-by-step instructions to install and configure a Laravel 11 project on an **Ubuntu 24 AWS instance** with **Apache, MySQL, PHP 8.2, and SSL (Let's Encrypt).**

**1\. Server Setup & Package Installation**
-------------------------------------------

Update the system and install necessary packages:

sudo apt update && sudo apt upgrade -y

sudo apt install zip unzip software-properties-common curl -y

Add PHP repository and install **PHP 8.2** with required extensions:

sudo add-apt-repository ppa:ondrej/php -y

sudo apt update

sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip php8.2-curl php8.2-bcmath php8.2-intl php8.2-readline php8.2-mysql

Install **Apache and enable mod\_rewrite**:

sudo apt install apache2 libapache2-mod-php8.2 -y

sudo a2enmod rewrite

sudo systemctl restart apache2

**2\. SSL Setup with Let's Encrypt (Certbot)**
----------------------------------------------

Install Certbot and generate an SSL certificate for your domain:

sudo apt install certbot python3-certbot-apache -y

sudo certbot --apache -d fnine.webprinciples.com

Test renewal:

sudo certbot renew --dry-run

**3\. Configure Apache Virtual Host**
-------------------------------------

Edit the **Apache configuration file**:

sudo nano /etc/apache2/sites-available/fnine.conf

Replace the contents with:

    ServerAdmin webmaster@fnine.webprinciples.com

    ServerName fnine.webprinciples.com

    DocumentRoot /var/www/Fnine-Shopping-Cart/public

        AllowOverride All

        Require all granted

    ErrorLog ${APACHE\_LOG\_DIR}/error.log

    CustomLog ${APACHE\_LOG\_DIR}/access.log combined

    SSLCertificateFile /etc/letsencrypt/live/fnine.webprinciples.com/fullchain.pem

    SSLCertificateKeyFile /etc/letsencrypt/live/fnine.webprinciples.com/privkey.pem

    Include /etc/letsencrypt/options-ssl-apache.conf

Enable the configuration and restart Apache:

sudo a2ensite fnine.conf

sudo systemctl restart apache2

**4\. MySQL Database Setup**
----------------------------

Install MySQL:

sudo apt install mysql-server -y

Secure MySQL installation:

sudo mysql\_secure\_installation

Log in to MySQL as root:

sudo mysql -u root -p

Create a new database and user:

CREATE DATABASE fnine;

GRANT ALL PRIVILEGES ON fnine.\* TO 'laravel\_user'@'localhost' IDENTIFIED BY 'your\_secure\_password';

FLUSH PRIVILEGES;

EXIT;

**5\. Laravel Installation & Configuration**
--------------------------------------------

Navigate to the web directory and clone your Laravel project:

cd /var/www/

git clone https://github.com/your-repo/Fnine-Shopping-Cart.git

cd Fnine-Shopping-Cart

Install dependencies:

composer install --no-dev --optimize-autoloader

Set the correct permissions:

sudo chown -R www-data:www-data /var/www/Fnine-Shopping-Cart

sudo chmod -R 775 /var/www/Fnine-Shopping-Cart/storage /var/www/Fnine-Shopping-Cart/bootstrap/cache

Set up the .env file:

cp .env.example .env

sudo nano .env

Update the following database settings:

DB\_DATABASE=fnine

DB\_USERNAME=laravel\_user

DB\_PASSWORD=your\_secure\_password

Generate the application key:

php artisan key:generate

Run migrations:

php artisan migrate

Clear and cache configurations:

php artisan config:clear

php artisan cache:clear

php artisan config:cache

php artisan route:clear

php artisan view:clear

Restart Apache:

sudo systemctl restart apache2

**6\. Testing & Troubleshooting**
---------------------------------

### **Check Laravel logs:**

sudo tail -f /var/www/Fnine-Shopping-Cart/storage/logs/laravel.log

### **Check Apache logs:**

sudo tail -f /var/log/apache2/error.log

### **Manually test MySQL connection:**

mysql -u laravel\_user -p

If everything is working, your Laravel 11 application should now be live at:

https://fnine.webprinciples.com

**7\. Enable Automatic SSL Renewal (Optional)**
-----------------------------------------------

To ensure SSL certificates renew automatically, add a cron job:

sudo crontab -e

Add this line at the bottom:

0 0 1 \* \* certbot renew --quiet

Save and exit.

**Final Notes**
---------------

*   Ensure that .env does not contain sensitive credentials in public repositories.
    
*   Always restart Apache after making changes to configurations.
    
*   Monitor logs frequently to detect and fix errors promptly.