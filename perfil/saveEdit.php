<?php
require dirname(__DIR__) . '/config.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$sqlUpdate = "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone' WHERE id='$id'";
$result = $db->query($sqlUpdate);//executando update

header("Location: ../perfil.php?id=$id");
?>