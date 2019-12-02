# PoC &amp; Unit Tests for SIHAM WebServices

## Requirements :
- composer
- php-soap


## Noob's guide :
- git clone https://github.com/UCA-Squad/siham-vhs.git
- composer update
- Modifier le vhost

```bash
    <VirtualHost *:80>
        ServerName votreNomDeDomaine.fr
        DocumentRoot "/var/www/siham-vhs/public"
        DirectoryIndex /index.php
        <Directory "/var/www/siham-vhs/public">
            AllowOverride None
            Order Allow,Deny
            Allow from All  
        	FallbackResource /index.php
        </Directory>
        ErrorLog /var/www/siham-vhs/var/log/siham-vhs_error.log
        CustomLog /var/www/siham-vhs/var/log/siham-vhs_access.log combined
    </VirtualHost>
```
- composer dump-env prod
> duplique le .env, modifier le fichier .env.local.php généré avec vos paramètres de connexion
- php bin/phpunit 
> à executer dans le répertoire de déploiement pour tester les services
