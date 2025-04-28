<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Convênio</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .logo {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 10px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #bdc3c7;
            padding: 6px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        th {
            background-color: #888888;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        .col-numero {
            width: 8%;
        }
        
        .col-parlamentar {
            width: 12%;
        }
        
        .col-objeto {
            width: 16%;
        }
        
        .col-vigencia {
            width: 7%;
        }
        
        .col-progresso {
            width: 6%;
        }
        
        .col-dados {
            width: 8%;
        }
        
        .col-liberado {
            width: 7%;
        }
        
        .col-repasse {
            width: 7%;
        }
        
        .col-contrapartida {
            width: 9%;
        }
        
        .col-total {
            width: 7%;
        }
        
        .col-situacao {
            width: 11%;
        }

        .bold {
            font-weight: bold;
        }

        .currency {
            text-align: right;
            font-weight: bold;
        }

        .text-break {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .progress-bar {
            background-color: #ecf0f1;
            border-radius: 10px;
            height: 10px;
            width: 100%;
            margin-top: 5px;
        }
        
        .progress {
            background-color: #3498db;
            height: 10px;
            border-radius: 10px;
        }
        
        .label-info {
            display: block;
            margin-bottom: 3px;
            font-weight: bold;
        }
        
        .value-info {
            display: block;
            margin-bottom: 6px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 5px;
        }
        
        .situacao-destaque {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">INFORMAÇÕES DO CONVÊNIO</div>
        <div class="subtitle">Relatório gerado em {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-numero">N° Convênio</th>
                <th class="col-parlamentar">Parlamentar/Concedente</th>
                <th class="col-objeto">Objeto</th>
                <th class="col-vigencia">Vigência</th>
                <th class="col-progresso">Progresso</th>
                <th class="col-dados">Dados Bancários</th>
                <th class="col-liberado">Liberado</th>
                <th class="col-repasse">Repasse</th>
                <th class="col-contrapartida">Contrapartida</th>
                <th class="col-total">Total</th>
                <th class="col-situacao">Situação</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- N° Convênio -->
                <td>
                    <span class="label-info">Nº do Convênio</span>
                    <span class="value-info">{{ $convenio->numero_convenio }}</span>
                    <span class="label-info">Identificação</span>
                    <span class="value-info">{{ $convenio->identificacao }}</span>
                </td>

                <!-- Parlamentar/Concedente -->
                <td>
                    <span class="label-info">Parlamentar</span>
                    <span class="value-info">{{ $convenio->parlamentar }}</span>
                    <span class="label-info">Concedente</span>
                    <span class="value-info">{{ $convenio->concedente }}</span>
                </td>

                <!-- Objeto -->
                <td class="text-break">
                    <span>{{ $convenio->objeto }}</span>
                </td>

                <!-- Vigência -->
                <td>
                    <span class="value-info">{{ \Carbon\Carbon::parse($convenio->data_vigencia)->format('d/m/Y') }}</span>
                </td>

                <!-- Progresso -->
                <td>
                    @php $acompanhamento = $convenio->acompanhamentos->first(); @endphp
                    <span class="value-info bold">{{ $acompanhamento->porcentagem_conclusao }}%</span>
                    <div class="progress-bar">
                        <div class="progress" style="width: {{ $acompanhamento->porcentagem_conclusao }}%"></div>
                    </div>
                </td>

                <!-- Dados Bancários -->
                <td class="text-break">
                    <span class="value-info">{{ $convenio->conta_vinculada }}</span>
                </td>
                
                <!-- Liberado -->
                <td class="currency">
                    R$ {{ number_format($acompanhamento->valor_liberado, 2, ',', '.') }}
                </td>
                
                <!-- Repasse -->
                <td class="currency">
                    R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}
                </td>
                
                <!-- Contrapartida -->
                <td class="currency">
                    R$ {{ number_format($convenio->valor_contrapartida, 2, ',', '.') }}
                </td>
                
                <!-- Total -->
                <td class="currency">
                    R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}
                </td>
                
                <!-- Situação -->
                <td class="text-break">
                    <span class="situacao-destaque">{{ $acompanhamento->situacao }}</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        Documento gerado automaticamente pelo Sistema de Gestão de Convênios - {{ date('d/m/Y') }}
    </div>
</body>
</html>