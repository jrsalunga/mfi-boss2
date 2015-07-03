<?php
require_once('../../lib/initialize.php');
!$session->is_logged_in() ? redirect_to("/login"): "";
//error_reporting(E_ALL);
//ini_set('display_errors','On');

$itemcats = Itemcat::find_all();

if(isset($_GET['all'])){
  $cat1 = 'ALUMINUM MATERIALS';
  $cat2 = 'SPECIAL HARDWARE';
} else {
  $cat1 = sanitize($_GET['cat1']);
  $cat2 = sanitize($_GET['cat2']);
}

if(isset($_GET['fr']) && isset($_GET['to'])){
    sanitize($_GET);
    $dr = new DateRange($_GET['fr'],$_GET['to']);
} else {
    $dr = new DateRange(NULL,NULL,false);   
}

$items = vItem::findByCategoryByDate($cat1, $cat2, $dr->fr, $dr->to);
//global $database;
//echo $database->last_query;

//echo json_encode($items);
//exit;

function itemByCategoryByDateSummary($datas ,$uf='id', $obj=TRUE){
  $arr = array();

  $lastid=0;

  if($obj) {
    foreach($datas as $data){
      //echo $data->{$uf}.'<br>';
      if(array_key_exists($data->{$uf}, $arr)) {
        
        $arr[$data->{$uf}]['enddate'] =  $data->postdate;
        $arr[$data->{$uf}]['endbal'] =  $data->currbal;
        //echo 'init array<br>';
      } else {
        $arr[$data->{$uf}]['rs'] = array();
        $arr[$data->{$uf}]['begbal'] =  $data->prevbal;
        $arr[$data->{$uf}]['begdate'] =  $data->postdate;
        $arr[$data->{$uf}]['endbal'] =  $data->currbal;
      }

      if($data->txncode=='RCP'){
          $arr[$data->{$uf}]['rcp'] += $data->qty;
        } else {
          $arr[$data->{$uf}]['isd'] += $data->qty;
        }
      //echo json_encode($data);
      array_push($arr[$data->{$uf}]['rs'], $data);
    }
  } else {
    foreach($datas as $key => $data){
      if(array_key_exists($data['bankcode'], $arr)) {       
        $arr[$data[$uf]]['totamt'] +=  $data['amount'];
      } else {
        $arr[$data[$uf]]['totamt'] =  $data['amount'];
        $arr[$data[$uf]]['checkdate'] = $data['checkdate'];
      }
    }
  }
  
  return $arr;  
}

$datas = itemByCategoryByDateSummary($items, 'id');

//echo json_encode($datas);
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
          <li class="active">
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
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main inventory-status">
        <h4>Inventory Movement</h4>

        <nav class="navbar navbar-default">
        
        <form class="navbar-form form-inline pull-right">
          
            <div class="form-group">
                <label class="sr-only" for="fr">From:</label>
                <input type="text" class="form-control" id="fr" name="fr" placeholder="YYYY-MM-DD" value="<?=$dr->fr?>">
            </div>  
            <div class="form-group">
                <label class="sr-only" for="to">To:</label>
                <input type="text" class="form-control" id="to" name="to" placeholder="YYYY-MM-DD"  value="<?=$dr->to?>">
            </div>

            
          
        <div class="form-group">
          <label for="cat1">Category </label>
            <select name="cat1"  id="cat1" class="selectpicker show-tick">
            <?php
                foreach ($itemcats as $itemcat) {
                  echo '<option ';
                  echo $_GET['cat1']==$itemcat->descriptor ? 'selected':'';
                  echo '>'.$itemcat->descriptor.'</option>';
                }
            ?>
            </select>
          </div>
          <div class="form-group">
            <label for="cat2"></label>
            <select name="cat2" id="cat2" class="selectpicker show-tick">
            <?php
                $flag = false;
                foreach ($itemcats as $itemcat) {
                  if(!$flag && isset($_GET['cat2']) && $_GET['cat2']!=$itemcat->descriptor){
                    echo '<option disabled>'.$itemcat->descriptor.'</option>';
                  } else {
                    echo '<option>'.$itemcat->descriptor.'</option>';
                    $flag = true;
                  }

                  /*
                  if(isset($_GET['to']) && $_GET['fr']!=$itemcat->descriptor){
                    if($flag && $_GET['fr']!=$itemcat->descriptor){
                       echo '<option disabled>'.$itemcat->descriptor.'</option>';
                       $flag = true;
                     } else {
                      echo '<option>'.$itemcat->descriptor.'</option>';
                     }
                   
                    
                  } else {
                    echo '<option>'.$itemcat->descriptor.'</option>';
                  }
                  */
                  
                }
            ?>
            </select>
          </div>
          <label class="checkbox-inline">
            <input type="checkbox" name="all" id="all"> All
          </label>
          <button type="submit" class="btn btn-primary">Search</button>
        </form>  
        
      </nav>


      <div class="panel-group">
        <?php
          $ctr = 0;
          foreach ($datas as $key => $value) {
            //var_dump($value[rs]);
            $i = vItem::row($key, 1);

            echo '<div class="panel panel-default">';
              echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">';
                echo '<a id="collapse-'.$ctr.'" class="collapsed" data-toggle="collapse" href="#collapse'.$ctr.'" aria-expanded="false" aria-controls="collapse'.$ctr.'">';
                echo $i.'</a></h3>';
              echo '</div>';
             // echo '';
             // echo' </div>';// end .panel-body  
              
              
        ?>
        <div class="panel-body">
              
              <div class="col-md-1">
                <a id="collapse-dm" class="btn btn-default collapsed" data-toggle="collapse" href="#collapse<?=$ctr?>" aria-expanded="false" aria-controls="collapseDM">
                  <span class="glyphicon glyphicon-menu-hamburger"></span>
                </a>
              </div>
              <div class="col-md-3 text-right">Beginning Balance: <b><?=number_format($value['begbal'],0)?></b></div>
              <div class="col-md-2 text-right">In: <b><?=number_format($value['rcp'],0)?></b></div>
              <div class="col-md-3 text-right">Out: <b><?=number_format($value['isd'],0)?></b></div>
              <div class="col-md-3 text-right">Ending Balance: <b><?=number_format($value['endbal'],0)?></b></div>
          
          <div class="collapse" id="collapse<?=$ctr?>">
          <table class="table table-striped">
          <thead>
            <tr>
              <th>Txn Refno</th>
              <th>Transaction</th>
              <th>Post Date</th>
              <th class="text-right">Quantity</th>
              <th class="text-right">Previous Balance</th>       
              <th class="text-right">Current Balance</th>
            </tr>
            
          </thead>
          <tbody>
             <?php

              

              //global $database;
              //echo $database->last_query;

              foreach ($value['rs'] as $item) {
                
                if($item->txncode=="RCP"){
                  $rcphdr = vRcphdr::find_by_field('refno', $item->txnrefno);
                  $href =  "/reports/print-stock-receipts/".$rcphdr->id;
                } else if($item->txncode=='ISD'){
                  $isdhdr = vIsdhdr::find_by_field('refno', $item->txnrefno);
                  $href =  "/reports/print-direct-issuance/".$isdhdr->id;
                 } else if($item->txncode=='ISS'){
                  $isshdr = vIsshdr::find_by_field('refno', $item->txnrefno);
                  $href =  "/reports/print-indirect-issuance/".$isshdr->id;
                } else {
                  $href = "/inventory-movement";
                }

                echo '<tr>';
                echo '<td><a href="'.$href.'" target="_blank">'. $item->txnrefno .'</a></td>';
                echo '<td>'. $item->txncode .'</td>';
                echo '<td>'. short_date($item->postdate) .'</td>';
                echo '<td class="text-right">'. number_format($item->qty, 0) .'</td>';
                echo '<td class="text-right">'. number_format($item->prevbal, 0) .'</td>';
                echo '<td class="text-right">'. number_format($item->currbal, 0) .'</td>';
                echo '</tr>';
              }
              ?>
          </tbody>
          </table>
          
          </div>  <!-- end collapse --> 

        <?php
              echo '</div>';// end .collapse
            
            echo '</div>';

            $ctr++;



          }


        ?>


        
        

       



        
      </div>
        
      

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


    $(document).ready(function(){

      daterange();
      
      $('#cat1').on('change', function(){
        var success = false;
        var that = $(this);

        $('#cat2 option').each(function(){
          //console.log(that.val()+'=='+$(this).val());
          if(that.val()==$(this).val()){
            //console.log('true');
            $('#cat2').selectpicker('val',$(this).val());
            success = true;
          } else {
           // console.log('false');
            if(!success){
              $(this).prop('disabled', true);
            } else {
              $(this).prop('disabled', false);
            }
          }
        });
        $('#cat2').selectpicker('render');
      })

      $('#all').on('click', function(){
        console.log($(this).is(':checked'));

        if($(this).is(':checked')){
          $('#cat1').prop('disabled', true).next('div').children('.btn').css('cursor', 'not-allowed').css('background-color', '#e6e6e6');
          $('#cat1').selectpicker('val', $('#cat2 option:first-child').val());
          $('#cat2').prop('disabled', true).next('div').children('.btn').css('cursor', 'not-allowed').css('background-color', '#e6e6e6');
          $('#cat2').selectpicker('val', $('#cat2 option:last-child').val());
          $('#cat1').selectpicker('render');
          $('#cat2').selectpicker('render');
        } else {
          $('#cat1').prop('disabled', false).next('div').children('.btn').css('cursor', 'pointer').css('background-color', '#fff');
          $('#cat2').prop('disabled', false).next('div').children('.btn').css('cursor', 'pointer').css('background-color', '#fff');
          $('#cat1').selectpicker('render');
          $('#cat2').selectpicker('render');
        }

      });








        


      } );
    </script>

    });
    </script>
    

  </body>
</html>