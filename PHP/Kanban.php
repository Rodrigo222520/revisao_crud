<?php
include 'db.php';

$sql = "SELECT t.id, t.nomeSetor, t.descricao, t.dataCadastro, t.status, t.prioridade, u.nome as usuario_nome FROM tarefas t JOIN usuario u ON t.idUsuario = u.id ORDER BY t.prioridade DESC, t.dataCadastro DESC";
$result = $conn->query($sql);

$kanban = [
    'fazer' => [],
    'feito' => [],
    'pronto' => []
];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kanban[$row['status']][] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/style.css">
    <title>Kanban de Tarefas</title>
</head>
<body>
    <main>
        <h1 class="centro">Quadro Kanban</h1>
            <div class="centro">
                <a href="Tarefas.php">Gerenciar Tarefas</a> 
            </div>
            <br>
        <div class="kanban-board">
            <?php foreach ([
                'fazer' => 'A Fazer',
                'feito' => 'Feito',
                'pronto' => 'Pronto'
            ] as $status => $titulo): ?>
                <div class="kanban-column">
                    <h2><?= $titulo ?></h2>
                    <?php if (!empty($kanban[$status])): ?>
                        <?php foreach ($kanban[$status] as $tarefa): ?>
                            <div class="kanban-card prioridade-<?= htmlspecialchars($tarefa['prioridade']) ?>">
                                <strong><?= htmlspecialchars($tarefa['nomeSetor']) ?></strong>
                                <div><?= htmlspecialchars($tarefa['descricao']) ?></div>
                                <div><small>Data: <?= htmlspecialchars($tarefa['dataCadastro']) ?></small></div>
                                <div><small>Usuário: <?= htmlspecialchars($tarefa['usuario_nome']) ?></small></div>
                                <div><small>Prioridade: <?= htmlspecialchars($tarefa['prioridade']) ?></small></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="centro">Nenhuma tarefa</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>