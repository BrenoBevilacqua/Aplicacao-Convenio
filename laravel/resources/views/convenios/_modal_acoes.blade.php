<div id="modalAcoes" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="background:white; width:80%; max-width:700px; margin:5% auto; padding:20px; border-radius:8px; position:relative; max-height:90%; overflow-y:auto; box-sizing: border-box;">

        <h3>Nova Ação</h3>
        <form id="formNovaAcao">
            @csrf
            <input type="hidden" name="convenio_id" value="{{ $convenio->id }}">
            <div style="margin-bottom: 10px;">
                <label for="tipo">Tipo</label>
                <select name="tipo" required style="width:100%; padding:8px;">
                    <option value="concedente">Concedente</option>
                    <option value="convenente">Convenente</option>
                </select>
            </div>
            <div style="margin-bottom: 10px;">
                <label for="data_edicao">Data de Edição</label>
                <input type="date" name="data_edicao" value="{{ now()->format('Y-m-d') }}" readonly style="width:100%; padding:8px;">
            </div>
            <div style="margin-bottom: 10px;">
                <label for="observacao">Observação</label>
                <textarea name="observacao" rows="3" style="width:100%; padding:8px;" required></textarea>
            </div>
            <div style="text-align:right;">
                <button type="submit" style="padding: 10px 20px; background-color: #3498db; color:white; border:none; border-radius:6px;">Salvar</button>
            </div>
        </form>

        <hr>

        <h4>Ações Cadastradas</h4>
        <table style="width:100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 10px; border: 1px solid #ccc;">Tipo</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Data de Edição</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Observação</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Ações</th>
                </tr>
            </thead>
            <tbody id="lista-acoes">
                @foreach($convenio->acoes as $acao)
                <tr style="background-color: #fff;">
                    <td style="padding: 10px; border: 1px solid #ccc;">{{ ucfirst($acao->tipo) }}</td>
                    <td style="padding: 10px; border: 1px solid #ccc;">{{ \Carbon\Carbon::parse($acao->data_edicao)->format('d/m/Y') }}</td>
                    <td style="padding: 10px; border: 1px solid #ccc; word-break: break-word; white-space: normal;">{{ $acao->observacao }}</td>
                    <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                        <button onclick="deletarAcao({{ $convenio->id }}, {{ $acao->id }}, this)" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                            Apagar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button onclick="fecharModalAcoes()" style="position:absolute; top:10px; right:10px; background-color:#e74c3c; color:white; border:none; padding:5px 10px; border-radius:4px;">X</button>
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
            const novaLinha = `
                <tr style="background-color: #fff;">
                    <td style="padding: 10px; border: 1px solid #ccc;">${data.acao.tipo.charAt(0).toUpperCase() + data.acao.tipo.slice(1)}</td>
                    <td style="padding: 10px; border: 1px solid #ccc;">${data.acao.data_edicao_formatada}</td>
                    <td style="padding: 10px; border: 1px solid #ccc; word-break: break-word; white-space: normal;">${data.acao.observacao}</td>
                    <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                    <button onclick="deletarAcao(${data.acao.convenio_id}, ${data.acao.id}, this)" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                        Apagar
                    </button>
                    </td>
                </tr>
                `;
            document.getElementById('lista-acoes').insertAdjacentHTML('beforeend', novaLinha);
            form.reset();
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