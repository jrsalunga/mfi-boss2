<?php
require_once('../lib/initialize.php');
$session->is_logged_in() ? redirect_to("/index"): "";
$cleanUrl->setParts('projectid');

$project = Project::find_by_id($projectid);


$boms = UIBom::getBom($project->id);
$gs = groupSummary($boms, 'itemcode', array('qty', 'qtyused', 'totamt', 'bomcost'), true);


$apvdtls = vApvdtl::find_all_by_field_id('project', $project->id);
$gs2 = groupSummary($apvdtls, 'account', array('amount'), true);

?>
<!DOCTYPE html>
<html lang="en-ph">
<head>
<meta charset="utf-8">
<title>MFI Project</title>
<link rel="shortcut icon" type="image/x-icon" href="/images/mfi-logo.png" />

<link rel="stylesheet" href="/css/print.css">

<style media="screen">
#page-wrap {
    background-color: #FFFFFF;
    margin-left: auto;
    margin-right: auto;
    width: 814px;
    position:relative;
    
    border: 1px solid #888888;
    margin-top: 20px;
    margin-bottom: 30px;
    
    min-height: 1046px;
    
    
    -webkit-box-shadow:rgba(0, 0, 0, 0.496094) 0 0 10px;
	-moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  
}
</style>
<style media="print">
#page-wrap {
    background-color: #FFFFFF;
    margin-left: auto;
    margin-right: auto;
    width: 814px;
    position:relative;
    
    margin-top: 0;
    margin-bottom: 0;
    /*
    border: none;
    height: 1046px;
	*/
/*	border: 1px solid #F00; */
    min-height: 1054px;
}

#settings-dialog {
	display:none;
}
</style>


</head>
<body>


<div id="page-wrap">
    <div id="header">
    	<div id="main-logo">
            <img src="/images/mfi-logo.png" />
        </div>
    	<div id="header-wrap">
        	
        	<h2>Modular Fusion Inc.</h2>
            <p>#1763 Paz M. Guanzon St., Paco, Manila</p>
            <h1 class="reportLabel">Project Costing</h1>
        </div>		
    </div>
    <div id="body">
   		<div id="m-container">
   			<div id="hdr">
                <p>Project: <?=$project->descriptor?></p>
                <p>Location: <?=$project->location?></p>
            </div>

            <table class="table">
              <thead>

              </thead>
                <tr>
                  <td class="text-center">Category</td>

                  <td class="text-center">Item</td>
                  <td class="text-center">UoM</td>
                  <td class="text-center">Ave Cost</td>
                  <td class="text-center">BoM</td>
                  <td class="text-center">BoM Cost</td>
                  <td class="text-center">Actual</td>
                  <td class="text-center">Actual Cost</td>
                </tr>
              <tbody>
              <?php
                foreach ($boms as $bom) {
                  if($bom->qty > $bom->qtyused)
                    $c = 'info';
                  else if($bom->qty < $bom->qtyused)
                    $c = 'warning';
                  else 
                    $c = '';

                  echo '<tr class="'.$c.'">';
                  echo '<td>'. $bom->catname .'</td>';
                  echo '<td>'. $bom->itemname .'</td>';
                  echo '<td>'. $bom->uom .'</td>';
                  echo '<td class="text-right">'. number_format($bom->avecost, 2) .'</td>';
                  echo '<td class="text-right">'. number_format($bom->qty, 0) .'</td>';
                  echo '<td class="text-right">'. number_format($bom->bomcost, 2) .'</td>';
                  echo '<td class="text-right">'. number_format($bom->qtyused, 0) .'</td>';
                  echo '<td class="text-right">'. number_format($bom->totamt, 2) .'</td>';
                  echo '</tr>';
                }
              ?>
              </tbody>
            </table>
        </div>    
            	
    </div>
    <div id="footer">
    	<div>&nbsp;</div>
    </div>
    
    
    <!--
    <div style="position:absolute; bottom: 0px;">
    	test
    </div>
    -->
</div>
<div id="settings-dialog" class="show">
	<div>
    	<a href="javascript:window.print()" class="btn btn-default print-preview" title="Print Preview">
        <span class="glyphicon glyphicon-print"></span> 
        Print Preview</a>
  	</div>
    
   
        
    
    <div class="pager-c">
    	<ul class="pager">
          <li class="previous">
          	<?=$p?'<a href="/reports/check-print/'.$p->id.'" title="Previous Record">Prev</a>':'<span class="disabled">Prev</span>'?>
		  </li>
          <li class="next">
          	<?=$n?'<a href="/reports/check-print/'.$n->id.'" title="Next Record">Next</a>':'<span class="disabled">Next</span>'?>         
          </li>
        </ul>
    </div>
    
    
    
</div>





<script src="/js/vendors/jquery-1.11.1.min.js"></script>
<script src="/js/vendors/jquery-ui-1.11.2.min.js"></script>

<script language="javascript">
function floatMe(){
    set = $(document).scrollTop()+25;
    $('#settings-dialog').animate({top:set+'px'},{duration:0,queue:false});
}
function fixPrintSettings(){
    if(parseInt($(document).width()) <= 1100){
        $('.print-preview').html('<span class="glyphicon glyphicon-print"></span>');
        $('.previous a').html('<span class="glyphicon glyphicon-chevron-left"></span>').css('margin-bottom','10px');
        $('.next a').html('<span class="glyphicon glyphicon-chevron-right"></span>');
        $('.pager li').css('display', 'block');
        //console.log('fix');
    } else {
        $('.print-preview').html('<span class="glyphicon glyphicon-print"></span> Print Preview');
        $('.previous a').html('Prev');
        $('.next a').html('Next');
        $('.pager li').css('display', 'inline');
    }   
}

$(document).ready(function(){
    floatMe();
    $(window).scroll(floatMe);
    fixPrintSettings();
    $(window).resize(fixPrintSettings);
});
</script>
</body>
</html>