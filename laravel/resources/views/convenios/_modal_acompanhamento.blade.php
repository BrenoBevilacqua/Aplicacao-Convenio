@vite('resources/js/app.js')  <!-- Para o JS -->
@vite('resources/css/app.css')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<div id="modalAcompanhamento" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="background:white; width:80%; max-width:700px; margin:5% auto; padding:20px; border-radius:8px; position:relative; max-height:90%; overflow-y:auto; box-sizing: border-box;">

        <h3 style="margin-bottom: 20px;">Atualizar Acompanhamento</h3>

        <form id="formNovoAcompanhamento">
            @csrf
            <input type="hidden" name="convenio_id" value="{{ $convenio->id }}">

            <div style="margin-bottom: 15px;">
                <label for="status" style="display:block; margin-bottom: 5px;">Status do Convênio</label>
                <select name="status" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                    <option value="em_execucao">Em execução</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
            <label for="monitorado" style="display:block; margin-bottom: 5px;">Monitoramento</label>
                <select name="monitorado" id="monitorado" required class="form-select">
                    <option value="1">Monitorado</option>
                    <option value="0">Não Monitorado</option>
                </select>
            </div>

            <div style="text-align:right;">
                <button type="submit" style="padding: 10px 20px; background-color: #3498db; color:white; border:none; border-radius:6px;">Salvar</button>
            </div>
        </form>

        <button onclick="fecharModalAcompanhamento()" style="position:absolute; top:10px; right:10px; background-color:#e74c3c; color:white; border:none; padding:5px 10px; border-radius:4px;">X</button>
    </div>
</div>
<script>
function abrirModalAcompanhamento() {
    document.getElementById('modalAcompanhamento').style.display = 'block';
}

function fecharModalAcompanhamento() {
    document.getElementById('modalAcompanhamento').style.display = 'none';
}

document.getElementById('formNovoAcompanhamento').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log("Interceptou o submit");
    const form = e.target;
    const formData = new FormData(form);

    fetch("{{ route('convenios.acompanhamentos.store', $convenio->id) }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("Resposta do backend:", data); // Log para depuração
        if (data.sucesso) {
            // Aqui, ao invés de tentar adicionar uma linha a uma tabela, 
            // você pode simplesmente fechar o modal ou fazer outro tipo de feedback.
            alert('Acompanhamento salvo com sucesso!');
            form.reset();
            fecharModalAcompanhamento();
        } else {
            alert('Erro ao salvar acompanhamento.');
        }
    })
    .catch(error => {
        console.error("Erro ao enviar acompanhamento:", error);
        alert('Erro inesperado.');
    });
}); 
</script>