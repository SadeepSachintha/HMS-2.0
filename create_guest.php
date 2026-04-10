<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = \App\Models\Role::where('slug', 'guest')->first();
if (!$role) {
    echo "No guest role";
} else {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'guest@hms.com'],
        [
            'name' => 'Guest User',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'phone' => '0987654321',
            'status' => 'active'
        ]
    );
    echo "Guest user created/exists";
}
