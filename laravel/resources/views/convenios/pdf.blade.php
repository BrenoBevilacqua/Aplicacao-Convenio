<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Convênio</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 10px;
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        table-layout: fixed;
    }
    th, td {
        border: 1px solid #bdc3c7;
        padding: 6px 4px;
        vertical-align: top;
        word-break: break-word;
        hyphens: auto;
    }
    th {
        background: #888;
        color: #fff;
        font-weight: bold;
        text-align: center;
        font-size: 10px;
    }
    .col-numero         { width: 8%;  }
    .col-parlamentar    { width: 13%; }
    .col-objeto         { width: 17%; }
    .col-vigencia       { width: 8%;  text-align: center; }
    .col-progresso      { width: 10%; text-align: center; }
    .col-dados          { width: 10%; }
    .col-liberado       { width: 8%;  text-align: center; }
    .col-repasse        { width: 8%;  text-align: center; }
    .col-contrapartida  { width: 8%;  text-align: center; }
    .col-total          { width: 7%;  text-align: center; }
    .col-situacao       { width: 7%;  text-align: center; }
    .label-info {
        font-weight: bold;
        display: block;
        margin-bottom: 2px;
    }
    .value-info {
        display: block;
        margin-bottom: 4px;
    }
    .currency {
        text-align: right;
        font-weight: bold;
    }
    .text-break {
        white-space: normal;
        word-break: break-word;
        hyphens: auto;
        text-align: left;
    }
    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 9px;
        color: #888;
        border-top: 1px solid #bdc3c7;
        padding-top: 6px;
    }
    .situacao-destaque {
        font-weight: bold;
    }
    .progress-bar {
        background: #ecf0f1;
        border-radius: 10px;
        height: 10px;
        width: 100%;
        margin-top: 4px;
    }
    .progress {
        background: #3498db;
        height: 10px;
        border-radius: 10px;
    }
</style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="{{ public_path('img/Brasao_Jardim.png') }}" alt="Brasão Jardim" style="height: 90px;">
        <h1>Estado de Mato Grosso do Sul</h1>
        <h1>Município de Jardim</h1>
    </div>
    <h1></h1>
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
                <td class="text-break">
                    @php $acompanhamento = $convenio->acompanhamentos->first(); @endphp
                    <span class="value-info bold">
                        {{ $acompanhamento->porcentagem_conclusao ?? 'Não específicado' }}
                        @if(isset($acompanhamento->porcentagem_conclusao))%
                        @endif
                    </span>
                    @if(isset($acompanhamento->porcentagem_conclusao))
                    <div class="progress-bar">
                    <div class="progress" style="width: {{ $acompanhamento->porcentagem_conclusao }}%"></div>
                    </div>
                    @endif
                </td>

                <!-- Dados Bancários -->
                <td class="text-break">
                    <span class="value-info">{{ $convenio->conta_vinculada }}</span>
                </td>
                
                
                <!-- Liberado -->
                <td class="currency">
                    @if(isset($acompanhamento) && isset($acompanhamento->valor_liberado))
                        R$ {{ number_format($acompanhamento->valor_liberado, 2, ',', '.') }}
                    @else
                        Não específicado
                    @endif
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
                    @if(isset($acompanhamento) && isset($acompanhamento->situacao))
                        <span class="situacao-destaque">{{ $acompanhamento->situacao }}</span>
                    @else
                        <span class="situacao-destaque">Não específicado</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        Documento gerado automaticamente pelo Sistema de Gestão de Convênios - {{ date('d/m/Y') }}
    </div>
</body>
</html>