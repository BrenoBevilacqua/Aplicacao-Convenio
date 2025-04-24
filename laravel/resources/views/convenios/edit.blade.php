
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Convênio</title>
    @vite('resources/css/app.css') 
</head>
<body class="bg-gray-100 p-6 font-sans">

    @include('convenios._modal_acoes')
    @include('convenios._modal_acompanhamento')

    <div class="mb-6">
        <a href="{{ route('convenio.index') }}"
           class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded shadow inline-block">
            ← Voltar
        </a>
    </div>

    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Edição de Convênio</h1>

    <form method="POST" action="{{ route('convenio.update', $convenio->id) }}"
          class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
        @csrf
        @method('PUT')

        <div class="text-center mb-6 space-x-4">
            <button type="button" onclick="abrirModalAcoes()"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                Criar Ações
            </button>
            <button type="button" onclick="abrirModalAcompanhamento()"
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded">
                Acompanhamento
            </button>
        </div>

        <h3 class="text-xl font-semibold text-gray-700 mb-4">Dados do Convênio</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold text-gray-700 mb-1">Número do Convênio</label>
                <input type="text" name="numero_convenio"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('numero_convenio', $convenio->numero_convenio) }}">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Ano do Convênio</label>
                <input type="text" name="ano_convenio"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('ano_convenio', $convenio->ano_convenio) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block font-bold text-gray-700 mb-1">Identificação do Convênio</label>
                <input type="text" name="identificacao"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('identificacao', $convenio->identificacao) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block font-bold text-gray-700 mb-1">Objeto</label>
                <input type="text" name="objeto"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('objeto', $convenio->objeto) }}">
            </div>
        </div>

        <hr class="my-6 border-gray-300">

        <h3 class="text-xl font-semibold text-gray-700 mb-4">Recursos</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold text-gray-700 mb-1">Fonte de Recursos</label>
                <select name="fonte_recursos"
                        class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="" disabled>Selecione uma fonte</option>
                    @foreach ($fontes as $fonte)
                        <option value="{{ $fonte }}" {{ old('fonte_recursos', $convenio->fonte_recursos) == $fonte ? 'selected' : '' }}>{{ $fonte }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Valor Repasse</label>
                <input type="text" id="valor_repasse" name="valor_repasse"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('valor_repasse', number_format($convenio->valor_repasse, 2, ',', '.')) }}">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Valor Contrapartida</label>
                <input type="text" id="valor_contrapartida" name="valor_contrapartida"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('valor_contrapartida', number_format($convenio->valor_contrapartida, 2, ',', '.')) }}">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Valor Total</label>
                <input type="text" id="valor_total" name="valor_total" readonly
                       class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-600"
                       value="{{ old('valor_total', number_format($convenio->valor_total, 2, ',', '.')) }}">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold text-gray-700 mb-1">Concedente</label>
                <select name="concedente" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="" disabled>Selecione um concedente</option>
                    @foreach ($concedentes as $concedente)
                        <option value="{{ $concedente }}" {{ old('concedente', $convenio->concedente) == $concedente ? 'selected' : '' }}>{{ $concedente }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Parlamentar</label>
                <select name="parlamentar" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="" disabled>Selecione um parlamentar</option>
                    @foreach ($parlamentares as $parlamentar)
                        <option value="{{ $parlamentar }}" {{ old('parlamentar', $convenio->parlamentar) == $parlamentar ? 'selected' : '' }}>{{ $parlamentar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block font-bold text-gray-700 mb-1">Conta Vinculada</label>
                <input type="text" name="conta_vinculada"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('conta_vinculada', $convenio->conta_vinculada) }}">
            </div>
        </div>

        <hr class="my-6 border-gray-300">

        <h3 class="text-xl font-semibold text-gray-700 mb-4">Status</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold text-gray-700 mb-1">Natureza de Despesa</label>
                <select name="natureza_despesa"
                        class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="" disabled>Selecione a natureza da despesa</option>
                    @foreach ($naturezas as $natureza)
                        <option value="{{ $natureza }}" {{ old('natureza_despesa', $convenio->natureza_despesa) == $natureza ? 'selected' : '' }}>{{ $natureza }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Data da Assinatura</label>
                <input type="date" name="data_assinatura"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('data_assinatura', $convenio->data_assinatura) }}">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Data da Vigência</label>
                <input type="date" name="data_vigencia"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('data_vigencia', $convenio->data_vigencia) }}">
            </div>
        </div>

        <div class="text-center mt-8">
            <input type="submit" value="Atualizar Convênio"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded cursor-pointer">
        </div>
    </form>

    {{-- Scripts --}}
    <script>
        // funções de abrir Acompanhamento
        function abrirModalAcompanhamento() {
            document.getElementById('modalAcompanhamento').style.display = 'block';
        }

        function fecharModalAcompanhamento() {
            document.getElementById('modalAcompanhamento').style.display = 'none';
        }
        // funções de abrir Ações
        function abrirModalAcoes() {
            document.getElementById('modalAcoes').style.display = 'block';
        }

        function fecharModalAcoes() {
            document.getElementById('modalAcoes').style.display = 'none';
        }
        // funções de formatação de número
        function formatarMoeda(input) {
            input.addEventListener('input', function () {
                let valor = input.value.replace(/\D/g, '');
                valor = (parseInt(valor || 0) / 100).toFixed(2);
                valor = valor.replace('.', ',');
                valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                input.value = valor;
                atualizarTotal();
            });
        }

        function parseMoedaParaFloat(valor) {
            return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
        }

        function atualizarTotal() {
            const repasse = parseMoedaParaFloat(document.getElementById('valor_repasse').value);
            const contrapartida = parseMoedaParaFloat(document.getElementById('valor_contrapartida').value);
            const total = repasse + contrapartida;
            document.getElementById('valor_total').value = total.toFixed(2).replace('.', ',');
        }

        formatarMoeda(document.getElementById('valor_repasse'));
        formatarMoeda(document.getElementById('valor_contrapartida'));

        document.querySelector('form').addEventListener('submit', function () {
            const repasse = document.getElementById('valor_repasse');
            const contrapartida = document.getElementById('valor_contrapartida');
            const total = document.getElementById('valor_total');

            repasse.value = parseMoedaParaFloat(repasse.value);
            contrapartida.value = parseMoedaParaFloat(contrapartida.value);
            total.value = parseMoedaParaFloat(total.value);
        });
    </script>

</body>
</html>
