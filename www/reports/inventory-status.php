<?php
require_once('../../lib/initialize.php');
!$session->is_logged_in() ? redirect_to("/login"): "";


$itemcats = Itemcat::find_all();


if(isset($_GET['all'])){
  $fr = 'ALUMINUM MATERIALS';
  $to = 'SPECIAL HARDWARE';
} else {
  $fr =sanitize($_GET['fr']);
  $to = sanitize($_GET['to']);
}
$items = vItem::findByCategory($fr, $to);
//global $database;
//echo $database->last_query;

function summaryReport($datas ,$uf='id', $obj=TRUE){
  $arr = array();
  $chkctr=0;

  if($obj) {
    foreach($datas as $data){
      
      if(array_key_exists($data->catname, $arr)) {
        $arr[$data->{$uf}]['value'] +=  $data->value;
        $arr[$data->{$uf}]['onhand'] +=  $data->onhand;
       
      } else {
        $arr[$data->{$uf}]['rs'] = array();
        $arr[$data->{$uf}]['value'] =  $data->value;
        $arr[$data->{$uf}]['onhand'] =  $data->onhand;
      
      }
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

$datas = summaryReport($items, 'catname');

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
          <li class="active">
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
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main inventory-status">
        <h4>Status of Inventory <small><i>( based on posting date )</i></small></h4>

        <nav class="navbar navbar-default">
        
        <form class="navbar-form form-inline pull-right">
        <div class="form-group">
          <label for="fr">From item category </label>
            <select name="fr"  id="fr" class="selectpicker show-tick">
            <?php
                foreach ($itemcats as $itemcat) {
                  echo '<option ';
                  echo $_GET['fr']==$itemcat->descriptor ? 'selected':'';
                  echo '>'.$itemcat->descriptor.'</option>';
                }
            ?>
            </select>
          </div>
          <div class="form-group">
            <label for="to">to</label>
            <select name="to" id="to" class="selectpicker show-tick">
            <?php
                $flag = false;
                foreach ($itemcats as $itemcat) {
                  if(!$flag && isset($_GET['to']) && $_GET['to']!=$itemcat->descriptor){
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

            echo '<div class="panel panel-default">';
              echo '<div class="panel-heading">';
                echo '<h3 class="panel-title">';
                echo '<a id="collapse-'.$ctr.'" class="collapsed" data-toggle="collapse" href="#collapse'.$ctr.'" aria-expanded="false" aria-controls="collapse'.$ctr.'">';
                echo $key.'</a></h3>';
              echo '</div>';
             // echo '';
             // echo' </div>';// end .panel-body  
              
              echo '<div class="collapse in" id="collapse'.$ctr.'">';
        ?>
        <div class="panel-body">
          <table class="table table-striped">
          <thead>
            <tr>
              <th class="hidden-sm hidden-xs">Code <div>&nbsp;&nbsp;</div></th>
              <th>Item <div>&nbsp;&nbsp;</div></th>
              <th class="text-right">Ave Cost <div>&nbsp;&nbsp;</div></th>
              <th class="text-right">On Hand <div>&nbsp;&nbsp;</div></th>       
              <th class="text-right">Total Cost <div>&nbsp;&nbsp;</div></th>
            </tr>
            
          </thead>
          <tbody>
             <?php

              

              //global $database;
              //echo $database->last_query;

              foreach ($value['rs'] as $item) {
                echo '<tr>';
                echo '<td class="hidden-sm hidden-xs">'. $item->code .'</td>';
                echo '<td>'. $item->descriptor .'</td>';
                echo '<td class="text-right">'. number_format($item->avecost, 2) .'</td>';
                echo '<td class="text-right" title="'. $item->onhand .' '. $item->uom .'">'. $item->onhand .'</td>';
                echo '<td class="text-right">'. number_format($item->value, 2) .'</td>';
                echo '</tr>';
              }
              ?>
          </tbody>
          </table>
          <div class="col-md-1" title="Collapse In">
            <a id="collapse-<?=$ctr?>" class="btn btn-default collapsed" data-toggle="collapse" href="#collapse<?=$ctr?>" aria-expanded="false" aria-controls="collapse<?=$ctr?>">
              <span class="glyphicon glyphicon-menu-hamburger"></span>
            </a>
          </div>
          <div class="col-md-2"></div>
          <div class="col-md-3"></div>
          <div class="col-md-3 text-right">Total On Hand: <b><?=number_format($value['onhand'],0)?></b></div>
          <div class="col-md-3 text-right">Total Amount: <b><?=number_format($value['value'],2)?></b></div> 
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
    $(document).ready(function(){

      
      $('#fr').on('change', function(){
        var success = false;
        var that = $(this);

        $('#to option').each(function(){
          //console.log(that.val()+'=='+$(this).val());
          if(that.val()==$(this).val()){
            //console.log('true');
            $('#to').selectpicker('val',$(this).val());
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
        $('#to').selectpicker('render');
      })

      $('#all').on('click', function(){
        console.log($(this).is(':checked'));

        if($(this).is(':checked')){
          $('#fr').prop('disabled', true).next('div').children('.btn').css('cursor', 'not-allowed').css('background-color', '#e6e6e6');
          $('#fr').selectpicker('val', $('#to option:first-child').val());
          $('#to').prop('disabled', true).next('div').children('.btn').css('cursor', 'not-allowed').css('background-color', '#e6e6e6');
          $('#to').selectpicker('val', $('#to option:last-child').val());
          $('#fr').selectpicker('render');
          $('#to').selectpicker('render');
        } else {
          $('#fr').prop('disabled', false).next('div').children('.btn').css('cursor', 'pointer').css('background-color', '#fff');
          $('#to').prop('disabled', false).next('div').children('.btn').css('cursor', 'pointer').css('background-color', '#fff');
          $('#fr').selectpicker('render');
          $('#to').selectpicker('render');
        }

      });








        $('.table').DataTable({
          "order": [[ 1, "asc" ]],
          "paging": false,
          "searching": false,
          "info": false
        });


      } );
    </script>

    });
    </script>
    

  </body>
</html>