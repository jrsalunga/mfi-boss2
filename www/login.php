<?php
require_once('../lib/initialize.php');
$session->is_logged_in() ? redirect_to("/index"): "";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="/images/mfi-logo.png" />


    <title>Modular Fusion - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">


    <script src="/js/vendors/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a href="/">
              <img src="/images/mfi-logo.png" class="img-responsive header-logo">
          </a>
          <a class="navbar-brand" href="/">Modular Fusion</a>
        </div>
        
      </div>
    </nav>
    <div class="container">
      <div class="div-signin">
        <div>
          <img class="center-block img-signin img-circle img-responsive" src="/images/login-avatar.png">
        </div>
        <form class="form-signin" accept-charset="utf-8" method="POST" action="/api/AuthUserLogin">

          <label class="sr-only" for="inputEmail">Username</label>
          <input id="inputEmail" class="form-control" type="text" 
          <?php 

            echo isset($_GET['error']) ? 'value="'.$_GET['username'].'"' : 'autofocus=""';

          ?> required="" placeholder="Username" name="username">
          <label class="sr-only" for="inputPassword">Password</label>
          
          <?php
            if(isset($_GET['error'])){
              echo '<div class="has-error">';
              echo '<input id="inputPassword" class="form-control" type="password" required="" autofocus="" placeholder="Password" name="password">';
              echo '<p class="text-danger">username or password you entered is incorrect.</p>';
              echo '</div>';
            } else {
              echo '<input id="inputPassword" class="form-control" type="password" required="" placeholder="Password" name="password">';
            }
          ?>

          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/vendors/jquery-1.11.1.min.js"></script>
    <script src="/js/vendors/jquery-ui-1.11.2.min.js"></script>
    <script src="/js/vendors/bootstrap-3.3.1.min.js"></script>
    
    <script src="/js/vendors/moment-2.8.4.min.js"></script>
    <script src="/js/vendors/accounting-0.4.1.min.js"></script>

    

  </body>
</html>