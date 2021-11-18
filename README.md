# PHPMantra
What is the PHP MVC framework?
PHP MVC is an application design pattern that separates the application data and business logic (Model) from the presentation (View). 
MVC stands for Model, View and Controller.
The controller interacts between the models and views.
PHPMantra is a PHP framework for web development.
# Installation of PHPMantra in Linux OS
sudo mkdir -p /var/www/phpmantra.com/html <br>
sudo chown -R $USER:$USER /var/www/phpmantra.com/html/<br>
sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/phpmantra.conf<br>
sudo nano /etc/apache2/sites-available/phpmantra.conf<br>
# Edit
<VirtualHost *:80><br>
        ServerName phpmantra.com<br>
        ServerAlias www.phpmantra.com<br>
        ServerAdmin admin@phpmantra.com<br>
        DocumentRoot /var/www/phpmantra.com/html<br>
        <Directory /var/www/phpmantra.com/html><br>
            Options Indexes FollowSymLinks MultiViews<br>
            AllowOverride All<br>
            Require all granted<br>
        </Directory><br>
        ErrorLog ${APACHE_LOG_DIR}/error.log<br>
        CustomLog ${APACHE_LOG_DIR}/access.log combined<br>
</VirtualHost><br>
sudo a2ensite phpmantra.conf <br>
sudo systemctl restart apache2<br>
sudo nano /etc/hosts<br>
# Edit<br>
127.0.0.1       localhost<br>
127.0.0.1       www.phpmantra.com<br>
cd /var/www/phpmantra.com/html/<br>
git clone git@github.com:prakashjoshi2707/PHPMantra.git<br>
cd PHPMantra/<br>
cp -r * /var/www/phpmantra.com/html/<br>
cd ..<br>
sudo rm -r PHPMantra/<br>
git remote -v<br>
git remote add origin git@github.com:prakashjoshi2707/PHPMantra.git<br>
git remote -v<br>
git push origin master<br>

