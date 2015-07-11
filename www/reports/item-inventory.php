<?php
require_once('../../lib/initialize.php');
!$session->is_logged_in() ? redirect_to("/login"): "";
//error_reporting(E_ALL);
//ini_set('display_errors','On');

if(isset($_GET['fr']) && isset($_GET['to'])){
    sanitize($_GET);
    $dr = new DateRange($_GET['fr'],$_GET['to']);
} else {
    $dr = new DateRange(NULL,NULL,false);   
}
                          
if(!empty($_GET['itemid']) || (is_uuid($_GET['itemid']))) 
  $item = Item::find_by_id(sanitize($_GET['itemid']));


//echo json_encode($item);
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

    <link href="/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap-3.3.4.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
    
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css" rel="stylesheet">
    
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
          <li class="active">
            <a href="/reports/item-inventory">Stockcard</a>
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
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main inventory-status">
        <h4>Item Stockcard</h4>

        <nav class="navbar navbar-default">
        <form class="navbar-form form-inline">
          <div class="form-group">
            <div class="form-group">
                <label class="sr-only" for="fr">From:</label>
                <input type="text" class="form-control" id="fr" name="fr" placeholder="YYYY-MM-DD" value="<?=$dr->fr?>">
            </div>  
            <div class="form-group">
                <label class="sr-only" for="to">To:</label>
                <input type="text" class="form-control" id="to" name="to" placeholder="YYYY-MM-DD"  value="<?=$dr->to?>">
            </div>

            
          </div>
          <div class="form-group pull-right">
            <div class="input-group">
              <input style="width: 400px;" type="text" class="form-control" id="search-item" placeholder="Search item..." value="<?=isset($item->descriptor)?$item->descriptor:"";?>">
              <input type="hidden" name="itemid" id="itemid" value="<?=$item->id?>"> 
              <div class="input-group-btn">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </div>
          </div> 

          
        </form>
        </nav>

      <?php
        if(!empty($item)){

        $stockcards = Stockcard::find_all_by_field_id('item', $item->id)
      ?>

      <div class="panel-group">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
              <a id="collapse-item" class="collapsed" data-toggle="collapse" href="#collapseItem" aria-expanded="false" aria-controls="collapseItem">
                <?=$item->descriptor?>
              </a>
            </h3>
          </div>
          <div class="collapse in" id="collapseItem">  
            <div class="panel-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Txn Ref No</th>
                    <th>Date</th>
                    <th>Transaction</th>
                    <th>Post Date</th>  
                    <th>Operator</th>     
                    <th class="text-right">Qty</th>
                    <th class="text-right">Prev Bal</th>
                    <th class="text-right">Curr Bal</th>
                  </tr>
                  
                </thead>
                <tbody>
                   <?php

                    

                    //global $database;
                    //echo $database->last_query;

                    foreach ($stockcards as $stockcard) {
                      if($stockcard->txncode=="RCP"){
                        $rcphdr = vRcphdr::find_by_field('refno', $stockcard->txnrefno);
                        $opr = '';
                        $href =  "/reports/print-stock-receipts/".$rcphdr->id;
                      } else if($stockcard->txncode=='ISD'){
                        $isdhdr = vIsdhdr::find_by_field('refno', $stockcard->txnrefno);
                        $opr = Operator::row($isdhdr->operatorid, 0);
                        $href =  "/reports/print-direct-issuance/".$isdhdr->id;
                       } else if($stockcard->txncode=='ISS'){
                        $isshdr = vIsshdr::find_by_field('refno', $stockcard->txnrefno);
                        $opr = Operator::row($isshdr->operatorid, 0);
                        $href =  "/reports/print-indirect-issuance/".$isshdr->id;
                      } else {
                       
                      }

                      echo '<tr>';
                      echo '<td><a href="'.$href.'" target="_blank">'. $stockcard->txnrefno .'</a></td>';
                      echo '<td>'. short_date($stockcard->txndate) .'</td>';
                      echo '<td>'. $stockcard->txncode .'</td>';
                      echo '<td>'. short_date($stockcard->postdate) .'</td>';
                      echo '<td>'. $opr .'</td>';
                      echo '<td class="text-right">'. number_format($stockcard->qty, 2) .'</td>';
                      echo '<td class="text-right">'. number_format($stockcard->prevbal, 0) .'</td>';
                      echo '<td class="text-right">'. number_format($stockcard->currbal, 0) .'</td>';
                      echo '</tr>';
                    }
                    ?>
                </tbody>
              </table>
            
            
            <div class="col-md-3"></div>
            <div class="col-md-3 text-right">Total on Hand: <b><?=number_format($item->onhand,0)?></b></div>
            <div class="col-md-3 text-right">Ave Cost: <b><?=number_format($item->avecost,2)?></b></div>
            <div class="col-md-3 text-right">Total Amount: <b><?=number_format(($item->onhand * $item->avecost),2)?></b></div> 
            </div><!-- panel-body -->   
          </div><!-- end collapse --> 
        </div>
      </div>
      

      <?php
        }
      ?>    


        
        

       



        
      
        
      

      </div>
    </div>
    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/vendors/jquery-1.11.1.min.js"></script>
    <script src="/js/vendors/jquery-ui-1.11.2.min.js"></script>
    <script src="/js/vendors/bootstrap-3.3.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
    <script src="/js/vendors/jquery.dataTables-1.10.5.min.js"></script>
    
    <script src="/js/vendors/moment-2.8.4.min.js"></script>
    <script src="/js/vendors/accounting-0.4.1.min.js"></script>


    <script type="text/javascript">
    $(document).ready(function(){
    function log( message ) {
     
      console.log(message);
      
    }
    function itemSearch(){
   
    $("#search-item").autocomplete({
      
      source: function( request, response ) {
        $.when(
          $.ajax({
              type: 'GET',
              url: "/api/search/item",
              dataType: "json",
              data: {
                maxRows: 20,
                q: request.term
              },
              success: function( data ) {
                response( $.map( data, function( item ) {
                  return {
                    label: item.code + ' - ' + item.descriptor,
                    value: item.descriptor,
                    id: item.id
                  }
                }));
              }
          })
        ).then(function(data){
          //console.log(data);
        });
      },
      minLength: 2,
      select: function(e, ui) {     
        $("#itemid").val(ui.item.id); /* set the selected id */
      },
      open: function() {
        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        $("#itemid").val('');  /* remove the id when change item */
      },
      close: function() {
        $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
      },
      focus: function (e, ui) {
        $(".ui-helper-hidden-accessible").hide();
      },
      messages: {
        noResults: '',
        results: function() {}
      }
      
    });
    }

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
    

      const i = $('#search-item');
      const strLength = i.val().length * 2;
      i.focus();
      i[0].setSelectionRange(strLength, strLength);

      itemSearch()
      daterange();        


    });
    </script>
    

  </body>
</html>