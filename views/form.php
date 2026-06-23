<?php
    $editando = !empty($registro);

    $val = [
        'titulo'     => $editando ? $registro['titulo']     : '',
        'descricao'  => $editando ? $registro['descricao']  : '',
        'prioridade' => $editando ? $registro['prioridade'] : 'media',
        'status'     => $editando ? $registro['status']     : 'aberto',
    ];
?>

<?php if ($mensagem && $tipoMsg === 'erro'): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-x-octagon me-2"></i><?= esc($mensagem) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">

    <div class="col-lg-8">
        <div class="painel">
            <div class="painel-header">
                <h6 class="mb-0">
                    <i class="bi bi-<?= $editando ? 'pencil-square' : 'plus-square-fill' ?> me-2"></i>
                    <?= $editando ? 'Editar Chamado' : 'Novo Chamado' ?>
                </h6>
                <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
            </div>

            <div class="p-4">
                <form action="index.php?acao=salvar" method="POST">
                    <?= CSRF::campo() ?>

                    <?php if ($editando): ?>
                        <input type="hidden" name="id" value="<?= $registro['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="titulo" class="form-label fw-semibold">
                            Título <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg"
                               id="titulo" name="titulo" maxlength="100"
                               placeholder="Ex: Computador não liga"
                               value="<?= esc($val['titulo']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label fw-semibold">
                            Descrição <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="descricao" name="descricao"
                                  rows="5" placeholder="Descreva o problema com o máximo de detalhes..."
                                  required><?= esc($val['descricao']) ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="prioridade" class="form-label fw-semibold">Prioridade</label>
                            <select class="form-select" id="prioridade" name="prioridade">
                                <option value="baixa"  <?= $val['prioridade'] === 'baixa'  ? 'selected' : '' ?>>🟢 Baixa</option>
                                <option value="media"  <?= $val['prioridade'] === 'media'  ? 'selected' : '' ?>>🟡 Média</option>
                                <option value="alta"   <?= $val['prioridade'] === 'alta'   ? 'selected' : '' ?>>🔴 Alta</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="aberto"    <?= $val['status'] === 'aberto'    ? 'selected' : '' ?>>Aberto</option>
                                <option value="andamento" <?= $val['status'] === 'andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="fechado"   <?= $val['status'] === 'fechado'   ? 'selected' : '' ?>>Fechado</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-<?= $editando ? 'warning' : 'primary' ?> px-4 rounded-pill">
                            <i class="bi bi-check-lg me-1"></i>
                            <?= $editando ? 'Salvar Alterações' : 'Cadastrar Chamado' ?>
                        </button>
                        <a href="index.php" class="btn btn-light px-4 rounded-pill">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <?php if ($editando): ?>
            <div class="painel mb-3">
                <div class="painel-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detalhes</h6>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Chamado</small>
                        <span class="fs-5 fw-bold">#<?= $registro['id'] ?></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Criado em</small>
                        <span class="fw-semibold">
                            <?= date('d/m/Y \à\s H:i', strtotime($registro['data_criacao'])) ?>
                        </span>
                    </div>
                    <?php if (!empty($registro['data_atualizacao']) && $registro['data_atualizacao'] !== $registro['data_criacao']): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Atualizado em</small>
                        <span class="fw-semibold">
                            <?= date('d/m/Y \à\s H:i', strtotime($registro['data_atualizacao'])) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Prioridade</small>
                        <span class="pill pill--<?= corPrioridade($registro['prioridade']) ?>">
                            <i class="<?= iconePrioridade($registro['prioridade']) ?>"></i>
                            <?= labelPrioridade($registro['prioridade']) ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="pill pill--<?= corStatus($registro['status']) ?>">
                            <i class="<?= iconeStatus($registro['status']) ?>"></i>
                            <?= labelStatus($registro['status']) ?>
                        </span>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-outline-danger w-100 rounded-pill"
                            onclick="confirmarExclusao(<?= $registro['id'] ?>)">
                        <i class="bi bi-trash3 me-1"></i>Excluir
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="painel">
                <div class="painel-header">
                    <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Orientações</h6>
                </div>
                <div class="p-4">
                    <p class="label-section">Prioridades</p>

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="pill pill--cyan px-2">Baixa</span>
                        <small class="text-muted">Pode aguardar</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="pill pill--amber px-2">Média</span>
                        <small class="text-muted">Atenção em breve</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="pill pill--coral px-2">Alta</span>
                        <small class="text-muted">Resolução urgente</small>
                    </div>

                    <hr>
                    <p class="label-section">Ciclo de vida</p>

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="pill pill--violet px-2">Aberto</span>
                        <small class="text-muted">Aguardando atendimento</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="pill pill--orange px-2">Em Andamento</span>
                        <small class="text-muted">Sendo resolvido</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="pill pill--emerald px-2">Fechado</span>
                        <small class="text-muted">Encerrado</small>
                    </div>

                    <hr>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Use títulos claros e objetivos.
                    </small>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
