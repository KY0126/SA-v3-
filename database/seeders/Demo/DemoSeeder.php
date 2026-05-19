<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder {
    public function run(): void {
        // Demo data placeholder.
        // By default Demo data is not seeded. To enable, set environment variable `SEED_DEMO=true`.
        if (!env('SEED_DEMO')) {
            return;
        }

        // Add demo-only seed data here when needed.
    }
}
