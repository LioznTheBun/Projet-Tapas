./vendor/bin/openapi src/controllers -o doc/openapi.yaml
php -S 127.0.0.1:3000
http://localhost:3000/doc/#/