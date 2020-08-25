## Meet the team

First : Install virtual host on apache 

 1- create anew file meettheteam.dev in your vhosts files. 
 2- add this code to it.
  <VirtualHost *:80>
      ServerName meettheteam.dev
      ServerAlias www.meettheteam.dev
  
      DocumentRoot /var/www/meettheteam/public/
      <Directory /var/www/meettheteam/public/>
          AllowOverride All
          Order Allow,Deny
          Allow from All
          <IfModule mod_rewrite.c>
                                      Options -MultiViews
                                      RewriteEngine On
                                      RewriteCond %{REQUEST_FILENAME} !-f
                                      RewriteRule ^(.*)$ index.php [QSA,L]
                                  </IfModule>
      </Directory>
  
      # uncomment the following lines if you install assets as symlinks
      # or run into problems when compiling LESS/Sass/CoffeeScript assets
      # <Directory /var/www/meettheteam/>
      #     Options FollowSymlinks
      # </Directory>
  
      ErrorLog /var/log/apache2/meettheteam_error.log
      CustomLog /var/log/apache2/meettheteam_access.log combined
  </VirtualHost>

3- add vhost name in hosts file in /etc/hosts
  127.0.0.1   meettheteam.dev

4- restart apache using #service apache2 restart
 
Secound: Install Project ( You should have symfony installed)
1- extract project folder in /var/www
2- install required bundles 


Third : Install database
1- create database with config (you can change user&password as yours and config it on /config/packages/doctrine.yaml)
        dbname:   "meettheteam"
        user:     "root"
        password: "password"
2- run #php bin/console make:migration
3- run #php bin/console doctrine:migrations:migrate

# Usage
 1- Register new user use command:
    $ php bin/console register:user test@test.com testname
    
#Note : 
before use phpunit test please edit "otp" returned authenticateOtp() in BaseController 
to return custom one same like test files LoginTest&ColleagueTest






