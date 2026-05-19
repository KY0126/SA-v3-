<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionMatrixSeeder extends Seeder {
    public function run(): void {
        $permissions = [
            'admin' => ['*'],
            'officer' => [
                'activity.review',
                'reservation.review',
                'equipment.loan.review',
                'conflict.coordinate',
                'notification.send',
            ],
            'professor' => [
                'activity.review',
                'advice.provide',
            ],
            'staff' => [
                'reservation.manage',
                'repair.manage',
            ],
            'it' => [
                'system.maintain',
                'notification.send',
                'audit.view',
            ],
            'student' => [
                'activity.apply',
                'reservation.apply',
                'equipment.loan.apply',
                'conflict.negotiate',
            ],
        ];

        DB::table('outbox')->updateOrInsert(
            [
                'event_type' => 'core.permission_matrix.v1',
                'status' => 'processed',
            ],
            [
                'payload' => json_encode(
                    ['permissions' => $permissions],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'retry_count' => 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
