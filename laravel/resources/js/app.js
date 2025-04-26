
import '../css/app.css';

// Arquivo app.js centralizado

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar módulos específicos com base na página atual
    initFormHandlers();
    initModalHandlers();
    initMoneyFormatters();
    
    // Verificar e inicializar tabelas de contratos se existirem
    initContratosHandlers();
});

/**
 * Gerenciamento de todas as funções relacionadas a modais
 */
const ModalManager = {
    // Modal Acompanhamento
    abrirModalAcompanhamento: function() {
        const modal = document.getElementById('modalAcompanhamento');
        if (modal) modal.style.display = 'block';
    },
    
    fecharModalAcompanhamento: function() {
        const modal = document.getElementById('modalAcompanhamento');
        if (modal) modal.style.display = 'none';
    },
    
    // Modal Ações
    abrirModalAcoes: function() {
        const modal = document.getElementById('modalAcoes');
        if (modal) modal.style.display = 'block';
    },
    
    fecharModalAcoes: function() {
        const modal = document.getElementById('modalAcoes');
        if (modal) modal.style.display = 'none';
    },
    
    // Modal Contratos
    abrirModalContratos: function(convenioId) {
        // Setar o ID do convênio no campo oculto
        const idField = document.getElementById('contratoConvenioId');
        if (idField) idField.value = convenioId;

        // Exibir o modal
        const modal = document.getElementById('modalContratos');
        if (modal) modal.classList.remove('hidden');
        
        this.carregarContratos(convenioId);
    },
    
    fecharModalContratos: function() {
        const modal = document.getElementById('modalContratos');
        const form = document.getElementById('formNovoContrato');
        
        if (modal) modal.classList.add('hidden');
        if (form) form.reset();
    },
    
    // Carregamento de contratos para o modal
    carregarContratos: function(convenioId) {
        fetch(`/convenios/${convenioId}/contratos`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('lista-contratos');
                
                // Verificar se o elemento #lista-contratos existe
                if (!tbody) {
                    console.error('Elemento #lista-contratos não encontrado no DOM');
                    return;
                }
                
                tbody.innerHTML = ''; // Limpar a tabela

                if (!data.sucesso || data.contratos.length === 0) {
                    tbody.innerHTML = `
                        <tr class="bg-white">
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                Nenhum contrato encontrado para este convênio
                            </td>
                        </tr>`;
                    return;
                }

                // Preencher a tabela com os contratos
                data.contratos.forEach(c => {
                    // Verificar se o contrato está vencido
                    const hoje = new Date();
                    const dataFim = new Date(c.validade_fim);
                    const vencido = dataFim < hoje;
                    const classeVencimento = vencido ? 'text-red-600 font-medium' : '';
                    
                    tbody.innerHTML += `
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="p-3 border text-xs">${c.numero_contrato}</td>
                            <td class="p-3 border text-xs">${c.empresa_contratada}</td>
                            <td class="p-3 border text-xs font-medium">R$ ${parseFloat(c.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td class="p-3 border text-xs ${classeVencimento}">
                                ${new Date(c.validade_inicio).toLocaleDateString('pt-BR')} a 
                                ${new Date(c.validade_fim).toLocaleDateString('pt-BR')}
                                ${vencido ? ' (Vencido)' : ''}
                            </td>
                            <td class="p-3 border text-center">
                                <button onclick="app.deletarContrato(${convenioId}, ${c.id}, this)" 
                                        class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition-colors flex items-center mx-auto text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
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
                if (tbody) {
                    tbody.innerHTML = `
                        <tr class="bg-white">
                            <td colspan="5" class="p-4 text-center text-red-500">
                                Erro ao carregar contratos. Tente novamente.
                            </td>
                        </tr>`;
                }
            });
    }
};

/**
 * Gerenciamento de contratos
 */
const ContratosManager = {
    deletarContrato: function(convenioId, contratoId, btn) {
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
                if (data.sucesso) {
                    const row = btn.closest('tr');
                    row.remove();
                    
                    // Atualizar o contador de contratos na tabela principal
                    const contador = document.querySelector(`tr[data-convenio-id="${convenioId}"] .contador-contratos`);
                    if (contador) {
                        const novoValor = parseInt(contador.textContent) - 1;
                        contador.textContent = novoValor;
                    }
                    
                    // Recarregar a página para atualizar os contadores
                    window.location.reload();
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
};

/**
 * Formatação de valores monetários
 */
const FormatManager = {
    formatarMoeda: function(input) {
        if (!input) return;
        
        input.addEventListener('input', function () {
            let valor = input.value.replace(/\D/g, '');
            valor = (parseInt(valor || 0) / 100).toFixed(2);
            valor = valor.replace('.', ',');
            valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = valor;
            FormatManager.atualizarTotal();
        });
    },
    
    parseMoedaParaFloat: function(valor) {
        if (!valor) return 0;
        return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
    },
    
    atualizarTotal: function() {
        const repasse = document.getElementById('valor_repasse');
        const contrapartida = document.getElementById('valor_contrapartida');
        const total = document.getElementById('valor_total');
        
        if (!repasse || !contrapartida || !total) return;
        
        const valorRepasse = this.parseMoedaParaFloat(repasse.value);
        const valorContrapartida = this.parseMoedaParaFloat(contrapartida.value);
        const valorTotal = valorRepasse + valorContrapartida;
        
        total.value = valorTotal.toFixed(2).replace('.', ',');
    },
    
    updatePorcentagemValue: function(val) {
        const porcentagemElement = document.getElementById('porcentagemValue');
        if (porcentagemElement) {
            porcentagemElement.innerText = val + '%';
        }
    }
};

/**
 * Gerenciamento de formulários
 */
const FormManager = {
    setupAcompanhamentoForm: function() {
        const form = document.getElementById('formNovoAcompanhamento');
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log("Interceptou o submit");

            const formData = new FormData(form);
            // A rota precisa ser extraída do próprio formulário ou de um atributo data
            // Como não temos acesso direto à rota do blade, precisamos adaptar
            const url = form.getAttribute('action') || window.acompanhamentoStoreRoute;
            
            // Extrair o token CSRF do formulário
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                              form.querySelector('[name="_token"]')?.value;
            
            fetch(url, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log("Resposta do backend:", data);
                if (data.sucesso) {
                    alert('Acompanhamento salvo com sucesso!');
                    form.reset();
                    ModalManager.fecharModalAcompanhamento();
                    // Opcional: recarregar a página para mostrar o novo acompanhamento
                    window.location.reload();
                } else {
                    alert('Erro ao salvar acompanhamento: ' + (data.mensagem || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error("Erro ao enviar acompanhamento:", error);
                alert('Erro inesperado: ' + error.message);
            });
        });
    },
    
    setupMoneySubmitHandler: function() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            if (form && 
                (form.querySelector('#valor_repasse') || 
                 form.querySelector('#valor_contrapartida') || 
                 form.querySelector('#valor_total'))) {
                
                form.addEventListener('submit', function () {
                    const repasse = form.querySelector('#valor_repasse');
                    const contrapartida = form.querySelector('#valor_contrapartida');
                    const total = form.querySelector('#valor_total');
                    
                    if (repasse) repasse.value = FormatManager.parseMoedaParaFloat(repasse.value);
                    if (contrapartida) contrapartida.value = FormatManager.parseMoedaParaFloat(contrapartida.value);
                    if (total) total.value = FormatManager.parseMoedaParaFloat(total.value);
                });
            }
        });
    }
};

/**
 * Inicialização dos módulos
 */
function initFormHandlers() {
    FormManager.setupAcompanhamentoForm();
    FormManager.setupMoneySubmitHandler();
}

function initModalHandlers() {
    // Expor as funções de modal para o escopo global para que possam ser chamadas pelos botões
    window.abrirModalAcompanhamento = ModalManager.abrirModalAcompanhamento;
    window.fecharModalAcompanhamento = ModalManager.fecharModalAcompanhamento;
    window.abrirModalAcoes = ModalManager.abrirModalAcoes;
    window.fecharModalAcoes = ModalManager.fecharModalAcoes;
    window.abrirModalContratos = function(id) { ModalManager.abrirModalContratos(id); };
    window.fecharModalContratos = ModalManager.fecharModalContratos;
    
    // Expor função de atualização de porcentagem
    window.updatePorcentagemValue = FormatManager.updatePorcentagemValue;
}

function initMoneyFormatters() {
    const repasse = document.getElementById('valor_repasse');
    const contrapartida = document.getElementById('valor_contrapartida');
    
    if (repasse) FormatManager.formatarMoeda(repasse);
    if (contrapartida) FormatManager.formatarMoeda(contrapartida);
}

function initContratosHandlers() {
    // Expor função de deletar contrato para o escopo global
    window.app = window.app || {};
    window.app.deletarContrato = ContratosManager.deletarContrato;
}