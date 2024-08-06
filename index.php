<?php
include_once('config.php');

session_start();
!$_SESSION['auth'] && header('Location: login.php?msg=Faça Login!!!');

function verificarSeUsuarioCurtiu(object $banco, $id_da_sessao, $id_do_post)
{
    //verificando se deve curtir ou descurtir
    $sqlQtdCurtidas = "SELECT COUNT (*) FROM curtidas WHERE postCurtido=:postId and curtidor=:curtidor";
    $stmt = $banco->prepare($sqlQtdCurtidas);
    $stmt->bindValue(':curtidor', $id_da_sessao, SQLITE3_INTEGER);
    $stmt->bindValue(':postId', $id_do_post, SQLITE3_INTEGER);
    $res = $stmt->execute();
    $qtd_curtidas = $res->fetchArray(SQLITE3_ASSOC);
    $jaCurtiu = $qtd_curtidas["COUNT (*)"]; //se maior que 0, ja curtiu
    return $jaCurtiu;
}

function totalizarCurtidasPost(object $banco, $id_do_post)
{
    //vendo quantidade de curtidas
    $sqlQtdCurtidas = "SELECT COUNT(*) FROM curtidas WHERE postCurtido=:postId";
    $stmt = $banco->prepare($sqlQtdCurtidas);
    $stmt->bindValue(':postId', $id_do_post, SQLITE3_INTEGER);
    $res = $stmt->execute();
    $var = $res->fetchArray(SQLITE3_ASSOC);
    $totalCurtidas = $var['COUNT(*)'];
    return $totalCurtidas;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REDE SOCIAL</title>
    <!-- bootstrap link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <?php
    echo "<a class='btn btn-primary verperfil' href='perfil.php?id=" . $_SESSION['id'] . "'>Ver perfil</a>"; //botao azul para ver perfil proprio da sessao
    echo "<a class='btn btn-danger sair' href='autenticacao/logout.php'> Sair </a>"; //botao vermelho que indica saida
    echo '<h4>Seja bem vindo, ' . strtoupper($_SESSION['nome']) . "!</h4>" . PHP_EOL;
    ?>
    <br>
    <div class="container">
        <div class="row">
            <!-- barra de postagem -->
            <div class="col-md-7">
                <div class="caixa">
                    <!-- inicio do formulario de post, enctype utilizado para inserir imagens  -->
                    <form action="acoes/postar.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $_SESSION['id'] ?>" name="id">
                        <h3>O que vamos postar hoje?</h3><br>
                        <input maxlength="100" class="w-75 h-10 mt-1" type="text" placeholder="Digite aqui sua postagem... (máx: 100)" name="postContent">
                        <input type="file" name="imagem" id="imagem" class="w-75">
                        <button type="submit" class="verperfil btn btn-primary ">Postar</button>
                        <p style="font-size: 10px;">(A imagem não é obrigatória)</p>
                        <br>
                    </form>
                </div>
                <!-- Inicio da seção onde mostra todos os posts da rede social -->
                <div class="caixa">
                    <h2>Outras postagens</h2>
                    <?php
                    $postagens = $db->query("SELECT * FROM posts ORDER BY data_post DESC"); //pegando os posts mais recentes em ordem de publicação.
                    $naoTemPost = True; //define se a msg de nao tem post sera usada
                    while ($postLinha = $postagens->fetchArray(SQLITE3_ASSOC)) {
                        $naoTemPost = false; //define que nao tera a msg de nao tem post
                        $donoPostId = $postLinha['donoId'];
                        $postId = $postLinha['postId'];
                        $imagemLinha = $postLinha['imagem'];
                    ?>
                        <div class="postados">
                            <?php
                            $stmt = $db->prepare("SELECT*FROM usuarios WHERE id=:id"); //com o id da tabela post como parametro, ele procura o id do usuario que fez a postagem
                            $stmt->bindValue(':id', $donoPostId);
                            $result = $stmt->execute();
                            $usuario = $result->fetchArray(SQLITE3_ASSOC); //transforma as informaçoes em escrita
                            $perfil = $usuario['id'];
                            //se existir imagem no post, mostrar
                            if ($imagemLinha != null) {
                                echo "<img src='uploads/$imagemLinha' alt='erro' height='250' class='rounded mx-auto d-block'><br>";
                            }
                            //printando informações
                            echo "<h5 style='margin-top: 5px;'>" . ($postLinha['conteudo']) . "</h5>";
                            echo "<p class='nomeUsuario'>por <a title='Ver perfil' href='acoes/visualizarPerfil.php?sessao=" . $_SESSION['id'] . "&id=" . $perfil . "'>" . strtoupper($usuario['nome']) . "</a></p>";

                            $usuarioLogadoCurtiu = verificarSeUsuarioCurtiu($db, $_SESSION['id'], $postId);
                            if ($usuarioLogadoCurtiu > 0) {
                                echo "<a class='mt-4 col-md-2 btn btn-danger' href='acoes/curtir.php?postId=" . $postId . "&curtidor=" . $_SESSION['id'] . "'>Descurtir</a><br><br>";
                            } else {
                                echo "<a class='mt-4 col-md-2 btn btn-success' href='acoes/curtir.php?postId=" . $postId . "&curtidor=" . $_SESSION['id'] . "'>Curtir</a><br><br>";
                            }
                            //exibe curtidas no post e a tag anchor direciona para quem curtiu a publicação
                            $totalCurtidas = totalizarCurtidasPost($db, $postId);
                            echo "<p class='col-md-3 curtidas'><a href='acoes/quemCurtiu.php?postId=" . $postId . "'>Curtidas: " . $totalCurtidas . "</a><p>";
                            ?>
                        </div>
                    <?php } //fechamento do while
                    if ($naoTemPost) {
                        echo "<p>Ainda não tem nada publicado em sua rede.</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-5 caixa">
                <!-- inicio da listagem de usuários -->
                <h3 class="mt-2">Também usam essa rede</h3>
                <table>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                    <?php
                    $usuarios = $db->query("SELECT * FROM usuarios ORDER BY id DESC");
                    //enquanto tiver o que usuarios, mostrar
                    while ($UsuarioLinha = $usuarios->fetchArray(SQLITE3_ASSOC)) {
                        echo "<tr>";
                        if ($UsuarioLinha['id'] == $_SESSION['id']) { //se o usuario for ele, indica que é sua conta
                            echo "<th>" . strtoupper($UsuarioLinha['nome']) . " (Você)</th>";
                            echo "<th> <a href='perfil.php?id=" . $UsuarioLinha['id'] . "'>Visitar <b>seu</b> perfil </a></th>";
                        } else {
                            echo "<th>" . strtoupper($UsuarioLinha['nome']) . "</th>";
                            echo "<th> <a href='acoes/visualizarPerfil.php?sessao=" . $_SESSION['id'] . "&id=" . $UsuarioLinha['id'] . "'>Visitar perfil</a></th>";
                            //acessa perfil de usuario a partir do id e já contabiliza a visita do perfil
                        }
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
   </div><br>
</body>
</html>