import '../css/app.css';

// Arquivo app.js centralizado

document.addEventListener('DOMContentLoaded', function () {
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
    abrirModalAcompanhamento: function () {
        const modal = document.getElementById('modalAcompanhamento');
        if (modal) modal.style.display = 'block';
    },

    fecharModalAcompanhamento: function () {
        const modal = document.getElementById('modalAcompanhamento');
        if (modal) modal.style.display = 'none';
    },

    // Modal Ações
    abrirModalAcoes: function () {
        const modal = document.getElementById('modalAcoes');
        if (modal) modal.style.display = 'block';
    },

    fecharModalAcoes: function () {
        const modal = document.getElementById('modalAcoes');
        if (modal) modal.style.display = 'none';
    },

    // Modal Contratos
    abrirModalContratos: function (convenioId) {
        // Setar o ID do convênio no campo oculto
        const idField = document.getElementById('contratoConvenioId');
        if (idField) idField.value = convenioId;

        // Exibir o modal
        const modal = document.getElementById('modalContratos');
        if (modal) modal.classList.remove('hidden');

        document.body.style.overflow = 'hidden'; // Impede rolagem do body quando modal está aberto
        this.carregarContratos(convenioId);
    },

    fecharModalContratos: function () {
        const modal = document.getElementById('modalContratos');
        const form = document.getElementById('formNovoContrato');

        if (modal) modal.classList.add('hidden');
        if (form) form.reset();
        document.body.style.overflow = ''; // Restaura rolagem do body
    },

    // Carregamento de contratos para o modal
    carregarContratos: function (convenioId) {
        fetch(`/convenios/${convenioId}/contratos`)
            .then(res => res.json())
            .then(data => {
                console.log(data); // Para debugging
                const tbody = document.getElementById('lista-contratos');

                // Verificar se o elemento #lista-contratos existe
                if (!tbody) {
                    console.error('Elemento #lista-contratos não encontrado no DOM');
                    return;
                }

                tbody.innerHTML = ''; // Limpar a tabela

                if (!data.sucesso || !data.contratos || data.contratos.length === 0) {
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
                            <td class="p-3 border text-xs">${c.numero_contrato || 'N/A'}</td>
                            <td class="p-3 border text-xs">${c.empresa_contratada || 'N/A'}</td>
<<<<<<< HEAD
                            <td class="p-3 border text-xs break-words max-w-xs">${c.objeto || 'N/A'}</td>
=======
>>>>>>> 9c25aaf7692e993e2d2028e484de2b2b79ff3dd2
                            <td class="p-3 border text-xs font-medium">R$ ${parseFloat(c.valor || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td class="p-3 border text-xs ${classeVencimento}">
                                ${this.formatarData(c.validade_inicio)} a 
                                ${this.formatarData(c.validade_fim)}
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
    },

    // Formatar data (YYYY-MM-DD para DD/MM/YYYY)
    formatarData: function (dataString) {
        if (!dataString) return 'N/A';
        const data = new Date(dataString);
        return data.toLocaleDateString('pt-BR');
    }
};

/**
 * Gerenciamento de contratos
 */
const ContratosManager = {
    // Armazenar o ID do convênio atual
    convenioAtual: null,

    // Salvar contrato
    salvarContrato: function () {
        const form = document.getElementById('formNovoContrato');
        const convenioId = document.getElementById('contratoConvenioId').value;

        // Verificar validação do formulário
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Converter o campo valor_contrato para formato numérico
        const valorInput = form.querySelector('#valor');
        if (valorInput) {
            valorInput.value = FormatManager.parseMoedaParaFloat(valorInput.value);
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
                    ModalManager.carregarContratos(convenioId);

                    // Mostrar mensagem de sucesso
                    alert('Contrato salvo com sucesso!');

                    // Opcional: recarregar a página para atualizar os contadores na tabela principal
                    // window.location.reload();
                } else {
                    alert('Erro ao salvar contrato: ' + (data.mensagem || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao salvar o contrato.');
            });
    },

    // Deletar contrato
    deletarContrato: function (convenioId, contratoId, btn) {
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
                        // Se o botão foi passado, remover a linha diretamente
                        if (btn) {
                            const row = btn.closest('tr');
                            if (row) row.remove();
                        } else {
                            // Caso contrário, recarregar todos os contratos
                            ModalManager.carregarContratos(convenioId);
                        }

                        // Atualizar o contador de contratos na tabela principal
                        const contador = document.querySelector(`tr[data-convenio-id="${convenioId}"] .contador-contratos`);
                        if (contador) {
                            const novoValor = parseInt(contador.textContent) - 1;
                            contador.textContent = novoValor;
                        }

                        // Opcional: recarregar a página para atualizar os contadores
                        window.location.reload();
                    } else {
                        alert('Erro ao excluir contrato: ' + (data.mensagem || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Ocorreu um erro ao excluir o contrato.');
                });
        }
    },

    // Inicializa manipuladores de eventos para contratos
    initEventListeners: function () {
        // Adicionar handler para fechar modal com tecla ESC
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !document.getElementById('modalContratos').classList.contains('hidden')) {
                ModalManager.fecharModalContratos();
            }
        });

        // Fechar modal ao clicar fora dele (no overlay)
        const modalContratos = document.getElementById('modalContratos');
        if (modalContratos) {
            modalContratos.addEventListener('click', function (event) {
                if (event.target === this) {
                    ModalManager.fecharModalContratos();
                }
            });
        }

        // Inicializar formatador de moeda para o campo valor do contrato
        const valorContratoInput = document.getElementById('valor');
        if (valorContratoInput) {
            FormatManager.formatarMoeda(valorContratoInput);
        }
    }
};

/**
 * Formatação de valores monetários
 */
const FormatManager = {
    // Inicializa todos os campos de moeda da página
    initMoneyFields: function () {
        // Seleciona todos os inputs com a classe 'money'
        const moneyInputs = document.querySelectorAll('.money');
        moneyInputs.forEach(input => {
            this.formatarMoeda(input);
        });

        // Adicionar formatação específica para o campo de valor do contrato
        const valorContrato = document.getElementById('valor');
        if (valorContrato) {
            this.formatarMoeda(valorContrato);
        }
    },

    formatarMoeda: function (input) {
        if (!input) return;

        // Formatar valor inicial se existir
        if (input.value) {
            let valor = input.value.replace(/\D/g, '');
            if (valor) {
                valor = (parseFloat(valor) / 100).toFixed(2);
                valor = valor.replace('.', ',');
                valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                input.value = valor;
            }
        }

        input.addEventListener('input', function () {
            let valor = input.value.replace(/\D/g, '');
            valor = (parseInt(valor || 0) / 100).toFixed(2);
            valor = valor.replace('.', ',');
            valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = valor;

            // Se este input é parte do cálculo de total, atualize o total
            if (input.id === 'valor_repasse' || input.id === 'valor_contrapartida') {
                FormatManager.atualizarTotal();
            }
        });
    },

    parseMoedaParaFloat: function (valor) {
        if (!valor) return 0;
        return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
    },

    atualizarTotal: function () {
        const repasse = document.getElementById('valor_repasse');
        const contrapartida = document.getElementById('valor_contrapartida');
        const total = document.getElementById('valor_total');

        if (!repasse || !contrapartida || !total) return;

        const valorRepasse = this.parseMoedaParaFloat(repasse.value);
        const valorContrapartida = this.parseMoedaParaFloat(contrapartida.value);
        const valorTotal = valorRepasse + valorContrapartida;

        total.value = valorTotal.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    updatePorcentagemValue: function (val) {
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
    setupAcompanhamentoForm: function () {
        const form = document.getElementById('formNovoAcompanhamento');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            console.log("Interceptou o submit");

            // Converter valor formatado para formato numérico antes de enviar
            const valorLiberado = document.getElementById('valor_liberado');
            if (valorLiberado) {
                // Usar a função para converter de volta para float
                valorLiberado.value = FormatManager.parseMoedaParaFloat(valorLiberado.value);
            }

            const formData = new FormData(form);

            // Obter a URL correta do atributo data-action ou criar dinamicamente
            const convenioId = formData.get('convenio_id');
            const url = form.getAttribute('data-action') ||
                `/convenios/${convenioId}/acompanhamentos`;

            // Extrair o token CSRF do formulário
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                form.querySelector('[name="_token"]')?.value;

            fetch(url, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    // Não incluir Content-Type para deixar o navegador definir com o boundary correto para FormData
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
                        // Recarregar a página para mostrar o novo acompanhamento
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

    setupContratoForm: function () {
        const form = document.getElementById('formNovoContrato');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            ContratosManager.salvarContrato();
        });
    },

    setupMoneySubmitHandler: function () {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function () {
                // Encontra todos os inputs com a classe money neste formulário
                const moneyInputs = form.querySelectorAll('.money');

                moneyInputs.forEach(input => {
                    // Converte para float antes de enviar
                    input.value = FormatManager.parseMoedaParaFloat(input.value);
                });

                // Garantir que o campo valor_total também seja convertido, se existir
                const total = form.querySelector('#valor_total');
                if (total) total.value = FormatManager.parseMoedaParaFloat(total.value);

                // Garantir que o campo valor do contrato também seja convertido, se existir
                const valorContrato = form.querySelector('#valor');
                if (valorContrato) valorContrato.value = FormatManager.parseMoedaParaFloat(valorContrato.value);
            });
        });
    }
};

/**
 * Inicialização dos módulos
 */
function initFormHandlers() {
    FormatManager.initMoneyFields();
    FormManager.setupAcompanhamentoForm();
    FormManager.setupContratoForm();
    FormManager.setupMoneySubmitHandler();
}

function initModalHandlers() {
    // Expor as funções de modal para o escopo global para que possam ser chamadas pelos botões
    window.abrirModalAcompanhamento = ModalManager.abrirModalAcompanhamento;
    window.fecharModalAcompanhamento = ModalManager.fecharModalAcompanhamento;
    window.abrirModalAcoes = ModalManager.abrirModalAcoes;
    window.fecharModalAcoes = ModalManager.fecharModalAcoes;
    window.abrirModalContratos = function (id) { ModalManager.abrirModalContratos(id); };
    window.fecharModalContratos = ModalManager.fecharModalContratos;
    window.salvarContrato = ContratosManager.salvarContrato;

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
    // Inicializar manipuladores de eventos específicos para contratos
    ContratosManager.initEventListeners();

    // Expor função de deletar contrato para o escopo global
    window.app = window.app || {};
    window.app.deletarContrato = ContratosManager.deletarContrato;
}