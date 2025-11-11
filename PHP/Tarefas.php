<?php

include 'db.php';
include 'api.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUsuario = $_POST['idUsuario'] ?? '';
    $nomeSetor = trim($_POST['nomeSetor'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $dataCadastro = trim($_POST['dataCadastro'] ?? '');
    $status = $_POST['status'] ?? 'none';
    $prioridade = $_POST['prioridade'] ?? 'none';

    if ($idUsuario === '') {
        $errors['idUsuario'] = 'Selecione um usuário.';
    }
    if ($nomeSetor === '') {
        $errors['nomeSetor'] = 'Preencha o setor de forma correta.';
    }
    if ($descricao === '') {
        $errors['descricao'] = 'Preencha a descrição de forma correta.';
    }
    if ($dataCadastro === '') {
        $errors['dataCadastro'] = 'Preencha a data de cadastro de forma correta.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataCadastro)) {
        $errors['dataCadastro'] = 'Data deve estar no formato YYYY-MM-DD.';
    }
    if ($status === 'none') {
        $errors['status'] = 'Preencha o status de forma correta.';
    }
    if ($prioridade === 'none') {
        $errors['prioridade'] = 'Preencha a prioridade de forma correta.';
    }

    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO tarefas (idUsuario,nomeSetor,descricao,dataCadastro,status,prioridade) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('isssss', $idUsuario, $nomeSetor, $descricao, $dataCadastro, $status, $prioridade);
            if ($stmt->execute()) {
                $success = 'Nova tarefa registrada com sucesso.';
                $task = [
                    'id' => $conn->insert_id,
                    'idUsuario' => $idUsuario,
                    'nomeSetor' => $nomeSetor,
                    'descricao' => $descricao,
                    'dataCadastro' => $dataCadastro,
                    'status' => $status,
                    'prioridade' => $prioridade,
                ];
                $apiResp = send_task_created($task);
                if (!$apiResp['ok']) {
                    $errors['api'] = 'Aviso: falha ao notificar API externa.';
                }
                $idUsuario = $nomeSetor = $descricao = $dataCadastro = '';
                $status = 'none';
                $prioridade = 'none';
            } else {
                $errors['database'] = 'Erro ao executar a inserção: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors['database'] = 'Erro na preparação da query: ' . $conn->error;
        }
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../../assets/logos/logoPequena.png">
    <link rel="stylesheet" href="../style/style.css">
    <title>Gerenciar Tarefas</title>
</head>

<body>

    <main class="centro">
        <div>
            <h1>Gerenciar Tarefas</h1>

            <?php if ($success): ?>
                <div class="mensagemSucesso">
                    <p><?php echo htmlspecialchars($success); ?></p>
                    <a href="" class="fechar">Fechar</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors) && isset($errors['database'])): ?>
                <div class="mensagemErro">
                    <p><?php echo htmlspecialchars($errors['database']); ?></p>
                    <a href="" class="fechar">Fechar</a>
                </div>
            <?php endif; ?>

            <form id="validarCadastroUsuario" action="" method="POST">
                <div id="quadrado">
                    <label for="idUsuario"></label>
                    <select name="idUsuario" class="flex">
                        <option value="" <?php echo (isset($idUsuario) && $idUsuario === '') ? 'selected' : ''; ?>>Selecione um usuário</option>
                        <?php
                        $conn_select = new mysqli($servername, $username, $password, $dbname);
                        if ($conn_select->connect_error) {
                            die("Conexao falhou: " . $conn_select->connect_error);
                        }
                        $result = $conn_select->query("SELECT id, nome FROM usuario");
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $selected = (isset($idUsuario) && $idUsuario == $row['id']) ? 'selected' : '';
                                echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['nome']) . "</option>";
                            }
                        }
                        $conn_select->close();
                        ?>
                    </select>
                    <?php if (!empty($errors['idUsuario'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['idUsuario']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorUsuario"></label>

                    <input type="text" class="flex" name="nomeSetor" placeholder="Nome do setor" value="<?php echo isset($nomeSetor) ? htmlspecialchars($nomeSetor) : ''; ?>">
                    <?php if (!empty($errors['nomeSetor'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['nomeSetor']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorSetor"></label>

                    <input type="text" class="flex" name="descricao" placeholder="Descrição" value="<?php echo isset($descricao) ? htmlspecialchars($descricao) : ''; ?>">
                    <?php if (!empty($errors['descricao'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['descricao']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorDescricao"></label>

                    <input type="date" class="flex" name="dataCadastro" value="<?php echo isset($dataCadastro) ? htmlspecialchars($dataCadastro) : ''; ?>">
                    <?php if (!empty($errors['dataCadastro'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['dataCadastro']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorCadastro"></label>

                    <label for="status"></label>
                    <select name="status" class="flex">
                        <option value="none" <?php echo (isset($status) && $status === 'none') ? 'selected' : ''; ?>>Status</option>
                        <option value="fazer" <?php echo (isset($status) && $status === 'fazer') ? 'selected' : ''; ?>>Fazer</option>
                        <option value="feito" <?php echo (isset($status) && $status === 'feito') ? 'selected' : ''; ?>>Feito</option>
                        <option value="pronto" <?php echo (isset($status) && $status === 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                    </select>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['status']); ?></p></div>
                    <?php endif; ?>

                    <label for="prioridade"></label>
                    <select name="prioridade" class="flex">
                        <option value="none" <?php echo (isset($prioridade) && $prioridade === 'none') ? 'selected' : ''; ?>>Prioridade</option>
                        <option value="baixa" <?php echo (isset($prioridade) && $prioridade === 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                        <option value="media" <?php echo (isset($prioridade) && $prioridade === 'media') ? 'selected' : ''; ?>>Média</option>
                        <option value="grande" <?php echo (isset($prioridade) && $prioridade === 'grande') ? 'selected' : ''; ?>>Alta</option>
                    </select>
                    <?php if (!empty($errors['prioridade'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['prioridade']); ?></p></div>
                    <?php endif; ?>

                </div>
                
                <br>
                <div class="centro">
                    <button type="submit" class="btnSubmit"><h2>Inserir tarefa</h2></button>
                </div>

            </form>
        </div>
    </main>

</body>
<br>
</html>