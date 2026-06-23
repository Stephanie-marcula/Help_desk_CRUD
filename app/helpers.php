<?php


function esc(?string $v): string
{
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}


function redirecionar(string $msg = '', string $tipo = 'sucesso', string $url = 'index.php'): void
{
    $query = '';

    if ($msg !== '') {
        $query = '?' . http_build_query(['msg' => $msg, 'tipo' => $tipo]);
    }

    header("Location: {$url}{$query}");
    exit;
}

function renderizar(string $view, array $dados = []): void
{
    extract($dados);

    ob_start();
    require BASE_PATH . "/views/{$view}.php";
    $conteudo = ob_get_clean();

    require BASE_PATH . '/views/layout.php';
}

function corPrioridade(string $p): string
{
    return ['alta' => 'coral', 'media' => 'amber', 'baixa' => 'cyan'][$p] ?? 'gray';
}

function corStatus(string $s): string
{
    return ['aberto' => 'violet', 'andamento' => 'orange', 'fechado' => 'emerald'][$s] ?? 'gray';
}

function labelPrioridade(string $p): string
{
    return ['alta' => 'Alta', 'media' => 'Média', 'baixa' => 'Baixa'][$p] ?? $p;
}

function labelStatus(string $s): string
{
    return ['aberto' => 'Aberto', 'andamento' => 'Em Andamento', 'fechado' => 'Fechado'][$s] ?? $s;
}

function iconePrioridade(string $p): string
{
    return [
        'alta'  => 'bi-exclamation-triangle-fill',
        'media' => 'bi-dash-circle-fill',
        'baixa' => 'bi-arrow-down-circle-fill',
    ][$p] ?? 'bi-circle';
}

function iconeStatus(string $s): string
{
    return [
        'aberto'    => 'bi-envelope-open',
        'andamento' => 'bi-arrow-repeat',
        'fechado'   => 'bi-check-circle-fill',
    ][$s] ?? 'bi-circle';
}
