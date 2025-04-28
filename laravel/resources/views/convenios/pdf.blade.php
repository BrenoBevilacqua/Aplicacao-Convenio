<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convênio PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #444;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="title">Informações do Convênio</div>

    <table>
        <thead>
            <tr>
                <th>N° Convênio</th>
                <th>Parlamentar/Concedente</th>
                <th>Objeto</th>
                <th>Vigência</th>
                <th>Progresso</th>
                <th>Dados Bancários</th>
                <th>Liberado</th>
                <th>Repasse</th>
                <th>Contrapartida</th>
                <th>Total</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- N° Convênio -->
                <td>
                    
                    <div><strong>Nº do Convênio:</strong> {{ $convenio->numero_convenio }}</div>
                    <div><strong>Identificação:</strong> {{ $convenio->identificacao }}</div>
                    
                </td>

                <!-- Parlamentar/Concedente -->
                <td>
                    <div><strong>Parlamentar:</strong> {{ $convenio->parlamentar }}</div>
                    <div><strong>Concedente:</strong> {{ $convenio->concedente }}</div>
                </td>

                <!-- Objeto -->
                <td>
                    <div>{{ $convenio->objeto }}</div>
                </td>

                <!-- Vigência -->
                <td>
                    <div><strong>Repasse:</strong> R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}</div>
                    <div><strong>Valor Total:</strong> R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}</div>
                    <div><strong>Valor Liberado:</strong> R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}</div>
                    <div><strong>Assinatura:</strong> {{ \Carbon\Carbon::parse($convenio->data_assinatura)->format('d/m/Y') }}</div>
                    <div><strong>Vigência:</strong> {{ \Carbon\Carbon::parse($convenio->data_vigencia)->format('d/m/Y') }}</div>
                    <div><strong>Dias / Vencimento:</strong>
                        {{ $convenio->dias_restantes >= 0 ? $convenio->dias_restantes . ' dia(s) restantes' : 'Vencido há ' . abs($convenio->dias_restantes) . ' dias atrás' }}
                    </div>
                </td>

                <!-- Progresso -->
                <td>
                    @php $acompanhamento = $convenio->acompanhamentos->first(); @endphp
                    <div>{{ $acompanhamento->porcentagem_conclusao }}% concluído</div>
                </td>

                <!-- Dados Bancários -->
                <td>
                    <div>{{ $convenio->conta_vinculada }}</div>
                </td>
                
                <!-- Liberado -->
                <td>
                    <div>R$ {{ number_format($acompanhamento->valor_liberado, 2, ',', '.') }}</div>
                </td>
                
                <!-- Repasse -->
                <td>
                    <div>R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}</div>
                </td>
                
                <!-- Contrapartida -->
                <td>
                    <div>R$ {{ number_format($convenio->valor_contrapartida, 2, ',', '.') }}</div>
                </td>
                
                <!-- Total -->
                <td>
                    <div>R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}</div>
                </td>
                <!-- Situação -->
                <td>
                    <div>---</div>
                </td>

            </tr>
        </tbody>
    </table>

</body>
</html>
