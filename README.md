# t412

Une [web-app simple mais efficace](https://mondedie.fr/viewtopic.php?id=8663) construite autour de l'API T411, avec quelques features sympa (téléchargement sur Seedbox, Syno (WIP), auto-téléchargement des séries, etc.).

## Installation

Clônez le dépot dans votre répertoire `www/`.  
`git clone https://github.com/matthiasbosc/t412`


Téléchargez les composants nécessaires:  
```bash
cd t412
bin/composer update
```

Configurez votre vhost (voir [ci-dessous](#vhost-nginx)).  
Rendez-vous à l'adresse où pointe votre domaine (`domain.tld/setup.php`), vérifier que les extensions nécessaires soient chargées et le cas échéant récupérez votre clé de sécurité.  

Éditez le fichier `t412.class.php` et complétez les champs suivants:

```php
const DB_HOST = 'localhost';
const DB_NAME = '';
const DB_USER = '';
const DB_PASS = '';
/** clé de sécurité */
const KEY = "";
/** préfixe pour DL Syno -- WIP */
const DL_PREFIX = 'https://dl.domain.tld/';
/** nom de domaine, sans http(s) */
public $domainName = 'domain.tld';
/** utilisateur t411 - nécessaire pour lancer les requêtes cron */
CONST T411USER = 'jeanneige';
```

Retournez à l'adresse où pointe votre domaine (`domain.tld/setup.php`) et vérifier que tous les tests renvoient "Ok".  
Il ne vous reste plus qu'à ajouter vos tâches cron pour récupérer les tops et (facultatif) le téléchargement automatique.
```bash
0 * * * * /usr/bin/php /votre/repertoire/www/cli/top.php
0 * * * * /usr/bin/php /votre/repertoire/www/cli/autodownload.php
```

# Vhost nginx
```
server {
    listen 80;
    listen [::]:80;
    server_name domain.tld;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name domain.tld;


    root /var/www/chemin/voulu;
    index index.html index.php;

    include ssl/.conf;
    location / {
        # First attempt to serve request as file, then
        # as directory, then fall back to displaying a 404.
        try_files $uri $uri/ =404;
        rewrite ^/details$|details/$|details/(.*)$ /details.php?id=$1? last;
        rewrite ^/nfo$|nfo/$|nfo/(.*)$ /nfo.php?id=$1? last;
        rewrite ^/download$|download/$|download/([0-9]*)$ /download.php?id=$1? last;
        rewrite ^/download/([0-9]*)/$ /download.php?id=$1? last;
        rewrite ^/dl$|dl/$|dl/(.*)$ /dl.php?id=$1? last;
        rewrite ^/delete$|delete/$|delete/(.*)$ /delete.php?id=$1? last;
        rewrite ^/login$|login/$ /login.php last;
        rewrite ^/logout$|logout/$ /logout.php last;
        rewrite ^/downloads$|downloads/$ /downloads.php last;
        rewrite ^/seedbox$|seedbox/$ /seedbox.php last;
        rewrite ^/suivi$|suivi/$ /suivi.php last;
        rewrite ^/top/today$|top/today/$ /top/today.php last;
        rewrite ^/top/week$|top/week/$ /top/week.php last;
        rewrite ^/top/month$|top/month/$ /top/month.php last;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
    }

    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml|html)$ {
        expires 90d;
        access_log off;
        log_not_found off;
        add_header Cache-Control "public";
    }

}
```
