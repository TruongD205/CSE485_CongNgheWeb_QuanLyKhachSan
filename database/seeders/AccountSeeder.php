<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['Username' => 'truong', 'Password' => '123456', 'Gmail' => 'truong@gmail.com'],
            ['Username' => 'dat', 'Password' => '123456', 'Gmail' => 'dat@gmail.com'],
            ['Username' => 'kien', 'Password' => '123456', 'Gmail' => 'kien@gmail.com'],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(
                ['Username' => $account['Username']],
                [
                    'Password' => $account['Password'],
                    'Gmail' => $account['Gmail'],
                ]
            );
        }
    }
}
