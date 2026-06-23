<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro — HelpDesk Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #F5F5F4; font-family: 'Segoe UI', system-ui, sans-serif; }
        .erro-container { max-width: 560px; margin: 120px auto; text-align: center; }
        .erro-icon { font-size: 4rem; color: #F43F5E; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="erro-container">
        <div class="erro-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <h3 class="fw-bold mb-3">Erro de Conexão</h3>
        <p class="text-muted">
            <?= esc($erroDeploy ?? 'Não foi possível conectar ao banco de dados. Verifique se o MySQL está em execução e tente novamente.') ?>
        </p>
        <a href="index.php" class="btn btn-dark rounded-pill px-4 mt-3">
            <i class="bi bi-arrow-clockwise me-1"></i> Tentar Novamente
        </a>
    </div>
</body>
</html>
