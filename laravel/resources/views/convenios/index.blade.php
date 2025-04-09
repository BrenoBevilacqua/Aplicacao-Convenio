<!-- resources/views/convenios/index.blade.php -->

@extends('convenios.app')

@section('content')
<div class="container">
    <h1 class="text-xl font-bold mb-4">Lista de Convênios</h1>

    <div style="position: absolute; top: 30px; right: 55px;">
    <a href="{{ route('convenio.create') }}"
       style="background-color:rgb(10, 59, 163); color: white; font-weight: 600; padding: 10px 20px; border-radius: 6px; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.15); transition: background-color 0.3s;">
        Novo Registro
    </a>
</div>

    <table class="table-auto w-full border-collapse border border-gray-300 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-2 py-1">Dados Gerais</th>
                <th class="border border-gray-300 px-2 py-1">Recursos / Concedentes / Parlamentar</th>
                <th class="border border-gray-300 px-2 py-1">Finalidade / Detalhamento</th>
                <th class="border border-gray-300 px-2 py-1">Valores / Data</th>
                <th class="border border-gray-300 px-2 py-1">Status / Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($convenios as $convenio)
                <tr>
                    <!-- Dados Gerais -->
                    <td class="border border-gray-300 px-2 py-1">
                        <strong>ID:</strong> {{ $convenio->id }}<br>
                        <strong>Nº / Ano:</strong> {{ $convenio->numero_convenio }} / {{ $convenio->ano_convenio }}<br>
                        <strong>Identificação:</strong> {{ $convenio->identificacao }}<br>
                        <strong>C/C:</strong> {{ $convenio->conta_vinculada }}<br>
                        <strong>Contratos:</strong> --<br>
                        <strong>Objeto:</strong> {{ $convenio->objeto }}
                    </td>

                    <!-- Recursos / Concedentes / Parlamentar -->
                    <td class="border border-gray-300 px-2 py-1">
                        <strong>Fonte de recurso:</strong> {{ $convenio->fonte_recursos }}<br>
                        <strong>Concedente:</strong> {{ $convenio->concedente }}<br>
                        <strong>Parlamentar:</strong> {{ $convenio->parlamentar }}
                    </td>

                    <!-- Finalidade / Detalhamento -->
                    <td class="border border-gray-300 px-2 py-1">
                        <strong>Finalidade:</strong> {{ $convenio->natureza_despesa }}<br>
                        <strong>Detalhamento:</strong> --
                    </td>

                    <!-- Valores / Data -->
                    <td class="border border-gray-300 px-2 py-1">
                        <strong>Repasse:</strong> R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}<br>
                        <strong>Valor Total:</strong> R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}<br>
                        <strong>Valor Liberado:</strong> --<br>
                        <strong>Assinatura:</strong> {{ \Carbon\Carbon::parse($convenio->data_assinatura)->format('d/m/Y') }}<br>
                        <strong>Vigência:</strong> {{ \Carbon\Carbon::parse($convenio->data_vigencia)->format('d/m/Y') }}<br>
                        <strong>Dias / Vencimento:</strong> {{ $convenio->dias_restantes >= 0 ? $convenio->dias_restantes . ' dia(s) restantes' : 'Vencido há ' . abs($convenio->dias_restantes) . ' dias atrás' }}
                    </td>

                    <!-- Status / Ações -->
                    <td class="border border-gray-300 px-2 py-1">
                        <strong>Status:</strong> --<br>
                        <a href="{{ route('convenio.edit', $convenio->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <form action="{{ route('convenio.destroy', $convenio->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este convênio?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
