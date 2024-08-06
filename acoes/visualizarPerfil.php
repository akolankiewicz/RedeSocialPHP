<?php
require dirname(__DIR__) . '/config.php';

$idSessao = $_GET['sessao'];
$idConta = $_GET['id'];

if ($idSessao != $idConta) {
    $sqlVisitas = "UPDATE usuarios SET visitas=visitas+1 WHERE id='$idConta'";
    $res = $db->query($sqlVisitas);
    if ($res) {
        echo "Contou";
    } else {
        echo "Não contou";
    }
}
header("Location: ../perfil.php?id=$idConta")
?>