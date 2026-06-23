<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Help Desk') ?> — HelpDesk Pro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar-app" aria-label="Navegação principal">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between w-100">

                <a href="index.php" class="navbar-logo">
                    <span class="logo-box"><i class="bi bi-activity"></i></span>
                    Help<strong>Desk</strong>
                </a>

                <div class="navbar-links d-none d-md-flex">
                    <a href="index.php" class="<?= ($acao ?? '') === 'listar' ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i> Painel
                    </a>
                    <a href="index.php?acao=criar" class="<?= ($acao ?? '') === 'criar' ? 'active' : '' ?>">
                        <i class="bi bi-plus-square-fill"></i> Novo Chamado
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="navbar-stat d-none d-lg-flex">
                        <span class="dot dot--aberto"></span> <?= $contadores['aberto'] ?? 0 ?>
                        <span class="dot dot--andamento ms-2"></span> <?= $contadores['andamento'] ?? 0 ?>
                        <span class="dot dot--fechado ms-2"></span> <?= $contadores['fechado'] ?? 0 ?>
                    </div>
                    <a href="index.php?acao=criar" class="btn-novo d-md-none">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-3 px-md-5 py-4">
        <?= $conteudo ?>
    </main>

    <div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0">
                <form method="POST" action="index.php?acao=excluir">
                    <?= CSRF::campo() ?>
                    <input type="hidden" name="id" id="excluirId">

                    <div class="modal-body text-center py-5">
                        <div class="modal-icon-danger mb-3">
                            <i class="bi bi-trash3"></i>
                        </div>
                        <h5 class="fw-bold mb-1" id="modalExcluirTitulo">Excluir chamado?</h5>
                        <p class="text-muted small mb-0">Esta ação é irreversível.</p>
                    </div>

                    <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">
                            <i class="bi bi-trash3 me-1"></i>Excluir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="app-footer">
        <div class="container-fluid px-4">
            <span>&copy; <?= date('Y') ?> HelpDesk Pro — Sistema de Chamados</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarExclusao(id) {
            document.getElementById('excluirId').value = id;
            new bootstrap.Modal(document.getElementById('modalExcluir')).show();
        }
    </script>
</body>
</html>
