<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Convenio;
use Carbon\Carbon;

class ConvenioController extends Controller
{
    public function index()
    {
        $convenios = Convenio::all();

        foreach ($convenios as $convenio) {
            $dataVigencia = Carbon::parse($convenio->data_vigencia);
            $hoje = Carbon::now();

            $convenio->dias_restantes = (int) $hoje->diffInDays($dataVigencia, false);
        }

        return view('convenios.index', compact('convenios'));
    }

    public function login()
    {
        return view('convenios.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            return redirect()->intended(route('convenio.index'));
        }

        return back()->withErrors([
            'username' => 'Usuário ou senha incorretos.',
        ]);
    }

    public function username()
    {
        return 'username';
    }

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

    public function create()
    {
        return view('convenios.create', $this->dadosAuxiliares());
    }

    public function store(Request $request)
    {
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

        $data['valor_total'] = $data['valor_repasse'] + $data['valor_contrapartida'];

        $request->merge([
            'valor_repasse' => str_replace(['.', ','], ['', '.'], $request->valor_repasse),
            'valor_contrapartida' => str_replace(['.', ','], ['', '.'], $request->valor_contrapartida),
        ]);

        $newConvenio = Convenio::create($data);
        return redirect(route('convenio.index'));
    }

    public function edit($id)
    {
        $convenio = Convenio::findOrFail($id);
        return view('convenios.edit', array_merge(
            ['convenio' => $convenio],
            $this->dadosAuxiliares()
        ));
    }

    public function update(Request $request, $id)
    {
        $convenio = Convenio::findOrFail($id);

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
        $request->merge([
            'valor_repasse' => str_replace(['.', ','], ['', '.'], $request->valor_repasse),
            'valor_contrapartida' => str_replace(['.', ','], ['', '.'], $request->valor_contrapartida),
            'valor_total' => str_replace(['.', ','], ['', '.'], $request->valor_total),
        ]);

        $convenio->update($validated);

        return redirect()->route('convenio.index')->with('success', 'Convênio atualizado com sucesso!');
    }
    
    public function destroy($id)
    {
    $convenio = Convenio::findOrFail($id);
    $convenio->delete();

    return redirect()->route('convenio.index')->with('success', 'Convênio excluído com sucesso!');
    }
}
