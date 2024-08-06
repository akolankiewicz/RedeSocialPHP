<?php
require dirname(__DIR__) . '/config.php';

session_start();
!$_SESSION['auth'] && header('Location: login.php?msg=Faça Login!!!');

$id = $_GET['id'];
$sqlSelect = "SELECT * FROM usuarios WHERE id=$id";//puxando dados para expor nos inputs
$result = $db->query($sqlSelect);
$userdata = $result->fetchArray(SQLITE3_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
        th, td, table{
            border: .5px solid black;
            margin: auto;
            padding: 10px;
            background-color: #E6E6FA;
        }
    </style>
</head>
<body>
    <h1 style="margin-top: 50px; text-align: center;">Formulário de Edição</h1>
    <form action="saveEdit.php" method="POST">
        <div class="container">
            <label for="email">Email:</label>
            <input class="form-control w-50" type="email" name="email" required value="<?php echo $userdata['email'] ?>"> <br>
            <label for="nome">Nome:</label>
            <input class="form-control w-50" type="text" name="nome" pattern="[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$" required value="<?php echo $userdata['nome'] ?>"> <br>
            <label for="nome">Telefone:</label>
            <input class="form-control w-50" type="text" name="telefone" required value="<?php echo $userdata['telefone'] ?>"> <br>
            <label for="senha">Senha:</label>
            <input class="form-control w-50" type="text" disabled placeholder="Não se pode alterar a senha!" name="senha"> <br><br>
            <input type="hidden" name="id" value="<?php echo $id ?>"><!-- para o id poder ser getado -->
            <button class="btn btn-success" type="submit">Salvar alterações</button>
        </div>
    </form><br><br>
</body>
</html>