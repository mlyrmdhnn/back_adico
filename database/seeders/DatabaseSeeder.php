<?php

namespace Database\Seeders;

use App\Models\AttendanceType;
use App\Models\PaymentMethod;
use App\Models\User;
use Database\Seeders\ProductImportSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        AttendanceType::factory()->create([
            'type' => 'izin setengah hari'
        ]);

        $this->call(ProductImportSeeder::class);
        $this->call(CustomerImportSeeder::class);

        PaymentMethod::factory()->create([
            'name' => 'cash'
        ]);
        PaymentMethod::factory()->create([
            'name' => 'kredit (7 hari)'
        ]);
        PaymentMethod::factory()->create([
            'name' => 'kredit (14 hari)'
        ]);
        PaymentMethod::factory()->create([
            'name' => 'kredit (21 hari)'
        ]);
        PaymentMethod::factory()->create([
            'name' => 'kredit (30 hari)'
        ]);


    }
}
