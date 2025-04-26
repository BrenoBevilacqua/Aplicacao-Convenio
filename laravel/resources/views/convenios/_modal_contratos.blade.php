<!-- Modal Contratos -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="modalContratos" class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-30 backdrop-blur-sm hidden z-50">
    <div class="bg-white p-5 rounded-lg shadow-lg w-11/12 max-w-3xl relative">
        <!-- Cabeçalho do Modal -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
            <h3 class="text-xl font-semibold text-gray-900">
                Contratos do Convênio
            </h3>
            <button type="button" onclick="fecharModalContratos()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <!-- Corpo do Modal -->
        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
            <!-- Lista de Contratos -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Contrato</th>
                            <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                            <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validade</th>
                            <th class="px-4 py-2 border text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="lista-contratos" class="divide-y divide-gray-200">
                        <!-- Contratos serão carregados dinamicamente via JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Formulário para adicionar novo contrato -->
            <div class="mt-8 border-t pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Adicionar Novo Contrato</h4>
                <form id="formNovoContrato" class="space-y-4">
                    @csrf
                    <input type="hidden" name="convenio_id" id="contratoConvenioId">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="numero_contrato" class="block text-sm font-medium text-gray-700 mb-1">Número do Contrato</label>
                            <input type="text" name="numero_contrato" id="numero_contrato" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label for="empresa_contratada" class="block text-sm font-medium text-gray-700 mb-1">Empresa Contratada</label>
                            <input type="text" name="empresa_contratada" id="empresa_contratada" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>

                    <div>
                        <label for="objeto" class="block text-sm font-medium text-gray-700 mb-1">Objeto</label>
                        <textarea name="objeto" id="objeto" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
                            <input type="number" name="valor" id="valor" step="0.01" min="0" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="data_assinatura" class="block text-sm font-medium text-gray-700 mb-1">Data de Assinatura</label>
                            <input type="date" name="data_assinatura" id="data_assinatura" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="validade_inicio" class="block text-sm font-medium text-gray-700 mb-1">Início da Validade</label>
                            <input type="date" name="validade_inicio" id="validade_inicio" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="validade_fim" class="block text-sm font-medium text-gray-700 mb-1">Fim da Validade</label>
                            <input type="date" name="validade_fim" id="validade_fim" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botões de ação -->
        <div class="flex items-center justify-end p-6 border-t border-gray-200 rounded-b gap-3">
            <button type="button" onclick="fecharModalContratos()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                Cancelar
            </button>
            <button type="button" onclick="salvarContrato()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                Salvar Contrato
            </button>
        </div>
    </div>
</div>

<script>

// Armazenar o ID do convênio atual
let convenioAtual = null;

// Função para abrir o modal de contratos
function abrirModalContratos(convenioId) {
    convenioAtual = convenioId;
    document.getElementById('contratoConvenioId').value = convenioId;
    document.getElementById('formNovoContrato').reset();
    document.getElementById('modalContratos').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Impede rolagem do body quando modal está aberto
    carregarContratos(convenioId);
}

// Função para fechar o modal de contratos
function fecharModalContratos() {
    document.getElementById('modalContratos').classList.add('hidden');
    document.body.style.overflow = ''; // Restaura rolagem do body
}

// Adiciona handler de tecla ESC para fechar o modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !document.getElementById('modalContratos').classList.contains('hidden')) {
        fecharModalContratos();
    }
});

// Fechar modal ao clicar fora dele (no overlay)
document.getElementById('modalContratos').addEventListener('click', function(event) {
    if (event.target === this) {
        fecharModalContratos();
    }
});

// Função para salvar o contrato
function salvarContrato() {
    const form = document.getElementById('formNovoContrato');
    const convenioId = document.getElementById('contratoConvenioId').value;
    
    // Verificar validação do formulário
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Coletar dados do formulário
    const formData = new FormData(form);
    
    // Converter FormData para objeto
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Enviar requisição via fetch API
    fetch(`/convenios/${convenioId}/contratos`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            // Limpar o formulário
            form.reset();
            
            // Recarregar a lista de contratos
            carregarContratos(convenioId);
            
            // Mostrar mensagem de sucesso
            alert('Contrato salvo com sucesso!');
        } else {
            alert('Erro ao salvar contrato: ' + (data.mensagem || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao salvar o contrato.');
    });
}

// Função para carregar contratos do convênio
function carregarContratos(convenioId) {
    fetch(`/convenios/${convenioId}/contratos`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Mantenha isso para debugging
            const tbody = document.getElementById('lista-contratos');
            tbody.innerHTML = '';
            
            // Verifique se data.contratos existe e tem elementos
            const contratos = data.contratos || [];
            
            if (contratos.length === 0) {
                tbody.innerHTML = `
                    <tr class="bg-white">
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            Nenhum contrato encontrado para este convênio
                        </td>
                    </tr>`;
                return;
            }
            
            contratos.forEach(contrato => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border">${contrato.numero_contrato || 'N/A'}</td>
                        <td class="px-4 py-3 border">${contrato.empresa_contratada || 'N/A'}</td>
                        <td class="px-4 py-3 border font-medium">R$ ${parseFloat(contrato.valor || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                        <td class="px-4 py-3 border">${formatarData(contrato.validade_inicio)} a ${formatarData(contrato.validade_fim)}</td>
                        <td class="px-4 py-3 border text-center">
                            <button onclick="deletarContrato(${convenioId}, ${contrato.id})" 
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
            console.error('Erro ao carregar contratos:', error);
            const tbody = document.getElementById('lista-contratos');
            tbody.innerHTML = `
                <tr class="bg-white">
                    <td colspan="5" class="p-4 text-center text-red-500">
                        Erro ao carregar contratos. Tente novamente.
                    </td>
                </tr>`;
        });
}

// Função para deletar contrato
function deletarContrato(convenioId, contratoId) {
    if (!confirm('Tem certeza que deseja excluir este contrato?')) {
        return;
    }
    
    fetch(`/convenios/${convenioId}/contratos/${contratoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            carregarContratos(convenioId);
            alert('Contrato excluído com sucesso!');
            
            // Atualizar o contador de contratos na tabela principal
            const contadorElement = document.querySelector(`button[onclick="abrirModalContratos(${convenioId})"]`);
            if (contadorElement) {
                const textoAtual = contadorElement.textContent;
                const match = textoAtual.match(/Contratos \((\d+)\)/);
                if (match && match[1]) {
                    const novoValor = parseInt(match[1]) - 1;
                    contadorElement.textContent = `Contratos (${novoValor})`;
                }
            }
        } else {
            alert('Erro ao excluir contrato: ' + (data.mensagem || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao excluir o contrato.');
    });
}

// Função para formatar data (YYYY-MM-DD para DD/MM/YYYY)
function formatarData(dataString) {
    if (!dataString) return 'N/A';
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR');
}
</script>