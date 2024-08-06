<?php
require dirname(__DIR__) . '/config.php';

session_start();
!$_SESSION['auth'] && header('Location: login.php?msg=Faça Login!!!');

$id = $_GET['id'];
$idSessao = $_GET['sessao'];

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
<a class="btn btn-primary verperfil" href="../perfil.php?id=<?php echo $id?>">Voltar</a>
    <h1>SEGUIDORES</h1><br>
    <div class="container caixa">
        <?php
            $alguemSegue = false;
            $seguidores = $db->query("SELECT * FROM seguidores WHERE idConta = $id");
            while ($UsuarioLinha = $seguidores->fetchArray(SQLITE3_ASSOC)) {
                $alguemSegue = true;
                $usu = $UsuarioLinha['idSeguidor'];
                $sqlVerNomeSeguidor = "SELECT * FROM usuarios WHERE id=$usu";
                $res = $db->query($sqlVerNomeSeguidor);
                $usuario = $res->fetchArray(SQLITE3_ASSOC);
                echo "<p><a href='visualizarPerfil.php?sessao=".$idSessao."&id=".$usu."'>".strtoupper($usuario['nome'])."</p>";
            }
            if(!$alguemSegue){
                echo "<h5 class='caixa'>Essa conta ainda não tem seguidores</h5>";
                echo "<p>Seja o primeiro a seguir!</p>";
            }
        ?>
    </div>
</body>
</html>