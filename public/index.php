<?php


$bootstrapPublic = __DIR__ . '/../app/bootstrap.php';
$bootstrapRoot   = __DIR__ . '/app/bootstrap.php';

if (file_exists($bootstrapPublic)) {
    require_once $bootstrapPublic;
} elseif (file_exists($bootstrapRoot)) {
    require_once $bootstrapRoot;
} else {
    http_response_code(500);
    exit('Arquivo app/bootstrap.php não encontrado. Confira a estrutura de pastas do projeto.');
}

$model = new Chamado($pdo);

$demoMode     = (bool)($config['demo_mode'] ?? false);
$maxChamados  = (int)($config['max_chamados'] ?? 50);
$maxTitulo    = (int)($config['max_titulo'] ?? 100);
$maxDescricao = (int)($config['max_descricao'] ?? 1000);

$acoesPermitidas = ['listar', 'criar', 'editar', 'salvar', 'excluir'];
$acao = $_GET['acao'] ?? 'listar';

if (!in_array($acao, $acoesPermitidas, true)) {
    $acao = 'listar';
}


$contadores = $model->contarPorStatus();
$totalGeral = array_sum($contadores);
$mensagem   = $_GET['msg']  ?? '';
$tipoMsg    = $_GET['tipo'] ?? 'sucesso';

$base = compact('contadores', 'totalGeral', 'mensagem', 'tipoMsg');


switch ($acao) {

    case 'salvar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validar()) {
            redirecionar('Requisição inválida.', 'erro');
        }

        $id        = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        $titulo    = trim($_POST['titulo']    ?? '');
        $descricao = trim($_POST['descricao'] ?? '');

        if ($titulo === '' || $descricao === '') {
            $volta = $id ? "index.php?acao=editar&id={$id}" : 'index.php?acao=criar';
            redirecionar('Preencha todos os campos obrigatórios.', 'erro', $volta);
        }

        if (mb_strlen($titulo) > $maxTitulo) {
            $volta = $id ? "index.php?acao=editar&id={$id}" : 'index.php?acao=criar';
            redirecionar("O título deve ter no máximo {$maxTitulo} caracteres.", 'erro', $volta);
        }

        if (mb_strlen($descricao) > $maxDescricao) {
            $volta = $id ? "index.php?acao=editar&id={$id}" : 'index.php?acao=criar';
            redirecionar("A descrição deve ter no máximo {$maxDescricao} caracteres.", 'erro', $volta);
        }

        if (!$id && $demoMode && $model->contarTotal() >= $maxChamados) {
            redirecionar(
                "Limite de {$maxChamados} chamados atingido nesta versão demonstrativa. Exclua um chamado antigo para cadastrar outro.",
                'erro',
                'index.php'
            );
        }

        $dados = [
            'titulo'     => $titulo,
            'descricao'  => $descricao,
            'prioridade' => $_POST['prioridade'] ?? 'baixa',
            'status'     => $_POST['status']     ?? 'aberto',
        ];

        if ($id) {
            $model->atualizar($id, $dados);
            redirecionar('Chamado atualizado com sucesso!');
        }

        $model->criar($dados);
        redirecionar('Chamado cadastrado com sucesso!');

    case 'excluir':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validar()) {
            redirecionar('Requisição inválida.', 'erro');
        }

        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

        if (!$id) {
            redirecionar('ID inválido.', 'erro');
        }

        $model->excluir($id);
        redirecionar('Chamado excluído com sucesso!');

    case 'criar':
        renderizar('form', $base + [
            'pageTitle' => 'Novo Chamado',
            'acao'      => 'criar',
            'registro'  => null,
        ]);
        break;

    case 'editar':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || !($registro = $model->buscarPorId($id))) {
            redirecionar('Chamado não encontrado.', 'erro');
        }

        renderizar('form', $base + [
            'pageTitle' => "Editar Chamado #{$id}",
            'acao'      => 'editar',
            'registro'  => $registro,
        ]);
        break;

    default:
        $filtro = $_GET['status'] ?? '';
        $busca  = trim($_GET['busca'] ?? '');
        $ordem  = ($_GET['ordem'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        renderizar('listar', $base + [
            'pageTitle' => 'Painel de Chamados',
            'acao'      => 'listar',
            'chamados'  => $model->listar($filtro, $busca, $ordem),
            'filtro'    => $filtro,
            'busca'     => $busca,
            'ordem'     => $ordem,
        ]);
        break;
}
