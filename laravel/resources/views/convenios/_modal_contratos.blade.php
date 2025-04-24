<div id="modalContratos" class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-30 backdrop-blur-sm hidden z-50">
    <div class="bg-white p-5 rounded-lg shadow-lg w-11/12 max-w-3xl relative">
        <!-- Botão de fechar no topo direito -->
        <button onclick="fecharModalContratos()" class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors">
            <span class="font-bold">×</span>
        </button>
        
        <h2 class="text-xl font-semibold mb-5 text-center text-gray-800">Contratos do Convênio</h2>

        <form id="formContrato" class="space-y-4">
            @csrf
            <input type="hidden" name="convenio_id" id="contratoConvenioId">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="numero_contrato" class="block text-sm font-medium text-gray-700 mb-1">Número do Contrato</label>
                    <input type="text" name="numero_contrato" id="numero_contrato" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label for="empresa_contratada" class="block text-sm font-medium text-gray-700 mb-1">Empresa Contratada</label>
                    <input type="text" name="empresa_contratada" id="empresa_contratada" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="col-span-2">
                    <label for="objeto" class="block text-sm font-medium text-gray-700 mb-1">Objeto</label>
                    <textarea name="objeto" id="objeto" rows="2" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>

                <div>
                    <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">R$</span>
                        <input type="number" name="valor" id="valor" step="0.01" class="w-full border border-gray-300 rounded-md p-2 pl-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                <div>
                    <label for="data_assinatura" class="block text-sm font-medium text-gray-700 mb-1">Data de Assinatura</label>
                    <input type="date" name="data_assinatura" id="data_assinatura" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label for="validade_inicio" class="block text-sm font-medium text-gray-700 mb-1">Início da Validade</label>
                    <input type="date" name="validade_inicio" id="validade_inicio" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label for="validade_fim" class="block text-sm font-medium text-gray-700 mb-1">Fim da Validade</label>
                    <input type="date" name="validade_fim" id="validade_fim" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            <button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md w-full sm:w-auto transition-colors shadow-sm flex items-center justify-center mx-auto">
                <span class="mr-1">+</span> Adicionar Contrato
            </button>
        </form>

        <hr class="my-5 border-gray-200">

        <h4 class="text-lg font-semibold mb-3 text-gray-700">Contratos Cadastrados</h4>

        <div class="overflow-x-auto max-h-64">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-3 text-sm font-medium text-gray-700 border-b text-left">Número</th>
                        <th class="py-2 px-3 text-sm font-medium text-gray-700 border-b text-left">Empresa</th>
                        <th class="py-2 px-3 text-sm font-medium text-gray-700 border-b text-right">Valor</th>
                        <th class="py-2 px-3 text-sm font-medium text-gray-700 border-b text-center">Ações</th>
                    </tr>
                </thead>
                <tbody id="corpoContratos" class="text-sm">
                    <!-- preenchido via JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let contratos = []
let convenioIdAtual = null;

function abrirModalContratos(convenioId, listaContratos = []) {
    convenioIdAtual = convenioId;
    document.getElementById('contratoConvenioId').value = convenioId;
    contratos = listaContratos;
    atualizarTabelaContratos();
    document.getElementById('modalContratos').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Impede rolagem do corpo enquanto modal está aberto
}

function fecharModalContratos() {
    document.getElementById('modalContratos').classList.add('hidden');
    document.getElementById('formContrato').reset();
    document.body.style.overflow = ''; // Restaura rolagem do corpo
}

function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(valor);
}

function atualizarTabelaContratos() {
    const corpo = document.getElementById('corpoContratos');
    corpo.innerHTML = '';
    
    if (contratos.length === 0) {
        corpo.innerHTML = `
            <tr class="border-t">
                <td colspan="4" class="p-3 text-center text-gray-500">Nenhum contrato cadastrado</td>
            </tr>
        `;
        return;
    }
    
    contratos.forEach(c => {
        corpo.innerHTML += `
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">${c.numero_contrato}</td>
                <td class="p-3">${c.empresa_contratada}</td>
                <td class="p-3 text-right">R$ ${formatarMoeda(c.valor)}</td>
                <td class="p-3 text-center">
                    <button onclick="excluirContrato(${c.id})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md text-xs transition-colors">
                        Excluir
                    </button>
                </td>
            </tr>
        `;
    });
}

document.getElementById('formContrato').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    
    // Feedback visual de carregamento
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Salvando...';

    fetch(`/convenios/${convenioIdAtual}/contratos`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': formData.get('_token')
        },
    })
    .then(res => {
        if (!res.ok) {
            return res.text().then(text => { throw new Error(text) });
        }
        return res.json();
    })
    .then(data => {
        if (data.sucesso) {
            contratos.push(data.contrato);
            atualizarTabelaContratos();
            form.reset();
            // Exibir mensagem de sucesso
            const mensagem = document.createElement('div');
            mensagem.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4';
            mensagem.innerHTML = 'Contrato adicionado com sucesso!';
            form.prepend(mensagem);
            setTimeout(() => mensagem.remove(), 3000);
        } else {
            alert('Erro ao adicionar contrato: ' + (data.mensagem || 'Erro desconhecido'));
        }
    })
    .catch(err => {
        console.error('Erro:', err);
        alert('Erro ao salvar contrato. Verifique os dados e tente novamente.');
    })
    .finally(() => {
        // Restaurar botão
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});

function excluirContrato(contratoId) {
    if (!confirm('Deseja realmente excluir este contrato?')) return;
    
    // Token CSRF
    const token = document.querySelector('input[name="_token"]').value;
    
    fetch(`/convenios/${convenioIdAtual}/contratos/${contratoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return res.json();
    })
    .then(data => {
        if (data.sucesso) {
            contratos = contratos.filter(c => c.id !== contratoId);
            atualizarTabelaContratos();
        } else {
            alert('Erro ao excluir contrato: ' + (data.mensagem || 'Erro desconhecido'));
        }
    })
    .catch(err => {
        console.error('Erro:', err);
        alert('Erro ao excluir contrato. Tente novamente.');
    });
}

// Fechar modal clicando fora dele
document.getElementById('modalContratos').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalContratos();
    }
});

// Prevenir propagação de cliques dentro do modal
document.querySelector('#modalContratos > div').addEventListener('click', function(e) {
    e.stopPropagation();
});
</script>