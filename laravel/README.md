# vite
https://laravel.com/docs/11.x/vite

# Traduzir para pt-br
https://github.com/lucascudo/laravel-pt-BR-localization

# criação de admMaster

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Administrador Master',
    'email' => 'adminmaster@example.com',
    'username' => 'adminMaster',
    'password' => Hash::make('senha123'),
    'role' => User::ROLE_ADMIN_MASTER,
]);