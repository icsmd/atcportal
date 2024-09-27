<?php

namespace Database\Seeders;

use App\Models\Application;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class ApplicationSeeder.
 */
class ApplicationSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Application::factory()->create([
        //     'user_id' => 2,
        // ]);

        $this->enableForeignKeys();
    }
}
