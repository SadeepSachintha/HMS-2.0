<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'System Administrator']);
        $managerRole = Role::create(['name' => 'Manager', 'slug' => 'manager', 'description' => 'Hotel Manager']);
        $staffRole = Role::create(['name' => 'Staff', 'slug' => 'staff', 'description' => 'Hotel Staff']);
        $userRole = Role::create(['name' => 'Guest', 'slug' => 'guest', 'description' => 'Hotel Patient/Guest']);

        // 2. Create Admin User
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@hms.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'phone' => '1234567890',
        ]);

        // 3. Create a Demo Hotel
        $hotel = Hotel::create([
            'name' => 'Grand Plaza Hotel',
            'email' => 'info@grandplaza.com',
            'phone' => '+1 800 555 0199',
            'address' => '123 Luxury Avenue',
            'city' => 'Metropolis',
            'country' => 'United States',
            'stars' => 5,
        ]);

        // 4. Attach Rooms to the Hotel using Eloquent Relationship
        $hotel->rooms()->create([
            'room_number' => '101',
            'type' => 'suite',
            'capacity' => 2,
            'price_per_night' => 299.99,
        ]);

        $hotel->rooms()->create([
            'room_number' => '102',
            'type' => 'deluxe',
            'capacity' => 2,
            'price_per_night' => 199.99,
        ]);

        // 5. Create a Reservation using Eloquent Relationship
        $adminUser->reservations()->create([
            'room_id' => 1,
            'check_in_date' => now()->addDays(2),
            'check_out_date' => now()->addDays(5),
            'total_price' => 899.97,
            'status' => 'confirmed',
        ]);
    }
}
