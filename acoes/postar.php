<?php
date_default_timezone_set('America/Sao_Paulo');//definindo formato de horário
require dirname(__DIR__) . '/config.php';

$conteudo = $_POST['postContent'];
$usuarioId = $_POST['id'];
$data_post = date('Y-m-d H:i:s');
$imagem = $_FILES['imagem'];

if($conteudo!=''){
    if($imagem['name']!=''){
        $nomeImagem = basename($imagem['name']);
        $caminhoImagem = '../uploads/' . $nomeImagem;

        $check = getimagesize($imagem['tmp_name']);
        if($check === false) {
            echo "O arquivo não é uma imagem.";
        }
        move_uploaded_file($imagem['tmp_name'], $caminhoImagem);

        $stmt = $db->prepare('INSERT INTO posts (conteudo, donoId, data_post, imagem)
            VALUES (:conteudo, :donoId, :data_post, :imagem)');
        $stmt->bindValue(':conteudo', $conteudo);
        $stmt->bindValue(':donoId', $usuarioId);
        $stmt->bindValue(':data_post', $data_post);//indicando nome das variaveis
        $stmt->bindValue(':imagem', $imagem['name']);

        $result = $stmt->execute();
        if($result){
            echo "Post feito com sucesso!";
        }else{
            echo  "Algo deu Errado! o post não foi feito.";
        }
    } else {
        $stmt = $db->prepare('INSERT INTO posts (conteudo, donoId, data_post) VALUES (:conteudo, :donoId, :data_post)');
        $stmt->bindValue(':conteudo', $conteudo);
        $stmt->bindValue(':donoId', $usuarioId);
        $stmt->bindValue(':data_post', $data_post);

        $result = $stmt->execute(); //variavel retorna true/false
        if($result){
            echo "Post feito com sucesso!123456";
        }else{
            echo  "Algo deu Errado! o post não foi feito";
        }
    }
} else {
    echo "não se pode postar sem nada escrito!";
}
header('Location: ../index.php')//voltando para o index
?>