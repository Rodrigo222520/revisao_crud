<?php

include 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nome)) {
        $errors['nome'] = 'Preencha o nome de forma correta.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Preencha o email de forma correta.';
    }

    if (empty($errors)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sss', $nome, $email, $senha_hash);
            if ($stmt->execute()) {
                $success = 'Novo usuário registrado com sucesso.';
                $nome = $email = '';
            } else {
                $errors['database'] = 'Erro ao registrar usuário: ' . $stmt->error;
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
    <title>Cadastrar Usuário</title>
</head>

<body>

    <main class="centro">
        <div>
            <h1>Cadastrar Usuário</h1>
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
                    <input type="text" class="flex" name="nome" placeholder="Nome" value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>">
                    <?php if (!empty($errors['nome'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['nome']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorNome"></label>

                    <input type="email" class="flex" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <div class="error"><p><?php echo htmlspecialchars($errors['email']); ?></p></div>
                    <?php endif; ?>
                    <label class="error" id="errorEmail"></label>

                </div>
                
                <br>
                <div class="centro">
                    <button type="submit"><h2>Cadastrar</h2></button>
                </div>
            </form>
        </div>  
    </main>                                  
</body>
<br>
</html>