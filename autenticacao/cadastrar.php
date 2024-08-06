<?php
require dirname(__DIR__) . '/config.php';

$sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
$stmt = $db->prepare($sql);
$stmt->bindValue(':email', $email);
$res = $stmt->execute();
$emailExiste = $res->fetchArray(SQLITE3_ASSOC);
$existe = $emailExiste["COUNT(*)"];

if ($existe > 0) {
    echo "<h3>O email já existe no banco de dados e já está sendo usado!!!</h3>";
    echo "<h3><a href='../cadastro.html'>Voltar para tela de cadastro</a></h3>";
} else {
    //inserindo as variaveis no banco de dados
    $stmt = $db->prepare('INSERT INTO usuarios (email, nome, senha, telefone, visitas) VALUES
        (:email, :nome, :senha, :telefone, :visitas)');
    $stmt->bindValue(':email',$_POST['email']);
    $stmt->bindValue(':nome', $_POST['nome']);
    $stmt->bindValue(':senha', password_hash($_POST['senha'], PASSWORD_DEFAULT));
    $stmt->bindValue(':telefone', $_POST['telefone']);
    $stmt->bindValue(':visitas', 0);
    $result = $stmt->execute();

    if ($result) {
        echo "Cadastro feito com sucesso!";
    } else {
        echo  "<script>window.alert('Algo deu Errado! o Usuário não foi salvo no banco')</script>";
    }
    header('Location: ../login.php');
}
