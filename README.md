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
> duplicate `.env` to `.env.local` and add your parameters,
> and to `.env.test.local` to add sync with a test instance

> - `php bin/phpunit` _(execute into folder to test all)_
> - `php bin/phpunit --testdox-html templates/tests/test.all.html > /dev/null` _(and create a render view)_

> `sass --no-source-map --style=compressed assets/scss/main.css public/css/main.min.css` _(if you need to update main style)_

>- `php bin/console sync:structure`
>- `php bin/console sync:agent --from-date=all` _(for a complete sync)_
>- `php bin/console sync:agent --from-date=YYYY-MM-DD` _(for the last updated, today by default)_
>- `php bin/console sync:agent --matricule=XXXXX` _(to update only one)_
>- `php bin/console sync:ldap`
>- `php bin/console sync:geisha`
> 
> for each command, add `--logger=file` to display to the webview