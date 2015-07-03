<?php
require_once('../../lib/initialize.php');
!$session->is_logged_in() ? redirect_to("/login"): "";
$cleanUrl->setParts('projectid');
error_reporting(E_ALL);
ini_set('display_errors','On');
$project = Project::find_by_id($projectid);

$items = vItem::ProjectDirectMaterials($project->id);

$itemss = UIBom::getBom($project->id);
$gs = groupSummary($itemss, 'itemcode', array('qty', 'qtyused', 'totamt', 'itemcost'), true);




//$gs3 = summarizeProdhdr($prodhdrs, 'opnid');
////global $database;
//echo $database->last_query;

//echo json_encode($gs3);
//exit;


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


    <title>Modular Fusion - Project DM</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap-3.3.5.min.css" rel="stylesheet">

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
      <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
          <li class="active">
            <a href="/reports/bom-variances">Project BOM Variances</a>
          </li>
          <li>
            <a href="/reports/inventory-status">Inventory Status</a>
          </li>
          <li>
                <a href="/reports/inventory-movement">Inventory Movement</a>
              </li>
          <li>
            <a href="/reports/stock-receipts">Stock Receipts Summary</a>
          </li>
          <li>
            <a href="/reports/direct-material-issuances">Direct Material Issuances</a>
          </li>
          <li>
            <a href="/reports/indirect-material-issuances">Indirect Material Issuances</a>
          </li>
          <li>
            <a href="/reports/check-funding">Check Funding</a>
          </li>
        </ul>        
      </div>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          
          <nav>
            <ul class="pager">
              <li class="previous"><a href="/reports/item-variances"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></li>
              <!--
              <li class="next"><a href="/project-print/<?=$project->id?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Print Preview</a></li>
              -->
            </ul>
          </nav>

          <div class="page-header">
            <h3><?=$project->descriptor?></h3>
            <div class="col-md-8">
              <span class="glyphicon glyphicon-map-marker"></span> 
              <?php
                echo isset($project->location) ? 
                '<a href="https://www.google.com/maps/search/'.$project->location.'" target="_blank" style="color:#333;">'.$project->location.'</a>':'';
              ?>
            </div>
            <div class="col-md-4 text-right">Contract Amount: <strong>&#8369; <?=number_format($project->amount,2)?></strong></div>
            <div class="clearfix"></div>
          </div>
          

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Direct Materials Issuance 

                <div class="btn-group btn-group-sm pull-right" role="group">
                <a data-toggle="tooltip" data-placement="top" title="back to BOM List" type="button" class="btn btn-default" href="/reports/project/<?=$project->id?>"><span class="pull-right glyphicon glyphicon-copy"></span></span></a>
                <button type="button" class="btn btn-default" disabled><span class="pull-right glyphicon glyphicon-list-alt"></button>
              </div>

               
                
                
              </h3>


            </div>
            <div class="panel-body">


              <div class="col-md-1">
                <a id="collapse-dm" class="btn btn-default collapsed" data-toggle="collapse" href="#collapseDM" aria-expanded="false" aria-controls="collapseDM">
                  <span class="glyphicon glyphicon-folder-open"></span>
                </a>
              </div>
              <div class="col-md-2 text-right">item Qty: <b><?=number_format($gs['gt_qty'],0)?></b></div>
              <div class="col-md-3 text-right">item Amount: <b><?=number_format($gs['gt_itemcost'],2)?></b></div>
              <div class="col-md-3 text-right">Actual Qty: <b><?=number_format($gs['gt_qtyused'],0)?></b></div>
              <div class="col-md-3 text-right">Actual Amount: <b><?=number_format($gs['gt_totamt'],2)?></b></div>
           
              
            </div>

            

            <div class="collapse in" id="collapseDM">
            <table class="table">
              <thead>
              </thead>
                <tr>
                  <td class="text-center">Ref No</td>
                  <!--<td class="text-center">Code</td>-->
                  <td class="text-center">Date</td>
                  <!--
                  <td class="text-center">UoM</td>
                  -->
                  <td class="text-center">Category</td>
                  <td class="text-center">Item</td>
                  <td class="text-center">Qty</td>
                  <td class="text-center">UoM</td>
                  
                  <td class="text-center">Operator</td>
                </tr>
              <tbody>
              <?php
                foreach ($items as $item) {
                  

                  echo '<tr>';
                  //echo '<td><a href="'.$_SERVER['REQUEST_URI'].'/'. $item->id .'">'. $item->catname .'</a></td>';
                  echo '<td><a href="/reports/print-direct-issuance/'.$item->id.'" target="_blank">'. $item->refno .'</a></td>';
                  echo '<td>'. short_date($item->postdate) .'</td>';
                  echo '<td>'. $item->type .'</td>';
                  echo '<td>'. $item->item .'</td>';
                  echo '<td class="text-right">'. number_format($item->qty, 0) .'</td>';
                  echo '<td>'. $item->uom .'</td>';
                  echo '<td>'. $item->operator .'</td>';
                  echo '</tr>';
                }
              ?>
              </tbody>
            </table>
            </div>

          </div>



         
          
          
          <div class="col-md-12">
          
          </div>
      </div>
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

    <script type="text/javascript">
    $(document).ready(function(){


      $("[data-toggle='tooltip']").tooltip();
   

      

      $('.collapse').on('shown.bs.collapse', function () {
        console.log($(this).parent('.panel').children('.panel-body').find('.glyphicon'));
        $(this).parent('.panel')
                .children('.panel-body')
                .find('.glyphicon')
                .removeClass("glyphicon-folder-close")
                .addClass("glyphicon-folder-open");
      });

      $('.collapse').on('hidden.bs.collapse', function () {
        console.log($(this).parent('.panel').children('.panel-body').find('.glyphicon'));
        $(this).parent('.panel')
                .children('.panel-body')
                .find('.glyphicon')
                .removeClass("glyphicon-folder-open")
                .addClass("glyphicon-folder-close");
      });
    });

    </script>

    

  </body>
</html>