<?php

namespace Database\Seeders;

use Database\Seeders\Core\UserSeeder;
use Database\Seeders\Demo\DemoSeeder;
use Database\Seeders\Domain\ClubSeeder;
use Database\Seeders\Domain\EquipmentSeeder;
use Database\Seeders\Domain\VenueSeeder;
use Database\Seeders\Scenario\ConflictSeeder;
use Database\Seeders\Workflow\ActivitySeeder;
use Database\Seeders\Workflow\AppealSeeder;
use Database\Seeders\Workflow\CalendarEventSeeder;
use Database\Seeders\Workflow\CreditLogSeeder;
use Database\Seeders\Workflow\NotificationLogSeeder;
use Database\Seeders\Workflow\RepairSeeder;
use Database\Seeders\Workflow\ReservationSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            ClubSeeder::class,
            VenueSeeder::class,
            EquipmentSeeder::class,
            ActivitySeeder::class,
            ReservationSeeder::class,
            ConflictSeeder::class,
            CreditLogSeeder::class,
            NotificationLogSeeder::class,
            CalendarEventSeeder::class,
            RepairSeeder::class,
            AppealSeeder::class,
            DemoSeeder::class,
        ]);
    }
}
