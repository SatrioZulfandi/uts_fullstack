<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Guard: pastikan automated test tidak pernah berjalan pada Supabase application database.
        $dbHost = config('database.connections.'.config('database.default').'.host', '');
        if (str_contains($dbHost, 'supabase.com') || str_contains($dbHost, 'supabase.co')) {
            $this->fail(
                'GUARD: Automated tests must never run against the Supabase application database. '.
                'DB_HOST = '.$dbHost.'. '.
                'Gunakan database testing lokal (MySQL/PostgreSQL) atau Supabase project khusus testing.'
            );
        }

        if (app()->environment() !== 'testing') {
            $this->fail(
                'GUARD: APP_ENV harus bernilai "testing" saat menjalankan automated tests. '.
                'Saat ini: '.app()->environment()
            );
        }
    }
}
