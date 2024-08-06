<?php
include_once('config.php');

session_start();
!$_SESSION['auth'] && header('Location: login.php?msg=Faça Login!!!');

$idSessao = $_SESSION['id']; //id da session
$idConta = $_GET['id'];

function infoPerfil($db, $idConta){
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id=:id");
    $stmt->bindValue(':id', $idConta);
    $result = $stmt->execute();
    $infoUser = $result->fetchArray(SQLITE3_ASSOC);
    return $infoUser;
}

function totalizarSeguidores(object $banco, $id_da_conta)
{
    $sqlQtdSeguidores = "SELECT COUNT(*) FROM seguidores WHERE idConta=:idConta";
    $stmt = $banco->prepare($sqlQtdSeguidores);
    $stmt->bindValue(':idConta', $id_da_conta, SQLITE3_INTEGER);
    $res = $stmt->execute();
    $qtd_seguidores = $res->fetchArray(SQLITE3_ASSOC);
    $totalSeguidores = $qtd_seguidores['COUNT(*)'];
    return $totalSeguidores;
}

function verificarSeSeguePerfil($banco, $id_da_sessao, $id_da_conta)
{
    $sqlSegue = "SELECT COUNT (*) FROM seguidores WHERE idConta=:idConta and idSeguidor=:idSeguidor";
    $stmt = $banco->prepare($sqlSegue);
    $stmt->bindValue(':idConta', $id_da_conta);
    $stmt->bindValue(':idSeguidor', $id_da_sessao);
    $result = $stmt->execute();
    $segueaconta = $result->fetchArray(SQLITE3_ASSOC);
    $segue = $segueaconta["COUNT (*)"]; //se maior que 0, indica que segue
    return $segue;
}

$infoUser =  infoPerfil($db, $idConta);
$totalSeguidores =  totalizarSeguidores($db, $idConta);
$segue = verificarSeSeguePerfil($db, $idSessao, $idConta);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo strtoupper($infoUser['nome']) ?></title>
    <!-- bootstrap link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <a class="btn btn-warning" style="margin-top: 10px;margin-right: 5px; padding: 5px;font-size: medium;float: right">
        <?php echo "Visitas ao perfil: " . $infoUser['visitas'] ?>
    </a> <a class="btn btn-primary verperfil" href="index.php">Voltar</a>

    <section class="container">
        <h1>PERFIL DO USUÁRIO</h1>
        <?php
        //verificando se exibe biografia ou mensagem generica
        if ($infoUser['biografia']) {
            echo "<h4> Nome: " . strtoupper($infoUser['nome']) . "</h4>";
            echo "";
            echo "<div class='caixa'><p>Biografia:</p><h5>" . $infoUser['biografia'] . "</h5></div>";
        } else {
            echo "<h4>Olá, eu sou " . strtoupper($infoUser['nome']) . "</h4>";
        }
        ?>
        <br>
        <h4>Telefone: <?php echo $infoUser['telefone'] ?></h4>
        <h4>Email: <?php echo $infoUser['email'] ?></h4><br>

        <?php //aqui mostrará apenas para o dono da conta
        if ($idSessao == $idConta) {
            echo "<p>EDITAR CONTA</p>";
            echo "<p> <a class='btn btn-sm btn-primary' href='perfil/edit.php?id=$idSessao'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/>
                </svg></a>
                <a class='btn btn-sm btn-danger' href='perfil/confirmarDelete.php?id=$idSessao'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                </svg> </a> </p>";
        ?>
            <form action="perfil/addBiografia.php" method="POST">
                <p>
                    <input type="hidden" name="id" value="<?php echo $idSessao  ?>">
                    <input type="text" name="biografia">
                    <button class="btn btn-light" type="submit">Adicionar/Alterar Biografia</button>
                </p>
            </form>
        <?php

        } else {
            if ($segue > 0) {
                echo "<p><a class='btn btn-danger' href='acoes/seguir.php?id=" . $idConta . "&usu=" . $idSessao . "'>
                DEIXAR DE SEGUIR</a></p><br><br>";
            } else {
                echo "<p><a class='btn btn-success' href='acoes/seguir.php?id=" . $idConta . "&usu=" . $idSessao . "'>
                SEGUIR CONTA</a></p><br><br>";
            }
        }
        ?>
        <h4>Seguidores: <?php echo "<a href='acoes/verSeguidores.php?id=" . $idConta . "&sessao=" . $idSessao . "' class='btn btn-warning'>" . $totalSeguidores . "</a>" ?> </h4>
        <br>
        <div class="caixa">
            <h2><?php echo "Postagens de " . strtoupper($infoUser['nome']) ?></h2>
            <?php
            $postagens = $db->query("SELECT * FROM posts ORDER BY data_post DESC"); //pegando os posts mais recentes em ordem de publicação.
            $EsseUsuariotemPost = false; //determina se mostra publicacoes ou se mostra que nao tem

            while ($postLinha = $postagens->fetchArray(SQLITE3_ASSOC)) {
                if ($postLinha['donoId'] == $idConta) { //se o dono do post for o msm dono da conta
                    $EsseUsuariotemPost = True;

                    function totalizarCurtidasPost(object $banco, $id_do_post)
                    {
                        $sqlQtdCurtidas = "SELECT COUNT(*) FROM curtidas WHERE postCurtido=:postId";
                        $stmt = $banco->prepare($sqlQtdCurtidas);
                        $stmt->bindValue(':postId', $id_do_post, SQLITE3_INTEGER);
                        $res = $stmt->execute();
                        $qtd_curtidas = $res->fetchArray(SQLITE3_ASSOC);
                        return $qtd_curtidas['COUNT(*)'];
                    }

                    function verificarSeUsuarioCurtiuPost(object $banco, $id_da_sessao, $id_do_post)
                    {
                        $sqlQtdCurtidas = "SELECT COUNT (*) FROM curtidas WHERE postCurtido=:postId and curtidor=:curtidor";
                        $stmt = $banco->prepare($sqlQtdCurtidas);
                        $stmt->bindValue(':curtidor', $id_da_sessao, SQLITE3_INTEGER);
                        $stmt->bindValue(':postId', $id_do_post, SQLITE3_INTEGER);
                        $res = $stmt->execute();
                        $qtd_curtidas = $res->fetchArray(SQLITE3_ASSOC);
                        $jaCurtiu = $qtd_curtidas["COUNT (*)"]; //se maior que 0, ja curtiu
                        return $jaCurtiu;
                    }

                    $postId = $postLinha['postId']; //pega id do post para acoes que envolvam o post em si, como curtir ou ver quem curtiu
            ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="postados col-md-8">
                                <?php
                                if ($postLinha['imagem'] != null) {
                                    echo "<img src='uploads/" . $postLinha['imagem'] . "' alt='erro' height='250' class='rounded mx-auto d-block'><br>";
                                }
                                echo "<h5 style='margin-top: 5px;'>" . $postLinha['conteudo'] . "</h5>";
                                $jaCurtiu = verificarSeUsuarioCurtiuPost($db, $idSessao, $postId);
                                if ($jaCurtiu > 0) { //verifica se usuario ja curtiu ou nao
                                    echo "<a class='col-md-2 btn btn-danger' href='acoes/curtir.php?postId=" . $postId . "&curtidor=" . $idSessao . "'>Descurtir</a><br><br>";
                                } else {
                                    echo "<a class='col-md-2 btn btn-success' href='acoes/curtir.php?postId=" . $postId . "&curtidor=" . $idSessao . "'>Curtir</a><br><br>";
                                }
                                $totalCurtidasPost = totalizarCurtidasPost($db, $postId);
                                echo "<a href='acoes/quemCurtiu.php?postId=" . $postId . "&id=" . $idSessao . "' class='col-md-2 curtidas'>Curtidas: " . $totalCurtidasPost . "<a>";
                                ?>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div><br>
            <?php }
            } //fim do while
            if (!$EsseUsuariotemPost) {
                echo "<p style='font-size: 15px;'>Esse usuário não tem publicações.</p>";
            }
            ?>
        </div>
    </section>
    <br><br>
</body>
</html>