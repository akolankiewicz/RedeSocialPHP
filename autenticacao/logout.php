<?php
session_start();
session_destroy();// destroi a sessao
header('Location: ../login.php?msg=Você saiu!')//joga pro login e usa msg de saida
?>