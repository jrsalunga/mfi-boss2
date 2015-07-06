<?php
require_once('../../lib/initialize.php');
!$session->is_logged_in() ? redirect_to("/login"): "";

if(isset($_GET['fr']) && isset($_GET['to'])){
    sanitize($_GET);
    $dr = new DateRange($_GET['fr'],$_GET['to']);
} else {
    $dr = new DateRange(NULL,NULL,false);   
}



if(isset($_GET['tab'])&&$_GET['tab']==='date'){

} else {
  $items = vItem::findAllStockReceiptsByDateRange($dr->fr, $dr->to);

}
//global $database;
//echo $database->last_query;
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


    <title>Modular Fusion</title>

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
          <li>
            <a href="/reports/bom-variances">Project BOM Variances</a>
          </li>
          <li>
            <a href="/reports/inventory-status">Inventory Status</a>
          </li>
          <li>
               <a href="/reports/inventory-movement">Inventory Movement</a>
              </li>
              <li>
                <a href="/reports/item-inventory">Stockcard</a>
              </li>
          <li class="active">
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
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main receipts">
      <h4>Summary of Stock Receipts <small><i>( based on issued date )</i></small></h4>


      <nav class="navbar navbar-default">
        
        <form class="navbar-form form-inline pull-right">
          <div class="form-group">
            <div class="form-group">
                <label class="sr-only" for="fr">From:</label>
                <input type="text" class="form-control" id="fr" name="fr" placeholder="YYYY-MM-DD" value="<?=$dr->fr?>">
            </div>  
            <div class="form-group">
                <label class="sr-only" for="to">To:</label>
                <input type="text" class="form-control" id="to" name="to" placeholder="YYYY-MM-DD"  value="<?=$dr->to?>">
            </div>

            <button type="submit" class="btn btn-primary">Go</button>
          </div>
        </form>
      </nav>



      <div role="tabpanel">

  <ul class="nav nav-tabs" role="tablist" id="myTab">
    <li role="presentation" <?=!isset($_GET['tab'])?'class="active"':''?>><a href="/reports/stock-receipts">All</a></li>
    <!--
    <li role="presentation" <?=(isset($_GET['tab'])&&$_GET['tab']==='date')?'class="active"':''?>><a href="/reports/stock-receipts?tab=date">by Date</a></li>
    -->
  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active">
      <?php
        if(isset($_GET['tab'])&&$_GET['tab']==='date'){
          echo 'by date';
        } else {
      ?>    
      <table id="by-category" class="table table-striped">
        <thead>
          <tr>
            <th>SR Ref No</th>
            <th>Category</th>
            <th>Item</th>
            <th class="text-right">Qty</th>
            <th>UoM</th>
            <th>PO</th>
          </tr>
        </thead>
        <tbody>
      <?php
          foreach ($items as $item) {
            echo '<tr>';
            echo '<td><a href="/reports/print-stock-receipts/'.$item->rcphdrid.'" target="_blank">'.$item->refno.'</a></td>';
            echo '<td>'.$item->catname.'</td>';
            echo '<td>'.$item->descriptor.'</td>';
            echo '<td class="text-right">'.$item->totqty.'</td>';
            echo '<td>'.$item->uom.'</td>';
            echo '<td>'.$item->porefno.'</td>';
            //echo  $item->catname .' - '.$item->descriptor.' - '. $item->totqty .' - '.$item->porefno.'<br>';
            echo '</tr>';
          }
        }
      ?>
        </tbody>
      </table>
    </div>
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

<script>

function daterange(){

  $( "#fr" ).datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#fr" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
}

$(document).ready(function(e) {
    daterange();
});
</script>

  </body>
</html>