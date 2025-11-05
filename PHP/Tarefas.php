<?php
include 'db.php';

if (isset($_GET['delete'])) {
    $idDelete = intval($_GET['delete']);
    $conn->query("DELETE FROM tarefas WHERE id = $idDelete");
    header('Location: Tarefas.php');
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM tarefas WHERE id = $idEdit");
    if ($result && $result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUsuario = $_POST['idUsuario'] ?? '';
    $nomeSetor = trim($_POST['nomeSetor'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $dataCadastro = trim($_POST['dataCadastro'] ?? '');
    $status = $_POST['status'] ?? 'none';
    $prioridade = $_POST['prioridade'] ?? 'none';
    $idTarefa = $_POST['idTarefa'] ?? '';

    if ($idTarefa) {
        $stmt = $conn->prepare("UPDATE tarefas SET idUsuario=?, nomeSetor=?, descricao=?, dataCadastro=?, status=?, prioridade=? WHERE id=?");
        $stmt->bind_param('isssssi', $idUsuario, $nomeSetor, $descricao, $dataCadastro, $status, $prioridade, $idTarefa);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO tarefas (idUsuario, nomeSetor, descricao, dataCadastro, status, prioridade) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $idUsuario, $nomeSetor, $descricao, $dataCadastro, $status, $prioridade);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: Tarefas.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/style.css">
    <title>Lista de Tarefas</title>
</head>
<body>
    <div class="centro">
        <h1>Lista de Tarefas</h1>
    </div>
    <div class="centro">
        <a href="Tarefas.php" class="btnAdd">Nova Tarefa</a>
    </div>
    <main class="centro">
        <form action="Tarefas.php" method="POST" style="margin-top:20px;">
            <input type="hidden" name="idTarefa" value="<?= $editData ? $editData['id'] : '' ?>">
            <select name="idUsuario" required>
                <option value="">Selecione um usuário</option>
                <?php
                $usuarios = $conn->query("SELECT id, nome FROM usuario");
                while ($u = $usuarios->fetch_assoc()) {
                    $selected = $editData && $editData['idUsuario'] == $u['id'] ? 'selected' : '';
                    echo "<option value='{$u['id']}' $selected>{$u['nome']}</option>";
                }
                ?>
            </select>
            <input type="text" name="nomeSetor" placeholder="Setor" value="<?= $editData ? htmlspecialchars($editData['nomeSetor']) : '' ?>" required>
            <input type="text" name="descricao" placeholder="Descrição" value="<?= $editData ? htmlspecialchars($editData['descricao']) : '' ?>" required>
            <input type="date" name="dataCadastro" value="<?= $editData ? htmlspecialchars($editData['dataCadastro']) : '' ?>" required>
            <select name="status" required>
                <option value="fazer" <?= $editData && $editData['status']=='fazer' ? 'selected' : '' ?>>Fazer</option>
                <option value="feito" <?= $editData && $editData['status']=='feito' ? 'selected' : '' ?>>Feito</option>
                <option value="pronto" <?= $editData && $editData['status']=='pronto' ? 'selected' : '' ?>>Pronto</option>
            </select>
            <select name="prioridade" required>
                <option value="baixa" <?= $editData && $editData['prioridade']=='baixa' ? 'selected' : '' ?>>Baixa</option>
                <option value="media" <?= $editData && $editData['prioridade']=='media' ? 'selected' : '' ?>>Média</option>
                <option value="grande" <?= $editData && $editData['prioridade']=='grande' ? 'selected' : '' ?>>Alta</option>
            </select>
        </form>  
    </main>
</body>
</html>

<br>
<div class="centro">
    <button type="submit" class="btnAdd">Salvar</button>
</div>

<table border="1" cellpadding="8" style="width:100%; border-collapse:collapse; margin-top:30px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Setor</th>
            <th>Descrição</th>
            <th>Data</th>
            <th>Status</th>
            <th>Prioridade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT t.id, u.nome as usuario_nome, t.nomeSetor, t.descricao, t.dataCadastro, t.status, t.prioridade FROM tarefas t JOIN usuario u ON t.idUsuario = u.id ORDER BY t.dataCadastro DESC";
        $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . htmlspecialchars($row['usuario_nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nomeSetor']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['dataCadastro']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['prioridade']) . '</td>';
                    echo '<td>';
                    echo '<a href="Tarefas.php?edit=' . $row['id'] . '" class="btnEdit">Editar</a> ';
                    echo '<a href="Tarefas.php?delete=' . $row['id'] . '" class="btnDelete" onclick="return confirm(\'Tem certeza que deseja excluir esta tarefa?\');">Excluir</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" style="text-align:center; color:#aaa;">Nenhuma tarefa encontrada</td></tr>';
            }
        ?>
    </tbody>
</table>