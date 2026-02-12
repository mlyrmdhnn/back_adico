<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Brand;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\HariKerja;
use App\Models\Notifications;
use App\Models\OmsetSalesman;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Product;
use App\Models\RequestItems;
use App\Models\Requests;
use App\Models\SalesTargets;
use App\Models\Store;
use App\Models\Uom;
use App\Models\UserChat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'manager',
            'username' => 'manager',
            'role' => 'manager',
            'password' => Hash::make('password'),
            'phone' => '081295096247'
        ]);

        User::factory()->create([
            'name' => 'Mulya Ramadhan',
            'username' => 'developer',
            'role' => 'developer',
            'password' => Hash::make('password'),
            'phone' => '12344567'
        ]);

        // User::factory()->create([
        //     'name' => 'Anita Sari',
        //     'username' => 'salesman1',
        //     'role' => 'salesman',
        //     'password' => Hash::make('password'),
        //     'phone' => '',
        //     'code' => 'TO-001'
        // ]);

        $salesmen = [
            ['name' => 'ANITA SARI', 'username' => 'salesman1', 'code' => 'TO-001'],
            ['name' => 'SEPTILIA ROZI', 'username' => 'salesman2', 'code' => 'TO-002'],
            ['name' => 'DEDI RUSTANDI', 'username' => 'salesman3', 'code' => 'TO-003'],
            ['name' => 'TAUFIK SABILLA RASYAD', 'username' => 'salesman4', 'code' => 'TO-016'],
            ['name' => 'AHMAD DASUKI', 'username' => 'salesman5', 'code' => 'TO-010'],
            ['name' => 'MUHIDIN', 'username' => 'salesman6', 'code' => 'TO-008'],
            ['name' => 'NONO SUHARTONO', 'username' => 'salesman7', 'code' => 'TO-023'],
        ];


        foreach ($salesmen as $index => $sales) {
            User::create([
                'name' => $sales['name'],
                'username' => $sales['username'],
                'role' => 'salesman',
                'password' => Hash::make('password'),
                'phone' => null,
                'code' => $sales['code'],
            ]);
        }


        AttendanceType::factory()->create([
            'type' => 'hadir'
        ]);
        AttendanceType::factory()->create([
            'type' => 'izin'
        ]);
        AttendanceType::factory()->create([
            'type' => 'sakit'
        ]);
        AttendanceType::factory()->create([
            'type' => 'telat'
        ]);

        $this->call(ProductImportSeeder::class);


        // Uom::factory()->create([]);
        // Configuration::factory()->create();
        // Brand::factory()->create();
        // Product::factory()->create();
    }
}
