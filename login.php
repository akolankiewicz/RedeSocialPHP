<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LOGIN</title>
  <style>
    body {
      text-align: center;
      margin: auto;
    }
    .centralizada {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
  </style>
  <!-- bootstrap css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
  integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <form action="autenticacao/autenticar.php" method="POST">
    <section class="centralizada">
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" id="exampleInputPassword1">
      </div><br>
      <button type="submit" class="btn btn-primary">Entrar</button>
      <a class="btn btn-warning" href="cadastro.html">Sem cadastro?</a>
      <br><br>

      <div>
        <?php //recebe a mensagem caso sair, tentar entrar sem conta, ou tentar entrar por url
        if (isset($_GET['msg'])) {
          echo "<h2>" . $_GET['msg'] . "</h2>";
        }
        ?>
      </div>
    </section>
  </form>
</body>
</html>