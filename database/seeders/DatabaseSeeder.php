<?php

namespace Database\Seeders;

use Database\Seeders\Core\PermissionMatrixSeeder;
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
use Database\Seeders\Workflow\EquipmentBorrowingSeeder;
use Database\Seeders\Workflow\NotificationLogSeeder;
use Database\Seeders\Workflow\RepairSeeder;
use Database\Seeders\Workflow\ReservationSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            // Core
            UserSeeder::class,
            PermissionMatrixSeeder::class,

            // Domain
            ClubSeeder::class,
            EquipmentSeeder::class,
            VenueSeeder::class,

            // Workflow
            ActivitySeeder::class,
            ReservationSeeder::class,
            EquipmentBorrowingSeeder::class,
            CalendarEventSeeder::class,
            RepairSeeder::class,
            NotificationLogSeeder::class,
            CreditLogSeeder::class,

            // Scenario
            ConflictSeeder::class,
            AppealSeeder::class,

            // Demo
            DemoSeeder::class,
        ]);
    }
}
