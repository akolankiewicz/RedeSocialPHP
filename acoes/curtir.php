<?php
require dirname(__DIR__) . '/config.php';

$postCurtido = $_GET['postId'];
$curtidor = $_GET['curtidor'];

$sqlQtdCurtidas = "SELECT COUNT (*) FROM curtidas WHERE postCurtido=:postId and curtidor=:curtidor";
$stmt = $db->prepare($sqlQtdCurtidas);
$stmt->bindValue(':curtidor', $curtidor, SQLITE3_INTEGER);
$stmt->bindValue(':postId', $postCurtido, SQLITE3_INTEGER);
$res = $stmt->execute();
$qtd_curtidas = $res->fetchArray(SQLITE3_ASSOC);
$jaCurtiu = $qtd_curtidas["COUNT (*)"]; //caso já, o post sera descurtido, caso não, o post sera curtido

if ($jaCurtiu > 0) { //vendo se ja ou nao
    $sqlDescurtir = "DELETE FROM curtidas WHERE postCurtido=:postId and curtidor=:curtidor";
    $stmt = $db->prepare($sqlDescurtir);
    $stmt->bindValue(':curtidor', $curtidor, SQLITE3_INTEGER);
    $stmt->bindValue(':postId', $postCurtido, SQLITE3_INTEGER);
    $res = $stmt->execute();
} else {
    $sqlCurtidas = "INSERT INTO curtidas (postCurtido, curtidor) VALUES (:postCurtido, :curtidor)";
    $stmt = $db->prepare($sqlCurtidas);
    $stmt->bindValue(':postCurtido', $postCurtido);
    $stmt->bindValue(':curtidor', $curtidor);
    $result = $stmt->execute();
    if ($result) { //verificacao de curtida
        echo "BOA";
    } else {
        echo "RUIM";
    }
}
header('Location: ../index.php');
?>