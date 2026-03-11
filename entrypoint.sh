#!/bin/sh

echo "Iniciando aplicação..."

# Esperar banco subir
sleep 5

echo "Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Rodando migrations..."
php artisan migrate:fresh --seed --force

echo "Cacheando configurações..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "Criando storage link..."
php artisan storage:link || true

# Instalar dependências do Node e compilar assets
echo "Instalando dependências Node..."
npm install

# Rodar Vite em dev mode (hot reload)
echo "Iniciando Vite (npm run dev) em background..."
npm run dev -- --host 0.0.0.0 &

echo "Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=$PORT