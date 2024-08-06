<?php
require dirname(__DIR__) . '/config.php';

$idRemove = $_GET['id']; //pega id do usuario que apagou a conta

$sqlVerificaImagem = "SELECT * FROM posts WHERE donoId=$idRemove";
$variaveisBanco = $db->query($sqlVerificaImagem);
$variaImagem = $variaveisBanco->fetchArray(SQLITE3_ASSOC);
$imagem = $variaImagem['imagem'];
$caminho_imagem = "../uploads/".$imagem; //mostra o caminho do delete.php ate uploads
unlink($caminho_imagem);

$sqlDeletePost = "DELETE FROM posts WHERE donoId=:id";
$stmt = $db->prepare($sqlDeletePost);
$stmt->bindValue(':id', $idRemove);
$resultDeletePost = $stmt->execute();
if ($resultDeletePost) {
    echo "Posts Excluídos" . PHP_EOL;
} else {
    echo  "Algo deu Errado! o post não foi excluído do banco".PHP_EOL;
}

$sqlDeletePost = "DELETE FROM seguidores WHERE idConta=:idC OR idSeguidor=:idS";
$stmt = $db->prepare($sqlDeletePost);
$stmt->bindValue(':idC', $idRemove);
$stmt->bindValue(':idS', $idRemove);
$resultDeletePost = $stmt->execute();
if ($resultDeletePost) {
    echo "Seguidores Excluídos" . PHP_EOL;
} else {
    echo  "Algo deu Errado! os seguidores não foram excluídos do banco".PHP_EOL;
}

$sqlDeleteCurtida = "DELETE FROM curtidas WHERE curtidor=:curtidor";
$stmt = $db->prepare($sqlDeleteCurtida);
$stmt->bindValue(':curtidor', $idRemove);
$resultDeleteCurtida = $stmt->execute();
if ($resultDeleteCurtida) {
    echo "Curtidas Excluídas" . PHP_EOL;
} else {
    echo  "Algo deu Errado! a curtida não foi excluído do banco".PHP_EOL;
}

$sqlDeleteUsuario = "DELETE FROM usuarios WHERE id=$idRemove";
$resultDeleteUsuario = $db->query($sqlDeleteUsuario); //verifica se deletou ou nao para fazer verificação
if ($resultDeleteUsuario) {
    echo "Usuario Excluído" . PHP_EOL;
} else {
    echo  "Algo deu Errado! o Usuário não foi excluído do banco". PHP_EOL;
}

/*zerar tabelas quando vazias pro id voltar a ser 1 */
$tabelas = ['seguidores', 'curtidas', 'posts', 'usuarios'];
foreach ($tabelas as $tabela)
{
    $verificar = $db->querySingle("SELECT COUNT(*) as count from '$tabela'");
    if ($verificar == 0) {
        $sqlZerar = "DELETE FROM `sqlite_sequence`";
        $zerado = $db->query($sqlZerar);
    }
}

include_once('../autenticacao/logout.php');//faz logout
header("Location: ../index.php"); //retorna a pagina inicial
?>