# Passos
- Instalar pasta do projeto
- Ir ao drive e instalar o PHP 8.4.5
- Renomear a pasta PHP para 'php-8.4.5' e movê-la para C:
- Adicionar o caminho da pasta PHP para PATH
- Testar php -v
- Baixar e instalar 'Composer-Setup.exe' -> https://getcomposer.org/download/
- Abrir projeto com VSCode
- aplicar estas configurações no '.env':

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=app-convenio
    DB_USERNAME=root
    DB_PASSWORD=

- Descomentar 'extension-fileinfo' no php.ini
- Rodar 'npm install' e, logo em seguida, 'npm run build'
- Descomentar 'extension=pdo_mysql' no php.ini
- Instalar XAMPP -> https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/ xampp-windows-x64-8.2.12-0-VS16-installer.exe/download
- Ligar Apache e MySql
- Rodar 'php artisan key:generate'
- Criar o banco 'app-convenio'
- Rodar 'php artisan migrate'
- Criar o admMaster com 'php artisan tinker':

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Administrador Master',
    'email' => 'adminmaster@example.com',
    'username' => 'adminMaster',
    'password' => Hash::make('senha123'),
    'role' => User::ROLE_ADMIN_MASTER,
]);


