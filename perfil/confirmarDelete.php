<?php //este arquivo funciona como uma confirmacao de delecao de conta, apenas isso
require dirname(__DIR__) . '/config.php';

session_start();
!$_SESSION['auth'] && header('Location: login.php?msg=Faça Login!!!');
$idSessao = $_GET['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONFIRMAçÂO</title>
    <!-- bootstrap link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
        body{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <h1>Ao deletar tudo, você perderá seus seguidores, curtidas, e todas as demais informações do seu perfil</h1>
    <a class="btn btn-danger" href='<?php echo "delete.php?id=$idSessao"?>'>DELETAR TUDO</a>
    <a href="../index.php">Cancelar</a>
    </div>
</body>
</html>