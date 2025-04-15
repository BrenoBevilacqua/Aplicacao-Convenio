<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Convênio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 20px;
        }
        h1, h3 { color: #2c3e50; }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group, div { margin-bottom: 15px; }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #34495e;
        }
        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        input[readonly] {
            background-color: #f1f1f1;
            color: #555;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        ul {
            background: #ffe0e0;
            color: #b00020;
            padding: 10px;
            border-radius: 6px;
        }
        ul li { list-style: none; }
        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    @include('convenios._modal_acoes')

    <div style="position: absolute; top: 20px; left: 20px;">
        <a href="{{ route('convenio.index') }}"
            style="background-color: rgb(10, 59, 163); color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
            ← Voltar
        </a>
    </div>

    <h1 style="text-align:center;">Edição de Convênio</h1>
    <form method="POST" action="{{ route('convenio.update', $convenio->id) }}">
        @csrf
        @method('PUT')


        <div style="text-align:center; margin-bottom: 20px;">
            <button type="button" onclick="abrirModalAcoes()"
            style="background-color: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
            Criar Ações
            </button>
        </div>

        <script>
            function abrirModalAcoes() {
                document.getElementById('modalAcoes').style.display = 'block';
            }

            function fecharModalAcoes() {
                document.getElementById('modalAcoes').style.display = 'none';
            }
        </script>



        <h3>Dados do Convênio</h3>
        <div>
            <label>Número do Convênio</label>
            <input type="text" name="numero_convenio" value="{{ old('numero_convenio', $convenio->numero_convenio) }}">
        </div>
        <div>
            <label>Ano do Convênio</label>
            <input type="text" name="ano_convenio" value="{{ old('ano_convenio', $convenio->ano_convenio) }}">
        </div>
        <div>
            <label>Identificação do Convênio</label>
            <input type="text" name="identificacao" value="{{ old('identificacao', $convenio->identificacao) }}">
        </div>
        <div>
            <label>Objeto</label>
            <input type="text" name="objeto" value="{{ old('objeto', $convenio->objeto) }}">
        </div>

        <hr>
        <h3>Recursos</h3>
        <div class="form-group">
            <label for="fonte_recursos">Fonte de Recursos</label>
            <select name="fonte_recursos" id="fonte_recursos" required>
                <option value="" disabled>Selecione uma fonte</option>
                @foreach ($fontes as $fonte)
                    <option value="{{ $fonte }}" {{ old('fonte_recursos', $convenio->fonte_recursos) == $fonte ? 'selected' : '' }}>{{ $fonte }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="valor_repasse">Valor Repasse</label>
            <input type="text" id="valor_repasse" name="valor_repasse" value="{{ old('valor_repasse', number_format($convenio->valor_repasse, 2, ',', '.')) }}">
        </div>
        <div>
            <label for="valor_contrapartida">Valor Contrapartida</label>
            <input type="text" id="valor_contrapartida" name="valor_contrapartida" value="{{ old('valor_contrapartida', number_format($convenio->valor_contrapartida, 2, ',', '.')) }}">
        </div>
        <div>
            <label for="valor_total">Valor Total</label>
            <input type="text" id="valor_total" name="valor_total" readonly value="{{ old('valor_total', number_format($convenio->valor_total, 2, ',', '.')) }}">
        </div>

        <script>
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

        <div class="form-group">
            <label for="concedente">Concedente</label>
            <select name="concedente" id="concedente" required>
                <option value="" disabled>Selecione um concedente</option>
                @foreach ($concedentes as $concedente)
                    <option value="{{ $concedente }}" {{ old('concedente', $convenio->concedente) == $concedente ? 'selected' : '' }}>{{ $concedente }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="parlamentar">Parlamentar</label>
            <select name="parlamentar" id="parlamentar" required>
                <option value="" disabled>Selecione um parlamentar</option>
                @foreach ($parlamentares as $parlamentar)
                    <option value="{{ $parlamentar }}" {{ old('parlamentar', $convenio->parlamentar) == $parlamentar ? 'selected' : '' }}>{{ $parlamentar }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Conta Vinculada</label>
            <input type="text" name="conta_vinculada" value="{{ old('conta_vinculada', $convenio->conta_vinculada) }}">
        </div>

        <hr>
        <h3>Status</h3>
        <div class="form-group">
            <label for="natureza_despesa">Natureza de Despesa</label>
            <select name="natureza_despesa" id="natureza_despesa" required>
                <option value="" disabled>Selecione a natureza da despesa</option>
                @foreach ($naturezas as $natureza)
                    <option value="{{ $natureza }}" {{ old('natureza_despesa', $convenio->natureza_despesa) == $natureza ? 'selected' : '' }}>{{ $natureza }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Data da Assinatura</label>
            <input type="date" name="data_assinatura" value="{{ old('data_assinatura', $convenio->data_assinatura) }}">
        </div>
        <div>
            <label>Data da Vigência</label>
            <input type="date" name="data_vigencia" value="{{ old('data_vigencia', $convenio->data_vigencia) }}">
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <input type="submit" value="Atualizar Convênio">
        </div>
    </form>
</body>
</html>