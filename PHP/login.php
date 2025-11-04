<?php

include 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Preencha o email de forma correta.';
    }
    if (empty($senha) || strlen($senha) < 6) {
        $errors['senha'] = 'A senha deve ter pelo menos 6 caracteres.';
    }

    if (empty($errors)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuario (email, senha) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param('ss', $email, $senha_hash);
            if ($stmt->execute()) {
                $success = 'Novo usuário cadastrado com sucesso.';
                $email = $senha = '';
            } else {
                $errors['database'] = 'Erro ao cadastrar usuário: ' . $stmt->error;
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
    <title>Login</title>
</head>

<body>
    <main class="centro">
        <div>
            <h1>Login</h1>
            <a href="Tarefas.php">Gerenciar Tarefas</a>
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
                    <input type="email" class="flex" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['email']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorEmail"></label>

                    <input type="password" class="flex" name="senha" placeholder="Senha">
                    <?php if (!empty($errors['senha'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['senha']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorSenha"></label>
                </div>

                <br>
                <div class="centro">
                    <button type="submit" class="btnSubmit"><h2>Cadastrar</h2></button>
                </div>
            </form>
        </div>  
    </main>                                  
</body>
</html>