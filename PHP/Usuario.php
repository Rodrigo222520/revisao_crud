<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = " INSERT INTO usuarios (nome,email,senha) VALUE ('$nome','$email')";   

        if ($conn->query($sql) === true) {
            echo "<div class='mensagemErro'> 
        <p>Novo Funcionário registrado com sucesso.</p>
        <a href='cadastrarFuncionario.php' class='fechar'>Fechar</a>
            </div>";
        } else {
            echo "<div class='mensagemErro'> 
        <p>Erro</p>
        <a href='cadastrarFuncionario.php' class='fechar'>Fechar</a>
            </div>" . $sql . '<br>' . $conn->error;
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
            <form id="validarCadastroUsuario" action="" method="POST">
                <div id="quadrado">
                    <input type="text" class="flex" name="nome" placeholder="Nome">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$name){
                                echo "<div class='error'>
                                <p>Preencha o Nome de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                        ?>   
                    <label class="error" id="errorNome"></label>
                    
                    <input type="text" class="flex" name="email" placeholder="Email">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$name){
                                echo "<div class='error'>
                                <p>Preencha o Email de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                    ?>  
                </div>
            </form>
        </div>  
    </main>                                  
</body>
<br>
<footer class="footerCentral">
    <button type="submit">
            <h2>Cadastrar</h2>
        </div>
    </button>
</footer>
</html>