<!-- ═══ ALERTAS ═══ -->
<?php if ($mensagem): ?>
    <div class="alert alert-<?= $tipoMsg === 'erro' ? 'danger' : 'success' ?> alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-<?= $tipoMsg === 'erro' ? 'x-octagon' : 'check-circle' ?> me-2"></i>
        <?= esc($mensagem) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="kpi-card kpi--total">
            <div class="kpi-icon"><i class="bi bi-collection"></i></div>
            <div>
                <div class="kpi-valor"><?= $totalGeral ?></div>
                <div class="kpi-label">Total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card kpi--aberto">
            <div class="kpi-icon"><i class="bi bi-envelope-open"></i></div>
            <div>
                <div class="kpi-valor"><?= $contadores['aberto'] ?></div>
                <div class="kpi-label">Abertos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card kpi--andamento">
            <div class="kpi-icon"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="kpi-valor"><?= $contadores['andamento'] ?></div>
                <div class="kpi-label">Em Andamento</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="kpi-card kpi--fechado">
            <div class="kpi-icon"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="kpi-valor"><?= $contadores['fechado'] ?></div>
                <div class="kpi-label">Fechados</div>
            </div>
        </div>
    </div>
</div>

<div class="painel mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-lg-4">
            <form method="GET" class="search-box">
                <input type="hidden" name="status" value="<?= esc($filtro) ?>">
                <input type="hidden" name="ordem"  value="<?= esc($ordem) ?>">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="busca" class="form-control"
                       placeholder="Buscar chamados..."
                       value="<?= esc($busca) ?>">
            </form>
        </div>

        <div class="col-lg-5">
            <div class="d-flex flex-wrap gap-2">
                <a href="index.php?ordem=<?= $ordem ?>&busca=<?= urlencode($busca) ?>"
                   class="chip <?= empty($filtro) ? 'chip--active' : '' ?>">Todos</a>
                <a href="index.php?status=aberto&ordem=<?= $ordem ?>&busca=<?= urlencode($busca) ?>"
                   class="chip chip--violet <?= $filtro === 'aberto' ? 'chip--active' : '' ?>">
                   <span class="dot dot--aberto"></span> Abertos</a>
                <a href="index.php?status=andamento&ordem=<?= $ordem ?>&busca=<?= urlencode($busca) ?>"
                   class="chip chip--orange <?= $filtro === 'andamento' ? 'chip--active' : '' ?>">
                   <span class="dot dot--andamento"></span> Em Andamento</a>
                <a href="index.php?status=fechado&ordem=<?= $ordem ?>&busca=<?= urlencode($busca) ?>"
                   class="chip chip--emerald <?= $filtro === 'fechado' ? 'chip--active' : '' ?>">
                   <span class="dot dot--fechado"></span> Fechados</a>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="btn-group w-100">
                <a href="index.php?status=<?= esc($filtro) ?>&ordem=desc&busca=<?= urlencode($busca) ?>"
                   class="btn btn-sm <?= $ordem === 'desc' ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-start-pill">
                    <i class="bi bi-sort-down me-1"></i>Recentes
                </a>
                <a href="index.php?status=<?= esc($filtro) ?>&ordem=asc&busca=<?= urlencode($busca) ?>"
                   class="btn btn-sm <?= $ordem === 'asc' ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-end-pill">
                    <i class="bi bi-sort-up me-1"></i>Antigos
                </a>
            </div>
        </div>
    </div>
</div>

<div class="painel p-0">
    <div class="painel-header">
        <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Chamados <span class="text-muted fw-normal">(<?= count($chamados) ?>)</span></h6>
    </div>

    <div class="table-responsive">
        <table class="table tabela mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($chamados)): ?>
                    <tr>
                        <td colspan="6" class="vazio">
                            <i class="bi bi-inbox"></i>
                            <p>Nenhum chamado encontrado.</p>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($chamados as $c): ?>
                    <tr class="linha-<?= $c['prioridade'] ?>">
                        <td class="fw-bold text-muted"><?= $c['id'] ?></td>
                        <td>
                            <div class="fw-semibold"><?= esc($c['titulo']) ?></div>
                            <?php if ($c['descricao']): ?>
                                <small class="text-muted"><?= esc(mb_strimwidth($c['descricao'], 0, 100, '...')) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="pill pill--<?= corPrioridade($c['prioridade']) ?>">
                                <i class="<?= iconePrioridade($c['prioridade']) ?>"></i>
                                <?= labelPrioridade($c['prioridade']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="pill pill--<?= corStatus($c['status']) ?>">
                                <i class="<?= iconeStatus($c['status']) ?>"></i>
                                <?= labelStatus($c['status']) ?>
                            </span>
                        </td>
                        <td>
                            <small class="text-muted"><?= date('d/m/Y \à\s H:i', strtotime($c['data_criacao'])) ?></small>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="index.php?acao=editar&id=<?= $c['id'] ?>" class="btn-icon btn-icon--edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-icon btn-icon--delete" title="Excluir"
                                        onclick="confirmarExclusao(<?= $c['id'] ?>)">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
