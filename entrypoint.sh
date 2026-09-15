#!/bin/bash

set -e

echo "⏳ Aguardando o banco de dados ficar disponível..."

until mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
  echo "Banco de dados ainda não está pronto. Aguardando..."
  sleep 2
done

echo "✅ Banco de dados está disponível. Continuando..."

MIGRATIONS_COUNT=$(mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -D"$DB_DATABASE" -se "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_DATABASE' AND table_name = 'migrations';")

if [ "$MIGRATIONS_COUNT" -eq 0 ]; then
  echo "📦 Tabela de migrations não existe. Rodando migrate e seed..."
  php artisan migrate --force
  php artisan db:seed --force
elif [ "$(mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -D"$DB_DATABASE" -se "SELECT COUNT(*) FROM migrations;")" -eq 0 ]; then
  echo "📦 Migrations ainda não aplicadas. Rodando migrate e seed..."
  php artisan migrate --force
  php artisan db:seed --force
else
  echo "✔️ Migrations já foram aplicadas anteriormente."
fi

echo "🔗 Criando link simbólico do storage..."
if [ -L public/storage ] || [ -d public/storage ]; then
  echo "🔄 Storage já existe. Removendo para recriar..."
  rm -rf public/storage
fi
php artisan storage:link

echo "⚙️ Gerando caches do Laravel..."
php artisan config:cache
php artisan route:cache

echo "🚀 Iniciando o Apache..."
exec apache2-foreground
