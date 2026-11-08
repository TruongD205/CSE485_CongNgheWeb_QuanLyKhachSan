<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::updateOrCreate(
            ['Username' => 'truong'],
            [
                'Password' => '123456',
                'Gmail'    => 'truong@gmail.com',
            ]
        );
    }
}
