@extends('convenios.app')

@section('content')
    <h2 class="text-xl font-bold mb-4 text-center">Histórico de Modificações</h2>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-500 text-white">
                <tr>
                    <th class="w-1/4 text-center px-4 py-2">Identificação do Convênio</th>
                    <th class="w-1/4 text-center px-4 py-2">Data de Registro</th>
                    <th class="w-1/4 text-center px-4 py-2">Ação</th>
                    <th class="w-1/4 text-center px-4 py-2">Usuário de Inclusão</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    @continue(!$log->user)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="text-center px-4 py-2">{{ $log->numero_convenio }} / {{ $log->ano_convenio }}</td>
                        <td class="text-center px-4 py-2">
                            {{ $log->data_modificacao ? $log->data_modificacao->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="text-center px-4 py-2">{{ $log->acao }}</td>
                        <td class="text-center px-4 py-2">{{ $log->user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
