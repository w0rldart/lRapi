## Demo VHOST
This is just a demo of what a vhost should be to get started with lRapi. I use it, it works.

	<VirtualHost *:80>
        	DocumentRoot /var/www/app.com/web
        	ServerName app.com

        	ErrorLog /var/www/logs/app-error.log
        	LogLevel debug

        	<Directory "/var/www/app.com/web">
                	Options Indexes Includes FollowSymLinks MultiViews
                	AllowOverride all
                	Order allow,deny
                	Allow from all
        	</Directory>
	</VirtualHost>

## Starting documentation

The home page of the app contains some info I wrote about the app.
