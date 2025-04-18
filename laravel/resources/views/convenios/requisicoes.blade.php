@extends('convenios.app')

@section('content')
    <h2>Requisições de Cadastro</h2>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.aprovar', $user->id) }}">
                            @csrf
                            <button type="submit" style="background:green; color:white; border:none; padding:5px 10px; border-radius:5px;">Aprovar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Nenhuma requisição pendente.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@push('styles')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background: #f0f0f0;
        }

        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
@endpush