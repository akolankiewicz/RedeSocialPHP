<?php
require dirname(__DIR__) . '/config.php';

$idConta = $_GET['id'];
$idSeguidor = $_GET['usu'];

$sqlSegue = "SELECT COUNT (*) FROM seguidores WHERE idConta=:idConta and idSeguidor=:idSeguidor";
$stmt = $db->prepare($sqlSegue);
$stmt->bindValue(':idConta', $idConta);
$stmt->bindValue(':idSeguidor', $idSeguidor);
$result = $stmt->execute();
$segueaconta = $result->fetchArray(SQLITE3_ASSOC);
$segue = $segueaconta["COUNT (*)"];//caso maior que 0, remove seguidor

if ($segue > 0) {
    $sqlRemoveSeguidor = "DELETE FROM seguidores WHERE idConta=:idConta and idSeguidor=:idSeguidor";
    $stmt = $db->prepare($sqlRemoveSeguidor);
    $stmt->bindValue(':idConta', $idConta);
    $stmt->bindValue(':idSeguidor', $idSeguidor);
    $result = $stmt->execute();
    if($result){
        echo "Você deixou de seguir";
    } else {
        echo "Deu erro! você ainda segue a conta";
    }
} else {
    $sqlAddSeguidor = "INSERT INTO seguidores (idConta, idSeguidor) VALUES (:idConta, :idSeguidor)";
    $stmt = $db->prepare($sqlAddSeguidor);
    $stmt->bindValue(':idConta', $idConta);
    $stmt->bindValue(':idSeguidor', $idSeguidor);
    $res = $stmt->execute();
    if ($res) {
        echo "deu boa";
    } else {
        echo "deu ruim";
    }
}
header("Location: ../perfil.php?id=$idConta");//voltando para perfil de onde o usuario quis seguir
?>