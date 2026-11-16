
<?php

$pdo = require __DIR__ . '/config/conexao.php';

$totalClientes = $pdo->query("
    SELECT COUNT(*) FROM clientes
")->fetchColumn();

$totalServicos = $pdo->query("
    SELECT COUNT(*) FROM servicos
")->fetchColumn();

$totalPendentes = $pdo->query("
    SELECT COUNT(*) FROM vendas
    WHERE status = 'Pendente'
")->fetchColumn();

$totalRecebido = $pdo->query("
    SELECT COALESCE(SUM(s.preco), 0)
    FROM vendas v
    INNER JOIN servicos s ON s.id = v.servico_id
    WHERE v.status = 'Pago'
")->fetchColumn();

$ultimasVendas = $pdo->query("
    SELECT
        c.nome AS cliente,
        s.nome AS servico,
        s.preco,
        v.data_venda,
        v.status
    FROM vendas v
    INNER JOIN clientes c ON c.id = v.cliente_id
    INNER JOIN servicos s ON s.id = v.servico_id
    ORDER BY v.id DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

function e($valor) {
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

function dinheiro($valor) {
    return 'R$ ' . number_format(
        (float) $valor,
        2,
        ',',
        '.'
    );
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meu Negócio | Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <i class="bi bi-briefcase-fill"></i>
            <span>Meu Negócio</span>
        </div>

        <div class="menu-label">MENU PRINCIPAL</div>
        
        <nav class="nav-menu">

        <a href="index.php" class="nav-link active">
            <i class="bi bi-grid-1x2"></i>
            Dashboard
        </a>
        
        <a href="clientes/" class="nav-link active">
            <i class="bi bi-people"></i>
            Clientes
        </a>
        
        <a href="servicos/" class="nav-link active">
            <i class="bi bi-box-seam"></i>
            Serviços
        </a>
        
        <a href="vendas/" class="nav-link active">
            <i class="bi bi-receipt"></i>
            Vendas
        </a>
        </nav>

        <div class="sidebar-footer">
            <i class="bi bi circle-fill status-dot"></i>
            Sistema Operacional
        </div>
    </aside>



</body>
</html>
