<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\DataKia;

$users = User::all();
foreach ($users as $u) {
    $hasKia = DataKia::where('user_id', $u->id)->exists();
    echo "User: ID={$u->id}, Name={$u->name}, Email={$u->email}, Role={$u->role}, HasKIA=" . ($hasKia ? 'Yes' : 'No') . "\n";
}
