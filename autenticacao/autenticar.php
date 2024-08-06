<?php
require dirname(__DIR__) . '/config.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $db->prepare("SELECT id, nome, email, senha FROM usuarios  WHERE email = :email");
$stmt->bindValue(':email', $email);
$result = $stmt->execute();//procura se o email existe no banco

if(!$result){
    echo "Usuario inexistente";
    header('Location: ../login.php?msg=Email inexistente');
    exit;
} else {
    $usuario = $result->fetchArray(SQLITE3_ASSOC);
    if(password_verify($senha, $usuario['senha'])){//verifica se existe senha
        session_start();
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['auth'] = true;
        header('Location: ../index.php');//manda pro sistema
    } else {
        header('Location: ../login.php?msg=Usuário não encontrado');//recusa o login e exibe msg
    }
}
?>