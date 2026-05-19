<?php

namespace Database\Seeders\Core;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        $users = [
            ['student_id' => 'A001', 'name' => '張組長', 'email' => 'a001@cloud.fju.edu.tw', 'phone' => '(02)2905-2002', 'role' => 'admin', 'club_position' => '課指組 組長', 'credit_score' => 100],
            ['student_id' => 'O001', 'name' => '陳承辦', 'email' => 'o001@cloud.fju.edu.tw', 'phone' => '(02)2905-2003', 'role' => 'officer', 'club_position' => '課指組 承辦', 'credit_score' => 100],
            ['student_id' => 'T001', 'name' => '林教授', 'email' => 't001@cloud.fju.edu.tw', 'phone' => '(02)2905-2001', 'role' => 'professor', 'club_position' => '攝影社 指導教授', 'credit_score' => 100],
            ['student_id' => 'S001', 'name' => '王職員', 'email' => 's001@cloud.fju.edu.tw', 'phone' => '(02)2905-2100', 'role' => 'staff', 'club_position' => '總務處 職員', 'credit_score' => 100],
            ['student_id' => 'IT001', 'name' => '李工程師', 'email' => 'it001@cloud.fju.edu.tw', 'phone' => '(02)2905-3001', 'role' => 'it', 'club_position' => '資訊中心 工程師', 'credit_score' => 100],
            ['student_id' => '410012345', 'name' => '王大明', 'email' => '410012345@cloud.fju.edu.tw', 'phone' => '0912-345-678', 'role' => 'student', 'club_position' => '攝影社 副社長', 'credit_score' => 85],
            ['student_id' => '410012346', 'name' => '陳小美', 'email' => '410012346@cloud.fju.edu.tw', 'phone' => '0923-456-789', 'role' => 'student', 'club_position' => '民謠吉他社 社長', 'credit_score' => 92],
            ['student_id' => '410012347', 'name' => '李同學', 'email' => '410012347@cloud.fju.edu.tw', 'phone' => '0934-567-890', 'role' => 'student', 'club_position' => '健言社 社員', 'credit_score' => 90],
            ['student_id' => '410012348', 'name' => '張同學', 'email' => '410012348@cloud.fju.edu.tw', 'phone' => '0945-678-901', 'role' => 'student', 'club_position' => '同舟共濟服務社 社長', 'credit_score' => 88],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['student_id' => $user['student_id']],
                $user + [
                    'password' => Hash::make('Password123!'),
                    'language' => 'zh-TW',
                    'is_active' => true,
                ]
            );
        }
    }
}
