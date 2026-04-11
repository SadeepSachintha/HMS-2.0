<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$role = Role::where('slug', 'guest')->first();
if (! $role) {
    echo 'No guest role';
} else {
    $user = User::firstOrCreate(
        ['email' => 'guest@hms.com'],
        [
            'name' => 'Guest User',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'phone' => '0987654321',
            'status' => 'active',
        ]
    );
    echo 'Guest user created/exists';
}
