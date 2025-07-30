#!/bin/bash
set -e

echo "🚀 Paleidžiama CakePHP aplikacija su automatinėmis migracijomis ir duomenų užpildymu..."

# Change to the application directory
cd /var/www/html

# Ensure the tmp directory exists and has proper permissions
mkdir -p tmp/cache/models tmp/cache/persistent tmp/cache/views tmp/sessions logs
chown -R www-data:www-data tmp logs

# Wait a moment to ensure everything is ready
sleep 2

echo "📊 Paleidžiamos duomenų bazės migracijos..."
# Run migrations
if bin/cake migrations migrate; then
    echo "✅ Migracijos sėkmingai užbaigtos"
else
    echo "❌ Migracijos nepavyko"
    exit 1
fi

echo "🌱 Paleidžiamas duomenų bazės užpildymas..."
# Run seeds (continue even if seeds fail, as they're not critical for basic functionality)
if bin/cake migrations seed; then
    echo "✅ Duomenų užpildymas sėkmingai užbaigtas"
else
    echo "⚠️  Duomenų užpildymas nepavyko, bet tęsiamas aplikacijos paleidimas"
    echo "   Pastaba: Galite paleisti duomenų užpildymą rankiniu būdu vėliau su: bin/cake migrations seed"
fi

echo "🎉 Duomenų bazės nustatymas užbaigtas! Paleidžiamas Apache..."

# Start Apache in the foreground
exec apache2-foreground