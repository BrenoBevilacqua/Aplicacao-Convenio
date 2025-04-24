@extends('convenios.app')
@section('content')
@include('convenios._modal_contratos')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Lista de Convênios</h1>
        <a href="{{ route('convenio.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg shadow-md transition-all duration-200 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Novo Convênio
        </a>
    </div>

    <!-- Filtros e pesquisa -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-grow">
                <input type="text" placeholder="Pesquisar convênios..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Status</option>
                    <option value="em_andamento">Em execução</option>
                    <option value="concluido">Finalizado</option>
                </select>
            </div>
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg transition-all duration-200">
                Filtrar
            </button>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dados Gerais
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Recursos / Concedentes
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Finalidade
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valores / Data
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($convenios as $convenio)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="space-y-1">
                                <div class="font-medium text-gray-900">{{ $convenio->identificacao }}</div>
                                <div class="text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Nº {{ $convenio->numero_convenio }}/{{ $convenio->ano_convenio }}
                                    </span>
                                </div>
                                <div class="text-gray-500">C/C: {{ $convenio->conta_vinculada }}</div>
                                <div class="text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $convenio->contratos->count() }} contrato(s)
                                    </span>
                                </div>
                                <div class="text-gray-600 text-xs mt-1">
                                    <span class="font-medium">Objeto:</span>
                                    <span class="line-clamp-2">{{ $convenio->objeto }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="space-y-1.5">
                                <div>
                                    <span class="font-medium text-gray-900">Fonte:</span>
                                    <span class="text-gray-700">{{ $convenio->fonte_recursos }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Concedente:</span>
                                    <span class="text-gray-700">{{ $convenio->concedente }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Parlamentar:</span>
                                    <span class="text-gray-700">{{ $convenio->parlamentar ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="space-y-1.5">
                                <div>
                                    <span class="font-medium text-gray-900">Finalidade:</span>
                                    <span class="text-gray-700">{{ $convenio->natureza_despesa }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="space-y-1.5">
                                <div>
                                    <span class="font-medium text-gray-900">Valor Total:</span>
                                    <span class="text-gray-700">R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Liberado:</span>
                                    <span class="text-gray-700">R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Assinatura:</span>
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($convenio->data_assinatura)->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Vigência:</span>
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($convenio->data_vigencia)->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    @php
                                        $diasRestantes = $convenio->dias_restantes;
                                        $classCor = $diasRestantes < 0 ? 'bg-red-100 text-red-800' : 
                                                    ($diasRestantes < 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                        $textoStatus = $diasRestantes >= 0 ? $diasRestantes . ' dia(s) restantes' : 'Vencido há ' . abs($diasRestantes) . ' dias';
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classCor }}">
                                        {{ $textoStatus }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $acompanhamento = $convenio->acompanhamentos->first();
                            @endphp

                            @if($acompanhamento)
                                <div class="flex flex-col gap-2">
                                    <span class="inline-flex justify-center items-center px-3 py-1.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800 w-full text-center">
                                        {{ ucfirst(str_replace('_', ' ', $acompanhamento->status)) }}
                                    </span>
                                    
                                    @if($acompanhamento->monitorado)
                                        <span class="inline-flex justify-center items-center px-3 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800 w-full text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Monitorado
                                        </span>
                                    @else
                                        <span class="inline-flex justify-center items-center px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-800 w-full text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Não Monitorado
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500 text-center block">Não informado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex flex-col space-y-2 items-center">
                                <button onclick="abrirModalContratos({{ $convenio->id }})"
                                        class="inline-flex items-center bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-3 py-1.5 rounded-md transition-colors font-medium text-xs w-full justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    Contratos ({{ $convenio->contratos->count() }})
                                </button>
                                
                                <div class="flex items-center gap-2 justify-center w-full">
                                    <a href="{{ route('convenios.exportar.pdf', $convenio->id) }}" 
                                       class="inline-flex items-center text-gray-700 hover:text-gray-900 px-2 py-1 text-xs" 
                                       title="Exportar PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                        </svg>
                                        PDF
                                    </a>

                                    <a href="{{ route('convenio.edit', $convenio->id) }}" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 px-2 py-1 text-xs" 
                                       title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Editar
                                    </a>

                                    <form action="{{ route('convenio.destroy', $convenio->id) }}" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este convênio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center text-red-600 hover:text-red-800 px-2 py-1 text-xs" 
                                                title="Excluir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $convenios->onEachSide(0)->links() }}
    </div>
</div>

<script>
function abrirModalContratos(convenioId) {
    document.getElementById('contratoConvenioId').value = convenioId;
    const modal = document.getElementById('modalContratos');
    modal.classList.remove('hidden');

    fetch(`/convenios/${convenioId}/contratos`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('lista-contratos');
            tbody.innerHTML = '';

            if (data.contratos.length === 0) {
                tbody.innerHTML = `
                    <tr class="bg-white">
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            Nenhum contrato encontrado para este convênio
                        </td>
                    </tr>`;
                return;
            }

            data.contratos.forEach(c => {
                tbody.innerHTML += `
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="p-3 border">${c.numero}</td>
                        <td class="p-3 border">${c.empresa}</td>
                        <td class="p-3 border font-medium">${c.valor_formatado}</td>
                        <td class="p-3 border">${c.validade_inicio} a ${c.validade_fim}</td>
                        <td class="p-3 border text-center">
                            <button onclick="deletarContrato(${convenioId}, ${c.id}, this)" 
                                    class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-colors flex items-center mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Excluir
                            </button>
                        </td>
                    </tr>`;
            });
        })
        .catch(error => {
            console.error('Erro ao buscar contratos:', error);
            const tbody = document.getElementById('lista-contratos');
            tbody.innerHTML = `
                <tr class="bg-white">
                    <td colspan="5" class="p-4 text-center text-red-500">
                        Erro ao carregar contratos. Tente novamente.
                    </td>
                </tr>`;
        });
}

function deletarContrato(convenioId, contratoId, btn) {
    if (confirm('Tem certeza que deseja excluir este contrato?')) {
        fetch(`/convenios/${convenioId}/contratos/${contratoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = btn.closest('tr');
                row.remove();
                
                // Atualizar o contador de contratos na tabela principal
                const contador = document.querySelector(`tr[data-convenio-id="${convenioId}"] .contador-contratos`);
                if (contador) {
                    const novoValor = parseInt(contador.textContent) - 1;
                    contador.textContent = novoValor;
                }
            } else {
                alert('Erro ao excluir contrato: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao excluir o contrato.');
        });
    }
}

function fecharModalContratos() {
    document.getElementById('modalContratos').classList.add('hidden');
    document.getElementById('formNovoContrato').reset();
}
</script>
@endsection