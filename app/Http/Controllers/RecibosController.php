<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\RecibosCreateRequest;
use App\Http\Requests\RecibosUpdateRequest;
use App\Repositories\AdminRepository;
use App\Repositories\RecibosRepository;
use App\Repositories\EmpresaRepository;
use App\Repositories\ServicosRepository;
use App\Repositories\UserRepository;
use App\Validators\RecibosValidator;

/**
 * Class RecibosController.
 *
 * @package namespace App\Http\Controllers;
 */
class RecibosController extends Controller
{
    /**
     * @var RecibosRepository
     */
    protected $repository;

    /**
     * @var RecibosValidator
     */
    protected $validator;
    protected $user;
    protected $servico;
    protected $empresa;
    protected $admin;

    /**
     * RecibosController constructor.
     *
     * @param RecibosRepository $repository
     * @param RecibosValidator $validator
     */
    public function __construct(RecibosRepository $repository, RecibosValidator $validator, UserRepository $user, ServicosRepository $servico, EmpresaRepository $empresa, AdminRepository $admin)
    {
        $this->middleware('auth');
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->user  = $user;
        $this->servico  = $servico;
        $this->empresa  = $empresa;
        $this->admin  = $admin;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function novo()
    {

        $recibos = $this->repository->all();
        $result = [];
        $result['title'] = "Recibos | " . env("APP_NAME");
        $result['link_header'] = route("recibos.index");
        $result['recibos'] = $recibos;
        $result['btnactive'] = "recibo";
        return view('recibos.novo', $result);
    }
    public function index()
    {
        $recibos = $this->repository->orderBy("data", "asc")->get();
        $result = [];
        $result['title'] = "Recibos | " . env("APP_NAME");
        $result['recibos'] = $recibos;
        for ($i = 0; $i < count($recibos); $i++) {
            $user = $this->user->find($recibos[$i]["colaborador_id"]);
            $result['recibos'][$i]['user'] = isset($user) ? $user : null;
        }
        $result['btnactive'] = "relatorio";
        return view('recibos.index', $result);



        if (!$user = $this->user->find(1)) {
            return redirect()->back();
        }
        return $user->recibos()->get();

        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $recibos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $recibos,
            ]);
        }
        return compact('recibos');
        return view('recibos.index', compact('recibos'));
    }
    public function search(Request $request)
    {
        $result = [];
        $name = $request->name;
        $este = $this->user->findWhere([["name", "LIKE", "%{$name}%"]]);
        for ($i = 0; $i < count($este); $i++) {
            $result[$i]['name'] = $este[$i]['name'];
            $result[$i]['cpf'] = $este[$i]['cpf'];
        }
        return $result;
    }
    public function searchServicos(Request $request)
    {
        $result = [];
        $name = $request->servico;
        $este = $this->servico->findWhere([["name", "LIKE", "%{$name}%"]]);
        for ($i = 0; $i < count($este); $i++) {
            $result[$i]['name'] = $este[$i]['name'];
            $result[$i]['valor'] = number_format($este[$i]['valor'], 2, ",", ".");
            $result[$i]['descricao'] = $este[$i]['descricao'];
        }
        return $result;
    }
    public function editar($id)
    {
        $recibos = $this->repository->find($id);
        if ($recibos) {
            $result = [];
            $result['title'] = "Recibos | " . env("APP_NAME");
            $result['link_header'] = route("recibos.index");
            $result['recibos'] = $recibos;
            $result['btnactive'] = "recibo";
            $result['recibos']['valor'] = number_format($recibos['valor'], 2, ",", ".");
            $result['recibos']['data'] = date('d/m/Y', strtotime($recibos['data']));
            return view('recibos.novo', $result);
        }
    }
    public function searchRecibosAll(Request $request)
    {

        $result = [];
        $result['datastart'] = $request->data_start;
        $result['dataend'] = $request->data_end;
        $request->pesquisarpor = !isset($request->pesquisarpor) ? "all" : $request->pesquisarpor;
        $request->texto = !isset($request->texto) ? "" : $request->texto;
        $find = [];
        if (isset($result['datastart']) && isset($result['dataend'])) {
            $request->data_start = $this->dataBRtoEUA($request->data_start);
            $request->data_end = $this->dataBRtoEUA($request->data_end);
            $find = true;
        }

        $result['recibos'] = [];
        if ($request->pesquisarpor == "colaborador" || $request->pesquisarpor == "all") {
            $user = $this->user->where([["name", "LIKE", "%" . $request->texto . "%"]])->orderBy('name', 'asc')->get();
            for ($i = 0; $i < count($user); $i++) {
                $rec = $this->repository->where(["colaborador_id" => $user[$i]["id"]])->get();
                if ($find) {
                    $rec = $this->repository->where(["colaborador_id" => $user[$i]["id"], ["data", ">=", $request->data_start], ["data", "<=", $request->data_end]])->get();
                }
                for ($a = 0; $a < count($rec); $a++) {
                    if ($rec[$a]) {
                        $result['recibos'][] = $rec[$a];
                    }
                }
            }
        }
        if ($request->pesquisarpor == "servico" || $request->pesquisarpor == "all") {
            $servico = $this->servico->where([["name", "LIKE", "%" . $request->texto . "%"]])->orderBy('name', 'asc')->get();

            for ($i = 0; $i < count($servico); $i++) {
                $rec = $this->repository->where(["servico" => $servico[$i]["name"]])->get();
                if ($find) {
                    $rec = $this->repository->where(["servico" => $servico[$i]["name"], ["data", ">=", $request->data_start], ["data", "<=", $request->data_end]])->get();
                }
                for ($a = 0; $a < count($rec); $a++) {
                    if ($rec[$a]) {
                        $tem = false;
                        for ($c = 0; $c < count($result['recibos']); $c++) {
                            if ($result["recibos"][$c]['id'] == $rec[$a]["id"]) {

                                $tem = true;
                            }
                        }
                        if (!$tem) {
                            $result['recibos'][] = $rec[$a];
                        }
                    }
                }
            }
        }
        $result['pesquisarpor'] = $request->pesquisarpor;
        $result['texto'] = $request->texto;
        $recibos = $result['recibos'];
        $result['title'] = "Recibos | " . env("APP_NAME");
        $result['recibos'] = $recibos;
        for ($i = 0; $i < count($recibos); $i++) {
            $user = $this->user->find($recibos[$i]["colaborador_id"]);
            $result['recibos'][$i]['user'] = isset($user) ? $user : null;
        }
        $result['btnactive'] = "relatorio";
        return view('recibos.index', $result);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  RecibosCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(RecibosCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $recibo = $this->repository->create($request->all());

            $response = [
                'message' => 'Recibos created.',
                'data'    => $recibo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recibo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $recibo,
            ]);
        }

        return view('recibos.show', compact('recibo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recibo = $this->repository->find($id);

        return view('recibos.edit', compact('recibo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RecibosUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(RecibosUpdateRequest $request)
    {
        try {
            $formato = isset($request["formato"]) ? $request["formato"] : "pdf";
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $recibos = [];
            $recibos['id'] = isset($request->id) ? $request->id : null;


            $empresa = $this->empresa->firstOrCreate(["cnpj" => "32325767000187"]);


            $user = [];
            $user["cpf"] = $request["cpf"];
            $user["name"] = $request["name"];

            $data = isset($request->data) ? explode("/", $request->data) : null;

            $recibos["servico"] = $request["servico"];
            $recibos["valor"] = floatval($request["valor"]);
            $recibos["descricao"] = $request["descricao"];
            $recibos["data"] = date("Y-m-d", strtotime(isset($data) ? $data[2] . "-" . $data[1] . "-" . $data[0] : date("Y-m-d")));

            $recibos['colaborador_id'] = $this->user->firstOrCreate($user)->id;
            $recibos['admin_id'] = \Auth::user()->id;
            $recibos['empresa_id'] = $empresa->id;
            $serv = $this->servico->firstOrCreate(["name" => $recibos["servico"]]);
            $servico = [];
            $servico["servico"] = $recibos["servico"];
            $servico["valor"] = $recibos["valor"];
            $servico["descricao"] = $recibos["descricao"];

            if (!$serv) {
                $this->servico->create($servico);
            } else {
                $this->servico->update($servico, $serv->id);
            }
            $recibos['last_edit'] = \Auth::user()->id;
            if ($recibos['id']) {
                $recibo = $this->repository->update($recibos, $recibos['id']);
            } else {
                $recibo = $this->repository->create($recibos);
            }
            $response = [
                'message' => 'Salvo com sucesso!',
                'data'    => $recibo->toArray(),
            ];

            if ($request->wantsJson()) {
                return response()->json($response);
            }
            //return $this->editar($recibo->id);
            $retorno = [];
            $retorno["message"] = $response['message'];
            $retorno["recibos"] = $recibo;
            return redirect()->back()->with($retorno)->withInput();
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Recibos deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Recibos deleted.');
    }
    public function GerarReciboHtml($id)
    {
        $recibos = $this->repository->find($id);

        $empresa = $this->empresa->find($recibos->empresa_id);
        $user = $this->user->find($recibos->colaborador_id);

        $donoPref = json_decode($empresa["endereco"], true);

        $complemento = !empty($donoPref['complemento']) ? ", " . $donoPref['complemento'] : "";
        $bairro = !empty($donoPref['bairro']) ? " - Bairro: " . $donoPref['bairro']  : "";
        $cidade = !empty($donoPref['cidade']) ? ", Cidade de " . $donoPref['cidade'] . " / " . $donoPref['estado']  : "";
        $numero = !empty($donoPref['num']) ? ", Nº.: " . $donoPref['num'] : "";
        $cep = !empty($donoPref['cep']) ? ", CEP: " . $donoPref['cep'] : "";
        $rua = $donoPref['rua'];
        $endereco = $rua . $numero . $complemento . $bairro . $cidade . $cep;
        $empresa["cnpj"] = $this->getCNPJ($empresa["cnpj"]);
        $user["cpf"] = $this->getCPF($user["cpf"]);

        $family = 'Arial';
        $linha1 = '<div style="width:60%;float:left;">Nome Fantasia: ' . $empresa["nome_fantasia"] . '</div>'
            . '<div style="width:40%;float:right;text-align:right;">CNPJ: ' . $empresa["cnpj"] . '</div>'
            . '<div style="width:100%;float:left;text-align:left;">Razão Social: ' . $empresa["razao_social"] . '</div>';
        $linha2 = '<div style="width:60%;float:left;text-align:left;">Email: ' . $empresa["email"] . '</div>'
            . '<div style="width:40%;float:right;text-align:right;">Insc. Est.: ' . $empresa["inscricao_estadual"] . '</div>';
        $linha3 = '<div style="width:40%;float:left;">' . $empresa["telefone"] . '</div>';
        $linha4 = '<div style="width:60%;float:left;">Endereço: ' . $donoPref["rua"] . ', ' . $donoPref["num"] . '</div>'
            . '<div style="width:40%;float:right;text-align:right;">Bairro: ' . $donoPref["bairro"] . '</div>';
        $linha5 = '<div style="width:40%;float:left;">Cidade: ' . $donoPref["cidade"] . ' / ' . $donoPref["estado"] . '</div>'
            . '<div style="width:60%;float:right;text-align:right;">CEP: ' . $donoPref["cep"] . '</div>';
        $cabecalho = '<div style="width:100%; font-size:9px; font-weight:200; font-family:Arial;">' . $linha1 . $linha2 . $linha3 . $linha4 . $linha5 . '</div>';

        $logo = '<div style="width: 80%; height: 100px;margin: 20px 0px 0px 20px;"><img style="width: 120px; height: 120px; margin:0px;" src="' . asset("images/logo-pd.png") . '" /></div>';

        $lateralLogo = '<div style="width: 88%; height: 90px;border:1px solid #333;margin-top: 20px; padding: 10px;line-height:17px;">' . $cabecalho . '</div>';
        $top = '<div style="width:100%; margin-top: 25px;"><div style="width:25%; height: 100px; float:left; margin:0px;">' . $logo . '</div><div style="width:72%; height: 100px; float:right; margin:0px; text-align: left;">' . $lateralLogo . '</div></div>';

        $tituloRecibo = '<div style="width:100%;margin: 25px 0px; font-size: 16px; font-weight: 500; text-align: center;">Recibo de Pagamento</div>';

        $bene = '<div style="width:100%; text-align: justify; padding-left: 50px;padding-right: 50px;"><div style="width:100%; float: left; font-size: 13px; font-weight: 200;">Eu, <strong>' . $user["name"] . '</strong>, portador(a) do CPF nº: <strong>' . $user["cpf"] . '</strong>, declaro ter recebido de ' . $empresa["nome_fantasia"] . ' [' . $empresa["razao_social"] . '], ' . $endereco . '.</div></div>';
        $dataexe = isset($recibos["data"]) ? ucfirst($this->dataExtenso($recibos["data"])) : NULL;
        $valorRS = "R$ " . number_format($recibos['valor'], 2, ".", ".");
        $valorExtenso = trim($this->valorExtenso($recibos['valor']));
        $servDesc = $recibos['servico'];

        $dt_exec = isset($dataexe) ? '<br/><div style="width:100%; text-align: left; padding-left: 50px; margin-top: 2px;"><div style="width:100%; float: left; font-size: 13px;"><strong>Data execução:</strong> ' . $dataexe . '</div></div>' : '';
        $valExtenso = '<div style="width:100%; text-align: left; padding-left: 50px; margin-top: 2px;"><div style="width:100%; float: left; font-size: 13px;"><strong>Valor do Pagamento:</strong> ' . $valorRS . ' (' . $valorExtenso . ')</div></div>';
        $servicoRef = '<div style="width:100%; text-align: left; padding-left: 50px; margin-top: 2px;"><div style="width:100%; float: left; font-size: 13px; font-weight: 200; padding-right:50px; text-align: justify;"><strong>Referente a Prestação de Serviço como:</strong> <span>' . $servDesc . '</span></div></div>';


        $dataass = $this->dataExtenso(date("Y/m/d"));

        $linha =        "<div style='width: 100%;padding-top: 4%;'>_____________________________________</div>";
        $assmorador =      "<div style='width: 100%;'>" . $user['name'] . "</div>";
        $assmoradorcpf =      "<div style='width: 100%;'>CPF nº.: " . $user['cpf'] . "</div>";

        $ass1 = "<div style='text-align:center;width:100%; font-weight: 200;margin-bottom: 20px;'>" . $linha . $assmorador . $assmoradorcpf . "</div>";
        $assinaturas = '<div style="width: 100%;">' . $ass1 . '</div>';
        $dataecidade = "<div style='width: 100%; padding-right:50px; float:left; font-weight: 200;text-align:justify;'><div style='width:100%; float: left; font-size: 13px; font-weight: 200;'>Para clareza firmamos o presente na cidade de Ribeirão Preto no dia de " . $dataass . ".</div></div>";
        $rodape = '<div style="width:100%; font-size: 10px; margin-top: 15px; padding-bottom: 20px; text-align: center;">Quintal Niger - ' . date("Y") . ' - Recibo cód.: ' . $recibos['id'] . '</div>';
        $blocoAss = '<br/><div style="width:100%; text-align: left; padding-left: 50px; margin-top: 25px; text-align: center;font-size: 13px;">' . $dataecidade . $assinaturas . $rodape . '</div>';

        $conteudoRecibo = '<div style="border:1px solid #ccc; height: 90%; width:90%;margin:5%; line-height: 20px; font-family: ' . $family . ';">' . $top . $tituloRecibo . $bene . $dt_exec . $valExtenso . $servicoRef . $blocoAss   . '</div>';

        $reciboFULL = '<div style="background:url(' . asset("images/bg-recibo.png") . ') no-repeat; background-image-resolution:300dpi;background-image-resize:5;float:left;  width: 100%; height: 100%; margin: 0px; padding: 0px; font-family:' . $family . '; text-align:center; border: 1px solid #ccc; ">' . $conteudoRecibo  . '</div>';


        return $reciboFULL;
    }
    public function getCPF($num)
    {
        if (strlen($num) === 11) {
            $p1 = substr($num, 0, 3);
            $p2 = substr($num, 3, 3);
            $p3 = substr($num, 6, 3);
            $p4 = substr($num, 9, 2);
            return $p1 . "." . $p2 . "." . $p3 . "-" . $p4;
        } else {
            if (strlen($num) === 14) {
                return $this->getCNPJ($num);
            }
            return $num;
        }
    }
    public function getCNPJ($num)
    {
        if (strlen($num) === 14) {
            $p1 = substr($num, 0, 2);
            $p2 = substr($num, 2, 3);
            $p3 = substr($num, 5, 3);
            $p4 = substr($num, 8, 4);
            $p5 = substr($num, 12, 2);
            return $p1 . "." . $p2 . "." . $p3 . "/" . $p4 . "-" . $p5;
        } else {
            return $num;
        }
    }
    public function getCEP($num)
    {
        if (strlen($num) === 8) {
            $p1 = substr($num, 0, 5);
            $p4 = substr($num, 5, 3);
            return $p1 . "-" . $p4;
        } else {
            return $num;
        }
    }

    public function dataExtenso($date2, $diasemana = null)
    {
        date_default_timezone_set("America/Sao_Paulo");
        setlocale(LC_ALL, 'pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil');
        if (!$diasemana) {
            return utf8_encode(strftime("%d de %B de %Y - %A", strtotime($this->dataEUA($date2))));
        } else {
            return utf8_encode(strftime("%d de %B de %Y", strtotime($this->dataEUA($date2))));
        }
    }


    public function Getfloat($str)
    {

        if (strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }

        if (preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
            return floatval($match[0]);
        } else {
            return floatval($str); // take some last chances with floatval
        }
    }
    function valorExtenso($valor = 0, $maiusculas = false, $porcento = false)
    {
        $agora = $valor;
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;
        $rt = "";

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000") $z++;
            elseif ($z > 0) $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if (!$maiusculas) {
            if ($porcento) {
                if ($porcento === 2) {
                    $ok1 = str_replace("reais", "", $rt ? $rt : " zero");
                    return trim(str_replace("real", "", $ok1 ? $ok1 : " zero"));
                } else {
                    $valor2 = self::valorExtenso(substr($agora, 3, 2), false, 2);
                    $reaiss = str_replace("reais", "vírgula " . $valor2 . " por cento", $rt ? $rt : " zero");
                    $ok = str_replace("real", "vírgula " . $valor2 . " por cento", $reaiss ? $reaiss : " zero");
                    return trim($ok);
                }
            } else {
                return ($rt ? $rt : "zero");
            }
        } else {

            if ($rt) $rt = preg_replace(" E ", " e ", ucwords($rt));
            return (($rt) ? ($rt) : "Zero");
        }
    }
    public function dataEUA($data)
    {
        $dat = explode("-", $data);
        if (count($dat) == 3) {
            return $dat[1] . "/" . $dat[2] . "/" . $dat[0];
        } else {
            return $data;
        }
    }
    public function dataBRtoEUA($data)
    {
        $dat = explode("/", $data);
        if (count($dat) == 3) {
            return $dat[2] . "-" . $dat[1] . "-" . $dat[0];
        } else {
            return $data;
        }
    }
    function imprimirRecibosSelecionados(Request $request)
    {
        if (count($request['select']) > 0) {
            $reciboHTML = "<div style='width: 100%; height: 100%;'>";
            for ($i = 0; $i < count($request['select']); $i++) {
                $float = $i % 2 !== 1 ? "float:left;" : "float:right;";
                $reciboHTML .= "<div style='" . $float . " width: 49%;'>" . $this->GerarReciboHtml($request['select'][$i]) . "</div>";
            }

            $mpdf = new \Mpdf\Mpdf([
                'margin_top' => 3,
                'margin_bottom' => 3,
                'margin_left' => 3,
                'margin_right' => 3,
                'mirrorMargins' => true,
                'mode' => 'utf-8',
                'format' => 'A4-L'
            ]);
            $mpdf->WriteHTML($reciboHTML . "</div>");
            $mpdf->Output("Recibos de Pagamento.pdf", "I");
        }
    }
    public function searchRecibosDate(Request $request)
    {

        $result = [];
        $result['datastart'] = $request->data_start;
        $result['dataend'] = $request->data_end;

        $request->data_start = $this->dataBRtoEUA($request->data_start);
        $request->data_end = $this->dataBRtoEUA($request->data_end);

        $recibos = $this->repository->where([["data", ">=", $request->data_start], ["data", "<=", $request->data_end]])->orderBy("data", "asc")->get();
        $result['title'] = "Recibos | " . env("APP_NAME");
        $result['recibos'] = $recibos;
        for ($i = 0; $i < count($recibos); $i++) {
            $user = $this->user->find($recibos[$i]["colaborador_id"]);
            $result['recibos'][$i]['user'] = isset($user) ? $user : null;
        }
        $result['btnactive'] = "relatorio";
        return view('recibos.index', $result);
    }
    public function deletarRecibo(Request $request)
    {
        $recibo = $this->repository->find($request->id);
        if ($recibo) {
            $ok = $this->repository->delete($request->id);
            if ($ok) {
                return redirect()->route("recibos.index");
            }
        }
    }
}
