<?php

namespace Tests\Feature;

use Exception;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionTest extends TestCase
{
    /**
     * Test the database connection.
     *
     * @return void
     */
    public function testDatabaseConnection()
    {
        $this->withoutExceptionHandling();

        try {
            DB::connection()->getPdo();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail('Could not connect to the database. Please check your configuration. error:' . $e );
        }
    }
}
