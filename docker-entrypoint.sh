#!/bin/bash
set -e

echo "Starting Docker Entrypoint..."

# Ajustar permissões essenciais do Laravel
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Limpar e refazer cache de configuração (recomendado em produção)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rodar as migrations automaticamente (apenas se a conexão com banco estiver configurada)
if [ "$RUN_MIGRATIONS" == "true" ]; then
    echo "Rodando as migrations do banco de dados..."
    php artisan migrate --force
fi

echo "Inicializando o servidor web (Apache)..."
exec apache2-foreground
