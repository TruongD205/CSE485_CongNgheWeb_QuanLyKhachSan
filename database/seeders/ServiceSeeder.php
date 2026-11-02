<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'ServiceName' => 'Nước suối Aquafina',
                'Price' => 10000,
                'Unit' => 'chai',
                'Description' => 'Nước khoáng đóng chai 500ml',
            ],
            [
                'ServiceName' => 'Nước ngọt Coca-Cola',
                'Price' => 15000,
                'Unit' => 'lon',
                'Description' => 'Nước ngọt có gas 330ml',
            ],
            [
                'ServiceName' => 'Giặt ủi quần áo',
                'Price' => 30000,
                'Unit' => 'kg',
                'Description' => 'Giặt sấy và ủi phẳng quần áo',
            ],
            [
                'ServiceName' => 'Thuê xe máy',
                'Price' => 150000,
                'Unit' => 'ngày',
                'Description' => 'Xe tay ga hoặc xe số kèm 2 mũ bảo hiểm',
            ],
            [
                'ServiceName' => 'Ăn sáng buffet',
                'Price' => 60000,
                'Unit' => 'suất',
                'Description' => 'Phục vụ bữa sáng từ 6h30 đến 9h30',
            ],
        ];

        foreach ($services as $item) {
            Service::updateOrCreate(
                ['ServiceName' => $item['ServiceName']],
                [
                    'Price' => $item['Price'],
                    'Unit' => $item['Unit'],
                    'Description' => $item['Description'],
                ]
            );
        }
    }
}

