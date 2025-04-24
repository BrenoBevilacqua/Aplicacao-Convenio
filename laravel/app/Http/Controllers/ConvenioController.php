<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Convenio;
use App\Models\Contrato;
use App\Models\LogHistorico;
use App\Models\Acao;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

// Funções relacionadas a convênios

class ConvenioController extends Controller
{
    // pagina inicial com tabelas
    public function index()
    {
        $convenios = Convenio::with(['acompanhamentos' => fn($query) => $query->latest()->limit(1),
        'contratos' 
            //$query->latest()->limit(1); // Pega o acompanhamento mais recente
        ])->paginate(3);

        foreach ($convenios as $convenio) {
            $dataVigencia = Carbon::parse($convenio->data_vigencia);
            $hoje = Carbon::now();
            $convenio->dias_restantes = (int) $hoje->diffInDays($dataVigencia, false);
        }

        return view('convenios.index', compact('convenios'));
    }   


    public function username()
    {
        return 'username';
    }

    // array de dados auxiliares
    private function dadosAuxiliares()
    {
        return [
            'fontes' => [
                'AVANÇAR CIDADES',
                'EMENDAS 2025',
                'FINISA - Financiamento à Infraestrutura e ao Saneamento',
                'Governo do Estado',
                'Governo Federal',
                'PAC 2025',
                'RECURSO PRÓPRIO',
                'SETOR PRIVADO',
            ],
            'concedentes' => [
                'AGEHAB - Agência de Habitação Popular do Estado de Mato Grosso do Sul',
                'AGESUL/ SEINFRA/ SEILOG',
                'AGRAER - Agência de Desenvolvimento Ágrario e Extensão Rural',
                'CAIXA ECONÔMICA FEDERAL',
                'Detran - Dep. Estadual de Trânsito MS',
                'FCMS - Fundação de Cultura de Mato Grosso do Sul',
                'FUNASA - Fundação Nacional da Saúde',
                'FUNDESPORTE - Fundação de Desporto e Lazer de MS',
                'ITAIPU',
                'MAPA / MDA / INCRA(Agricultura)',
                'MCOM - Ministério das Comunicações',
                'MDR (Ministério das Cidades(Infraestrutura)',
                'MDS (Ministério da Cidadania)',
                'ME - MINISTERIO DA ECONOMIA / FAZENDA',
                'MEC / FNDE - Ministério da Educação',
                'MIDR - MINISTÉRIO DA INTEGRAÇÃO E DO DESENVOLVIMENTO REGIONAL',
                'MINC - Ministério da Cultura',
                'Ministério da Defesa',
                'Ministério da Saúde / FNS',
            ],
            'parlamentares' => [
                'Bancada do Mato Grosso do Sul',
                'Com.Mista,Plan.Orç e Fiscalização',
                'Dep Est Felipe Orro',
                'Dep Estadual - ANTONIO VAZ',
                'Dep Estadual - CORONEL DAVID',
                'Dep Estadual - Gerson Claro',
                'Dep Estadual - Gleice Jane',
                'Dep Estadual - João Henrique',
                'Dep Estadual - Junior Mochi',
                'Dep Estadual - Lia Nogueira',
                'Dep Estadual - MARCIO FERNANDES',
                'Dep Estadual - Onevan de Matos',
                'Dep Estadual - Pedro Kemp',
                'Dep Estadual -Pedrossian Neto',
                'Dep Estadual - Rinaldo Modesto',
                'Dep Estadual - Zé Teixeira',
                'Dep Estadual - Roberto Hashioka',
                'Dep Fed - Mandetta',
                'Dep Federal Camila Jara',
                'Dep Federal Marcos Pollon',
            ],
            'naturezas' => [
                'Custeio',
                'Equipamentos e Material Permanente',
                'Obras e instalações',
            ],
        ];
    }

    // rota para create
    public function create()
    {
        return view('convenios.create', $this->dadosAuxiliares());
    }

    // criar convenio
    public function store(Request $request)
    {
    // Validação dos dados
        $data = $request->validate([
            'numero_convenio' => 'required|numeric',
            'ano_convenio' => 'required|numeric',
            'identificacao' => 'required|string',
            'objeto' => 'required|string',
            'fonte_recursos' => 'required|string',
            'valor_repasse'=> 'required|numeric',
            'valor_contrapartida' => 'required|numeric',
            'concedente' => 'required|string',
            'parlamentar' => 'required|string',
            'conta_vinculada' => 'required|string',
            'natureza_despesa' => 'required|string',
            'data_assinatura' => 'required|date',
            'data_vigencia'=> 'required|date',
        ]);

    // Calculando o valor total
    $data['valor_total'] = $data['valor_repasse'] + $data['valor_contrapartida'];

    // Formatação dos valores monetários
    $request->merge([
        'valor_repasse' => str_replace(['.', ','], ['', '.'], $request->valor_repasse),
        'valor_contrapartida' => str_replace(['.', ','], ['', '.'], $request->valor_contrapartida),
    ]);

    // Criando o novo convênio
    $newConvenio = Convenio::create($data);

    // Registrando o histórico de criação
    LogHistorico::create([
        'user_id' => auth()->id(),
        'acao' => 'Criação',
        'numero_convenio' => $newConvenio->numero_convenio,
        'ano_convenio' => $newConvenio->ano_convenio,
        'data_modificacao' => now(),
    ]);

    // Redirecionando para a página de index + funções da página index
    return redirect(route('convenio.index'));
    }

        public function edit($id)
        {
        $convenio = Convenio::with('acoes')->findOrFail($id);

        return view('convenios.edit', array_merge(
            ['convenio' => $convenio],
            $this->dadosAuxiliares()
        ));
        }
        // editar convenio
        public function update(Request $request, $id)
        {
            $convenio = Convenio::findOrFail($id);

            $request->merge([
                'valor_repasse' => str_replace(['.', ','], ['', '.'], $request->valor_repasse),
                'valor_contrapartida' => str_replace(['.', ','], ['', '.'], $request->valor_contrapartida),
                'valor_total' => str_replace(['.', ','], ['', '.'], $request->valor_total),
            ]);
        
            $validated = $request->validate([
                'numero_convenio' => 'required',
                'ano_convenio' => 'required',
                'identificacao' => 'required',
                'conta_vinculada' => 'nullable',
                'objeto' => 'nullable',
                'fonte_recursos' => 'nullable',
                'concedente' => 'nullable',
                'parlamentar' => 'nullable',
                'natureza_despesa' => 'nullable',
                'valor_repasse' => 'nullable|numeric',
                'valor_total' => 'nullable|numeric',
                'data_assinatura' => 'nullable|date',
                'data_vigencia' => 'nullable|date',
            ]);

            LogHistorico::create([
                'user_id' => auth()->id(),
                'acao' => 'Edição',
                'numero_convenio' => $convenio->numero_convenio,
                'ano_convenio' => $convenio->ano_convenio,
                'data_modificacao' => now(),
            ]);

            $convenio->update($validated);

        return redirect()->route('convenio.index')->with('success', 'Convênio atualizado com sucesso!');
    }
    
    // deletar convenio
    public function destroy($convenioId, $acaoId = null)
    {
        if ($acaoId) {
            $acao = Acao::where('convenio_id', $convenioId)->where('id', $acaoId)->first();

            if (!$acao) {
                return response()->json(['sucesso' => false, 'mensagem' => 'Ação não encontrada.'], 404);
            }

            $acao->delete();
            return response()->json(['sucesso' => true]);
        }
   
        $convenio = Convenio::findOrFail($convenioId);

        LogHistorico::create([
            'user_id' => auth()->id(),
            'acao' => 'Exclusão',
            'numero_convenio' => $convenio->numero_convenio,
            'ano_convenio' => $convenio->ano_convenio,
            'data_modificacao' => now(),
        ]);

        $convenio->delete();

        return redirect()->route('convenio.index')->with('success', 'Convênio excluído com sucesso.');
    }

    // guardar ações
    public function storeAcao(Request $request, $id)
    {
        try {
            $request->validate([
                'tipo' => 'required|in:concedente,convenente',
                'observacao' => 'required|string',
                'data_edicao' => 'required|date',
            ]);
    
            $acao = new Acao();
            $acao->convenio_id = $id;
            $acao->tipo = $request->tipo;
            $acao->observacao = $request->observacao;
            $acao->data_edicao = $request->data_edicao;
            $acao->save();
    
            return response()->json([
                'sucesso' => true,
                'acao' => [
                    'id' => $acao->id,
                    'tipo' => $acao->tipo,
                    'data_edicao_formatada' => Carbon::parse($acao->data_edicao)->format('d/m/Y'),
                    'observacao' => $acao->observacao,
                    'convenio_id' => $acao->convenio_id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao salvar ação: ' . $e->getMessage()
            ], 500);
        }
    }

    // historico de modificações
    public function historico()
    {
        $logs = LogHistorico::with('user')->orderBy('data_modificacao', 'desc')->get(); // Obtem os logs de forma ordenada
        //dd($logs);
        return view('convenios.info', compact('logs'));
    }
   
    // exportar para pdf
    public function exportarPdf($id)
    {
        $convenio = Convenio::findOrFail($id);
        $pdf = Pdf::loadView('convenios.pdf', compact('convenio'));
        return $pdf->download("convenio_{$convenio->id}.pdf");
    }
    
    // guardar acompanhamento
    public function storeAcompanhamento(Request $request, Convenio $convenio)
    {
        //\Log::info('Recebido status: ' . $request->status); // Log de status
        //\Log::info('Recebido monitorado: ' . $request->monitorado);
    
        $request->validate([
            'status' => 'required|in:em_execucao,finalizado,cancelado',
            'monitorado' => 'required|boolean',
        ]);
    
        $acompanhamento = $convenio->acompanhamentos()->create([
            'status' => $request->status,
            'monitorado' => $request->monitorado == '1',
        ]);
        //dd($acompanhamento->toArray());
    
        return response()->json([
            'sucesso' => true,
            'acompanhamento' => [
                'status' => $acompanhamento->status,
                'status_formatado' => ucfirst(str_replace('_', ' ', $acompanhamento->status)),
                'monitorado' => $acompanhamento->monitorado,
                'data_formatada' => $acompanhamento->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }

    // guardar Contrato
    public function storeContrato(Request $request, $convenioId)
    {
        $request->validate([
            'numero_contrato' => 'required|string',
            'objeto' => 'required|string',
            'empresa_contratada' => 'required|string',
            'valor' => 'required|numeric',
            'data_assinatura' => 'required|date',
            'validade_inicio' => 'required|date',
            'validade_fim' => 'required|date|after_or_equal:validade_inicio',
        ]);

        $contrato = Contrato::create([
            'convenio_id' => $convenioId,
            'numero_contrato' => $request->numero_contrato,
            'objeto' => $request->objeto,
            'empresa_contratada' => $request->empresa_contratada,
            'valor' => $request->valor,
            'data_assinatura' => $request->data_assinatura,
            'validade_inicio' => $request->validade_inicio,
            'validade_fim' => $request->validade_fim,
        ]);

        return response()->json([
            'sucesso' => true,
            'contrato' => $contrato
        ]);
    }

    // deletar contrato
    public function destroyContrato($convenioId, $contratoId)
    {
        $contrato = Contrato::where('convenio_id', $convenioId)->findOrFail($contratoId);
        $contrato->delete();

        return response()->json(['sucesso' => true]);
    }

    

    
   
}

