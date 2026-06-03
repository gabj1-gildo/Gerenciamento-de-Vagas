#!/bin/bash
set -e

echo "Starting Docker Entrypoint..."

# Ajustar porta do Apache se a variável PORT estiver definida (necessário para o Render)
if [ -n "$PORT" ]; then
    echo "Configurando Apache para escutar na porta $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/*.conf
fi

# Ajustar permissões essenciais do Laravel
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Limpar e refazer cache de configuração (recomendado em produção)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rodar as migrations automaticamente (apenas se a conexão com banco estiver configurada)
if [ "$RUN_MIGRATIONS" == "true" ]; then
    echo "Aguardando conexão com o banco de dados..."
    for i in {1..30}; do
        if php artisan db:monitor --quiet; then
            echo "Banco de dados está pronto!"
            break
        fi
        echo "Banco de dados indisponível, aguardando 2 segundos... ($i/30)"
        sleep 2
    done

    echo "Rodando as migrations do banco de dados..."
    php artisan migrate --force
fi

echo "Inicializando o servidor web (Apache)..."
exec apache2-foreground
