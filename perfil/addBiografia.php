<?php
require dirname(__DIR__) . '/config.php';

$idConta = $_POST['id'];//pega id da conta que recebera biografia
$biografia = $_POST['biografia'];

$trocarBio = "UPDATE usuarios SET biografia='$biografia' WHERE id='$idConta'";
$stmt = $db->query($trocarBio);//executa o comando e altera a biografia

echo "Biografia adicionada em seu perfil!";
header("Location: ../perfil.php?id=$idConta");
?>