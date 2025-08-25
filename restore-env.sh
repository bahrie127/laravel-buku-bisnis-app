#!/bin/bash

# Script untuk restore environment asli

echo "🔄 Restoring original environment..."

if [ -f ".env.backup" ]; then
    cp .env.backup .env
    rm .env.backup
    echo "✅ Original .env restored"

    # Clear cache
    php artisan config:clear
    php artisan cache:clear

    echo "✅ Cache cleared"
    echo ""
    echo "🎯 Environment restored to original state"
    echo "   Database: bookkeeping-db (original data)"
else
    echo "❌ No backup file found"
    echo "   Please manually restore your .env file"
fi
