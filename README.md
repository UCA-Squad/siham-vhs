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
- Attention aux droits d'accès (SELinux) sur les répertoires:
  - `templates/sync`
  - `templates/test`
  - `var/ldap`

### Parameters
> duplicate `.env` to `.env.local` and add your parameters,
> and to `.env.test.local` to add sync with a test instance
---
### Tests
> - `php bin/phpunit` _(execute into folder to test all)_
> - `php bin/phpunit --testdox-html templates/tests/test.all.html > /dev/null` _(and create a render view)_
---
### Commands
>- `php bin/console sync:structure`
>- `php bin/console sync:agent --from-date=all` _(for a complete sync)_
>- `php bin/console sync:agent --from-date=YYYY-MM-DD` _(for the last updated, today by default)_
>- `php bin/console sync:agent --matricule=XXXXX` _(to update only one)_
>- `php bin/console sync:ldap` _(need generated file by LDAP and store to `var/ldap`)_
>- `php bin/console sync:geisha` _(data from GEISHA)_
> 
> for each command, add `--logger=file` to display to the webview
>
>- `php bin/console check:anomaly` _(some controls send to hr department by email)_
---
### Examples _with our crontab_
```bash
### PROD 

# Tests to prevent server down
00 7,12,17 * * mon,tue,wed,thu,fri apache cd /var/www/html/siham-vhs && php bin/phpunit --configuration=phpunit.prod.xml --testdox-html templates/test/prod.phpunit.html > /dev/null 2>&1

# Command list
15 19 * * mon,tue,wed,thu,fri apache cd /var/www/html/siham-vhs && php bin/console --env=prod sync:structure --logger=file > /dev/null 2>&1
30 19 * * mon,tue,wed,thu,fri apache cd /var/www/html/siham-vhs && php bin/console --env=prod sync:agent     --logger=file > /dev/null 2>&1
30 20 * * mon,tue,wed,thu,fri,sat apache cd /var/www/html/siham-vhs && php bin/console --env=prod sync:geisha    --logger=file > /dev/null 2>&1
30 21 * * mon,tue,wed,thu,fri,sat apache cd /var/www/html/siham-vhs && php bin/console --env=prod sync:ldap      --logger=file > /dev/null 2>&1
# Get all agents once by week, the saturday
00 08 * * sat apache cd /var/www/html/siham-vhs && php bin/console --env=prod sync:agent     --logger=file --from-date=all > /dev/null 2>&1
# Check anomaly
00 06 * * mon,tue,wed,thu,fri apache cd /var/www/html/siham-vhs && php bin/console --env=prod check:anomaly > /dev/null 2>&1

### TEST/PREPROD

# Tests to prevent server down
30 07,12,17 * * mon,tue,wed,thu,fri apache cd /var/www/html/siham-vhs && php bin/phpunit --configuration=phpunit.test.xml --testdox-html templates/test/test.phpunit.html > /dev/null 2>&1

# Command list, launch once by week (the sunday)
00 08 * * sun apache cd /var/www/html/siham-vhs && php bin/console --env=test sync:structure --logger=file > /dev/null 2>&1
30 08 * * sun apache cd /var/www/html/siham-vhs && php bin/console --env=test sync:agent     --logger=file --from-date=all > /dev/null 2>&1
30 11 * * sun apache cd /var/www/html/siham-vhs && php bin/console --env=test sync:geisha    --logger=file > /dev/null 2>&1
00 12 * * sun apache cd /var/www/html/siham-vhs && php bin/console --env=test sync:ldap      --logger=file > /dev/null 2>&1
```
---
### Style
> `sass --no-source-map --style=compressed assets/scss/main.css public/css/main.min.css` _(if you need to update main style)_