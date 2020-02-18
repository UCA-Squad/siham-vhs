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
- php bin/phpunit --testdox-html templates/tests/test.all.html > /dev/null
> à mettre en cron et rendre disponible à la racine de l'application
- sass --no-source-map --style=compressed assets/scss/main.css public/css/main.min.css
> if you need to update main style

- php bin/console sync:structure --logger=file _//console by default_
- php bin/console sync:agent_--logger=file --from-date=_(all/date("Y-m-d"))_
- php bin/console sync:ldap --logger=file
- php bin/console sync:geisha --logger=file