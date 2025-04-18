<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Convênios</title>

    <!-- Fonte bonita e um CSS leve -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: #f8f9fa;
            color: #212529;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: #343a40;
            padding: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
            color: #fff;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }

        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        table th, table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #f1f3f5;
            font-weight: 600;
        }

        a.btn {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a.btn:hover {
            background-color: #084298;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }
        .table-actions .actions-container {
        display: flex; /* Usar flexbox para alinhar os botões lado a lado */
        flex-direction: column; /* Organizar os botões verticalmente */
        gap: 10px; /* Espaço entre os botões */
        }

        .table-actions a {
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        background-color: transparent;
        color: #007bff;
        border: 1px solid #007bff;
        font-weight: 600;
        display: block; /* Para garantir que o link ocupe toda a largura disponível */
        }

        .table-actions a:hover {
        background-color: #007bff;
        color: white;
        }   

        .table-actions button {
        color: white;
        border: none;
        background-color: #dc3545;
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 600;
        display: block; /* Garante que o botão ocupe toda a largura disponível */
        }

        .table-actions button:hover {
        background-color: #c82333;
        }

/* Garantir que os botões fiquem bem separados */
        .table-actions .actions-container a,
        .table-actions .actions-container button {
            margin-bottom: 5px; /* Espaço entre os botões */
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #bb2d3b;
        }
        .toggle-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .sidebar.hidden {
            display: none;
        }
    </style>

    @stack('styles')
</head>
<body>

    <div class="sidebar">
        <h2>Convênios</h2>
        <a href="{{ route('convenio.index') }}" class="{{ request()->routeIs('convenio.index') ? 'active' : '' }}">📄 Lista de Convênios</a>
        <a href="{{ route('convenios.info') }}" class="{{ request()->routeIs('convenios.info') ? 'active' : '' }}">🕓 Dados</a>
        @auth
        @if (auth()->user()->role === 'admin_master')
        <a href="{{ route('admin.requisicoes') }}" class="{{ request()->routeIs('admin.requisicoes') ? 'active' : '' }}">Ver Requisições</a>
        @endif
        @endauth
        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
    </div>

    <div class="main-content">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <div class="content-box">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
    <script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('hidden');
    }
</script>
</body>
</html>
