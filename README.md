# PHPMantra
What is the PHP MVC framework?
PHP MVC is an application design pattern that separates the application data and business logic (Model) from the presentation (View). 
MVC stands for Model, View and Controller.
The controller interacts between the models and views.
PHPMantra is a PHP framework for web development.
# Installation of PHPMantra in Linux OS
sudo mkdir -p /var/www/phpmantra.com/html
sudo chown -R $USER:$USER /var/www/phpmantra.com/html/
sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/phpmantra.conf
sudo nano /etc/apache2/sites-available/phpmantra.conf
# Edit
<VirtualHost *:80>
        ServerName phpmantra.com
        ServerAlias www.phpmantra.com
        ServerAdmin admin@phpmantra.com
        DocumentRoot /var/www/phpmantra.com/html
        <Directory /var/www/phpmantra.com/html>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Require all granted
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
sudo a2ensite phpmantra.conf 
sudo systemctl restart apache2
sudo nano /etc/hosts
# Edit
127.0.0.1       localhost
127.0.0.1       www.phpmantra.com
cd /var/www/phpmantra.com/html/
git clone git@github.com:prakashjoshi2707/PHPMantra.git
cd PHPMantra/
cp -r * /var/www/phpmantra.com/html/
cd ..
sudo rm -r PHPMantra/
git remote -v
git remote add origin git@github.com:prakashjoshi2707/PHPMantra.git
git remote -v
git push origin master

