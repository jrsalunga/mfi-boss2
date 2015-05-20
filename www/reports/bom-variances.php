<?php
require_once('../../lib/initialize.php');
!$session->is_logged_in() ? redirect_to("/login"): "";
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


    <title>Modular Fusion</title>

    <link href="/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">

    <!--
    <script src="/js/vendors/ie-emulation-modes-warning.js"></script>
    -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/vendors/jquery-1.11.1.min.js"></script>
    <script src="/js/vendors/jquery-ui-1.11.2.min.js"></script>
    <script src="/js/vendors/bootstrap-3.3.1.min.js"></script>
    <script src="/js/vendors/jquery.dataTables-1.10.5.min.js"></script>
    
    <script src="/js/vendors/moment-2.8.4.min.js"></script>
    <script src="/js/vendors/accounting-0.4.1.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {


        $('#example').DataTable({
          "order": [[ 1, "desc" ]],
          stateSave: true
        });

        //$('div.dataTables_filter input').focus();

      } );
    </script>


  </head>

  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/">
              <img src="/images/mfi-logo.png" class="img-responsive header-logo">
          </a>
          <a class="navbar-brand" href="/">Modular Fusion</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Home</a></li>
            <li class="active"><a href="/reports">Reports</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <span class="glyphicon glyphicon-cog"></span> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="/settings">Settings</a></li>
                <li><a href="/logout">Log Out</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
  </nav>



    <div class="container-fluid">
      <div class="row">
          <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
              <li class="active">
                <a href="/reports/bom-variances">Project BOM Variances</a>
              </li>
              <li>
                <a href="/reports/inventory-status">Inventory Status</a>
              </li>
            </ul>        
          </div>
          
          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <table id="example" class="display" cellspacing="0" width="100%">
              <thead>
                <th>Projects <div>&nbsp&nbsp</div></th>
                <th>Amount <div>&nbsp&nbsp</div></th>
                <th>Date Start <div>&nbsp&nbsp</div></th>
              </thead>
              <tbody>
              <?php


                $projects = Project::find_all();

                
                /*
                echo '<div class="list-group">';
                foreach ($projects as $project) {
                  echo '<a href="project/'.$project->id.'" class="list-group-item">'.$project->descriptor.'</a>';
                }
                echo '</div>';
                */



                foreach ($projects as $project) {
                  echo '<tr>';
                  echo '<td><a href="/reports/project/'.$project->id.'" style="display: block;" title="'.$project->code.'">'.$project->descriptor.'</a></td>';
                  echo '<td>'.number_format($project->amount,2).'</td>';
                  echo '<td>'.$project->datestart.'</td>';
                  echo '</tr>';
                }


              ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
    


    

  </body>
</html>