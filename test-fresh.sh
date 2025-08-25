#!/bin/bash

# Script untuk testing dengan database MySQL terpisah
# Menggunakan database dengan suffix _test

echo "🚀 Starting Laravel Testing with MySQL..."
echo "📁 Database: bookkeeping-db_test"
echo ""

# Copy .env.testing_mysql ke .env untuk sementara
echo "📝 Setting up testing environment..."
cp .env .env.backup
cp .env.testing_mysql .env

echo "✅ Environment file updated"
echo ""

# Buat database jika belum ada
echo "🗄️  Creating database if not exists..."
mysql -u root -ppwdpwd8 -e "CREATE DATABASE IF NOT EXISTS \`bookkeeping-db_test\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Database 'bookkeeping-db_test' ready"
else
    echo "❌ Failed to create database. Please check MySQL credentials."
    # Restore original .env
    cp .env.backup .env
    rm .env.backup
    exit 1
fi

echo ""

# Clear cache
echo "🧹 Clearing application cache..."
php artisan config:clear
php artisan cache:clear

# Run migrations fresh with seeding
echo "🔄 Running migrate:fresh --seed..."
php artisan migrate:fresh --seed

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database migrated and seeded successfully!"
    echo ""
    echo "📊 Database Info:"
    echo "   - Database: bookkeeping-db_test"
    echo "   - Connection: mysql"
    echo "   - Host: 127.0.0.1:3306"
    echo ""
    echo "🎯 You can now test your application with fresh data!"
    echo "   Original data in 'bookkeeping-db' is untouched."
    echo ""
    echo "💡 To restore original environment, run:"
    echo "   cp .env.backup .env && php artisan config:clear"
else
    echo "❌ Migration failed!"
fi
