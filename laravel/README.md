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
<<<<<<< HEAD
]);

# configuração do banco
- Criar banco no mySql
- aplicar estas configurações no '.env':
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app-convenio
DB_USERNAME=root
DB_PASSWORD=
- rodar 'php artisan migrate:fresh'
- gerar chave com 'php artisan key:generate'

# vite
- rodar 'npm install'
- rodar 'npm run build'
=======
]);
>>>>>>> 9c25aaf7692e993e2d2028e484de2b2b79ff3dd2
