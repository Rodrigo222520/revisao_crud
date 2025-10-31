<?php

include '../SQL/db.sql';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = $_POST['usuario'];
    $nomeSetor = $_POST['nomeSetor'];
    $descricao = $_POST['descricao'];
    $dataCadastro = $_POST['dataCadastro'];
    $status = $_POST['status'];
    $prioridade = $_POST['prioridade'];
   
    $sql = " INSERT INTO tarefas (usuario,nomeSetor,descricao,dataCadastro,status,prioridade) VALUE ('$usuario','$nomeSetor','$descricao,'$dataCadastro','$status','$prioridade')";   

        if ($conn->query($sql) === true) {
            echo "<div class='mensagemErro'> 
        <p>Nova tarefa registrada com sucesso.</p>
        <a href='' class='fechar'>Fechar</a>
            </div>";
        } else {
            echo "<div class='mensagemErro'> 
        <p>Erro</p>
        <a href='' class='fechar'>Fechar</a>
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
    <title>Gerenciar Tarefas</title>
</head>

<body>

    <main class="centro">
        <div>
            <h1>Gerenciar Tarefas</h1>
            <form id="validarCadastroUsuario" action="" method="POST">
                <div id="quadrado">
                    <input type="text" class="flex" name="usuario" placeholder="Usuário">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$name){
                                echo "<div class='error'>
                                <p>Preencha o usuário de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                        ?>   
                    <label class="error" id="errorUsuario"></label>
                    
                    <input type="text" class="flex" name="nomeSetor" placeholder="Nome do setor">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$name){
                                echo "<div class='error'>
                                <p>Preencha o setor de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                    ?>  
                    <label class="error" id="errorSetor"></label>

                    <input type="text" class="flex" name="descricao" placeholder="Descrição">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$name){
                                echo "<div class='error'>
                                <p>Preencha o setor de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                    ?>  
                    <label class="error" id="errorDescricao"></label>

                    <input type="text" class="flex" name="dataCadastro" placeholder="Data do cadastro">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$name){
                                echo "<div class='error'>
                                <p>Preencha o cadastro de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                    ?>  
                    <label class="error" id="errorCadastro"></label>

                    <label for="status">
                        <select name="status" class="flex">
                            <option value="none">Status</option>
                            <option value="Fazer">Fazer</option>
                            <option value="Feito">Feito</option>
                            <option value="Pronto">Pronto</option>
                        </select>
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$status || $status == 'none'){
                                echo "<div class='error'>
                                <p>Preencha o status de Forma Correta</p>
                                </div>";
                                $valido = false;
                            }else{
                                $valido = true;
                            }
                        }
                    ?>   
                    <label for="prioridade">
                        <select name="prioridade" class="flex">
                            <option value="none">Prioridade</option>
                            <option value="Baixa">Baixa</option>
                            <option value="Média">Média</option>
                            <option value="Alta">Alta</option>
                        </select>
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!$status || $status == 'none'){
                                echo "<div class='error'>
                                <p>Preencha a prioridade de Forma Correta</p>
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
            <h2>Inserir tarefa</h2>
        </div>
    </button>
</footer>
</html>