<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TestFreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:fresh {--restore : Restore original environment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate:fresh --seed on testing database (bookkeeping-db_test)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('restore')) {
            return $this->restoreEnvironment();
        }

        return $this->runTestFresh();
    }

    private function runTestFresh(): int
    {
        $this->info('🚀 Starting Laravel Testing with MySQL...');
        $this->info('📁 Database: bookkeeping-db_test');
        $this->newLine();

        // Backup current env
        if (file_exists('.env')) {
            copy('.env', '.env.backup');
            $this->info('📝 Current .env backed up');
        }

        // Switch to testing environment
        if (file_exists('.env.testing_mysql')) {
            copy('.env.testing_mysql', '.env');
            $this->info('✅ Switched to testing environment');
        } else {
            $this->error('❌ .env.testing_mysql not found!');
            return 1;
        }

        $this->newLine();

        // Create database if not exists
        $this->info('🗄️  Creating database if not exists...');

        try {
            $originalDbName = config('database.connections.mysql.database');
            $testDbName = $originalDbName . '_test';

            // Connect without database to create it
            Config::set('database.connections.mysql.database', '');
            DB::purge('mysql');

            DB::connection('mysql')->statement("CREATE DATABASE IF NOT EXISTS `{$testDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("✅ Database '{$testDbName}' ready");

            // Restore database name in config
            Config::set('database.connections.mysql.database', $testDbName);
            DB::purge('mysql');
        } catch (\Exception $e) {
            $this->error('❌ Failed to create database: ' . $e->getMessage());
            $this->restoreEnvFile();
            return 1;
        }

        $this->newLine();

        // Clear config cache only
        $this->info('🧹 Clearing configuration cache...');
        $this->call('config:clear');

        // Run migrations fresh with seeding
        $this->info('🔄 Running migrate:fresh --seed...');

        $exitCode = $this->call('migrate:fresh', ['--seed' => true]);

        if ($exitCode === 0) {
            $this->newLine();
            $this->info('✅ Database migrated and seeded successfully!');
            $this->newLine();
            $this->info('📊 Database Info:');
            $this->line('   - Database: bookkeeping-db_test');
            $this->line('   - Connection: mysql');
            $this->line('   - Host: 127.0.0.1:3306');
            $this->newLine();
            $this->info('🎯 You can now test your application with fresh data!');
            $this->line('   Original data in \'bookkeeping-db\' is untouched.');
            $this->newLine();
            $this->comment('💡 To restore original environment, run:');
            $this->line('   php artisan test:fresh --restore');
        } else {
            $this->error('❌ Migration failed!');
            return 1;
        }

        return 0;
    }

    private function restoreEnvironment(): int
    {
        $this->info('🔄 Restoring original environment...');

        if (file_exists('.env.backup')) {
            copy('.env.backup', '.env');
            unlink('.env.backup');
            $this->info('✅ Original .env restored');

            // Clear cache
            $this->call('config:clear');
            $this->call('cache:clear');

            $this->info('✅ Cache cleared');
            $this->newLine();
            $this->info('🎯 Environment restored to original state');
            $this->line('   Database: bookkeeping-db (original data)');
        } else {
            $this->error('❌ No backup file found');
            $this->line('   Please manually restore your .env file');
            return 1;
        }

        return 0;
    }

    private function restoreEnvFile(): void
    {
        if (file_exists('.env.backup')) {
            copy('.env.backup', '.env');
            unlink('.env.backup');
        }
    }
}
