@extends('convenios.app')

@section('content')
    <h2 class="mb-4">Histórico de Modificações</h2>

    <table>
        <thead>
            <tr>
                <th>Identificação</th>
                <th>Data de Registro</th>
                <th>Usuário de Inclusão</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            @continue(!$log->user)
            <tr>
                <td>{{ $log->numero_convenio }} / {{ $log->ano_convenio }}</td>
                <td>{{ $log->data_modificacao ? $log->data_modificacao->format('d/m/Y H:i') : 'N/A' }}</td>
                <td>{{ $log->user->username }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection