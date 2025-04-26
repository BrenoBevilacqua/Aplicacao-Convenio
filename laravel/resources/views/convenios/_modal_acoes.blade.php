@vite(['resources/css/app.css', 'resources/js/app.js'])
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<div id="modalAcoes" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="background:white; width:80%; max-width:700px; margin:5% auto; padding:20px; border-radius:8px; position:relative; max-height:90%; overflow-y:auto; box-sizing: border-box;">

        <h3 class="text-xl font-semibold mb-4">Nova Ação</h3>
        <form id="formNovaAcao">
            @csrf
            <input type="hidden" name="convenio_id" value="{{ $convenio->id }}">
            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="tipo" required class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="concedente">Concedente</option>
                    <option value="convenente">Convenente</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="data_edicao" class="block text-sm font-medium text-gray-700">Data de Edição</label>
                <input type="date" name="data_edicao" value="{{ now()->format('Y-m-d') }}" readonly class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="observacao" class="block text-sm font-medium text-gray-700">Observação</label>
                <textarea name="observacao" rows="3" class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <div class="text-right">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">Salvar</button>
            </div>
        </form>
        <button onclick="fecharModalAcoes()" class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">X</button>
    </div>
</div>

<script>
document.getElementById('formNovaAcao').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch("{{ route('convenios.acoes.store', $convenio->id) }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error("Erro ao interpretar resposta como JSON:", text);
            throw new Error("Resposta do servidor não é JSON.");
        }
    })
    .then(data => {
        if (data.sucesso) {
            alert('Sucesso ao salvar a ação')
        } else {
            alert('Erro ao salvar a ação.');
        }
    })
    .catch(error => {
        console.error("Erro inesperado ao salvar ação:", error);
        alert('Erro inesperado ao salvar.');
    });
});

function deletarAcao(convenioId, acaoId, btn) {
    if (!confirm("Tem certeza que deseja apagar esta ação?")) return;

    fetch(`/convenios/${convenioId}/acoes/${acaoId}`, {
        method: "DELETE",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) {
            btn.closest('tr').remove();
        } else {
            alert('Erro ao apagar ação: ' + (data.mensagem ?? 'Erro desconhecido.'));
        }
    })
    .catch(err => {
        console.error("Erro inesperado ao apagar ação:", err);
        alert('Erro inesperado ao apagar.');
    });
}
</script>