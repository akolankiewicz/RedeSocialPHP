<?php
require dirname(__DIR__) . '/config.php';

session_start();
!$_SESSION['auth'] && header('Location: login.php?msg=Faça Login!!!');

$idSessao = $_SESSION['id'];
$postId = $_GET['postId'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- bootstrap link -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curtidas</title>
</head>
<body>
<a class="btn btn-primary verperfil" href="../index.php">Voltar</a><!-- botao para voltar -->
    <h1>QUEM CURTIU ESSA PUBLICAÇÃO?</h1><br>
    <div class="container caixa">
    <?php
        $alguemCurtiu = false;
        $usuariosQueCurtiram = $db->query("SELECT * FROM curtidas WHERE postCurtido=$postId");
        while ($UsuarioLinha = $usuariosQueCurtiram->fetchArray(SQLITE3_ASSOC)) {
            $alguemCurtiu = true;
            $usu = $UsuarioLinha['curtidor'];
            $sqlVerNomeCurtidor = "SELECT * FROM usuarios WHERE id=$usu";
            $res = $db->query($sqlVerNomeCurtidor);
            $usuario = $res->fetchArray(SQLITE3_ASSOC);
            echo "<p><a href='visualizarPerfil.php?sessao=".$idSessao."&id=".$usu."'>".strtoupper($usuario['nome'])."</p>";
        }
        if(!$alguemCurtiu){
            echo "<h5 class='caixa'>Ainda não tiveram curtidas nessa publicação.</h5>";
            echo "<p>Seja o primeiro a curtir!</p>";
        }
    ?>
    </div>
</body>
</html>