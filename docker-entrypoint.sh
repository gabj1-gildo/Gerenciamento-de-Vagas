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

# Rodar as migrations automaticamente (executa por padrão, a menos que RUN_MIGRATIONS seja 'false')
if [ "$RUN_MIGRATIONS" != "false" ]; then
    echo "Aguardando conexão com o banco de dados..."
    DB_READY=false
    for i in {1..30}; do
        if php artisan db:monitor --quiet; then
            echo "Banco de dados está pronto!"
            DB_READY=true
            break
        fi
        echo "Banco de dados indisponível, aguardando 2 segundos... ($i/30)"
        sleep 2
    done

    if [ "$DB_READY" = "true" ]; then
        echo "Rodando as migrations do banco de dados..."
        php artisan migrate --force
    else
        echo "Erro: Não foi possível conectar ao banco de dados. Abortando migrations."
        exit 1
    fi
fi

echo "Inicializando o servidor web (Apache)..."
exec apache2-foreground
