<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convênio PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }
        .title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="title">Informações do Convênio</div>

    <table>
        <thead>
            <tr>
                <th>Dados Gerais</th>
                <th>Recursos / Concedentes / Parlamentar</th>
                <th>Finalidade / Detalhamento</th>
                <th>Valores / Datas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Dados Gerais -->
                <td>
                    <strong>ID:</strong> {{ $convenio->id }}<br>
                    <strong>Nº / Ano:</strong> {{ $convenio->numero_convenio }} / {{ $convenio->ano_convenio }}<br>
                    <strong>Identificação:</strong> {{ $convenio->identificacao }}<br>
                    <strong>C/C:</strong> {{ $convenio->conta_vinculada }}<br>
                    <strong>Contratos:</strong> --<br>
                    <strong>Objeto:</strong> {{ $convenio->objeto }}
                </td>

                <!-- Recursos / Concedentes / Parlamentar -->
                <td>
                    <strong>Fonte de recurso:</strong> {{ $convenio->fonte_recursos }}<br>
                    <strong>Concedente:</strong> {{ $convenio->concedente }}<br>
                    <strong>Parlamentar:</strong> {{ $convenio->parlamentar }}
                </td>

                <!-- Finalidade / Detalhamento -->
                <td>
                    <strong>Finalidade:</strong> {{ $convenio->natureza_despesa }}<br>
                    <strong>Detalhamento:</strong> --
                </td>

                <!-- Valores / Datas -->
                <td>
                    <strong>Repasse:</strong> R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}<br>
                    <strong>Valor Total:</strong> R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}<br>
                    <strong>Valor Liberado:</strong> R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}<br>
                    <strong>Assinatura:</strong> {{ \Carbon\Carbon::parse($convenio->data_assinatura)->format('d/m/Y') }}<br>
                    <strong>Vigência:</strong> {{ \Carbon\Carbon::parse($convenio->data_vigencia)->format('d/m/Y') }}<br>
                    <strong>Dias / Vencimento:</strong> 
                    {{ $convenio->dias_restantes >= 0 ? $convenio->dias_restantes . ' dia(s) restantes' : 'Vencido há ' . abs($convenio->dias_restantes) . ' dias atrás' }}
                </td>

                <!-- Status -->
                <td>
                    @php
                        $acompanhamento = $convenio->acompanhamentos->first();
                    @endphp

                    @if($acompanhamento)
                        <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $acompanhamento->status)) }}<br>
                        <strong>Monitoramento:</strong> 
                        @if($acompanhamento->monitorado)
                            Monitorado
                        @else
                            Não Monitorado
                        @endif
                    @else
                        <strong>Status:</strong> Não informado<br>
                        <strong>Monitoramento:</strong> Não informado
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
