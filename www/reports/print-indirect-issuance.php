<?php
include_once('../../lib/initialize.php');
include_once('../../classes/class.cleanurl.php');
//error_reporting(E_ALL);
//ini_set('display_errors','On');
$cleanUrl->setParts('isshdrid');

//echo $isshdrid;
if(is_uuid($isshdrid)){
	$isshdr = vIsshdr::find_by_id($isshdrid);
	if(!$isshdr){
		$isshdr = vIsshdr::first('refno');
	}
} else {
	$isshdr = vIsshdr::first('refno');
}
//$isshdr = visshdr::find_by_id($isshdrid);
//global $database;
//echo $database->last_query;
//  echo var_dump($isshdr);


?>
<!DOCTYPE HTML>
<html lang="en-ph">
<head>
<meta charset="utf-8">
<title>Indirect Material Issuance : <?=$isshdr->refno?></title>
<link rel="shortcut icon" type="image/x-icon" href="/images/mfi-logo.png" />

<link rel="stylesheet" href="/css/print.css">

<script src="/js/vendors/jquery-1.10.1.min.js"></script>
<script src="/js/vendors/jquery-ui-1.10.3.js"></script>

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
#footer.bottom {
    position: relative;
	color: #000;
}
</style>


</head>
<body>


<div id="page-wrap">
	<div class="isposted" style="visibility: <?=$isshdr->posted==1?"visible":"hidden"?>">
    	<h1>Posted</h1>
    </div>
    <div class="iscancelled" style="visibility: <?=$isshdr->cancelled==1?"visible":"hidden"?>">
    	<h1>Cancelled</h1>
    </div>
    <div id="header">
    	<div id="main-logo">
            <img src="/images/mfi-logo.png" />
        </div>
    	<div id="header-wrap">
        	
        	<h2>ModularFusion Inc</h2>
            <p>1763 Paz M. Guanzon St., Paco, 1007 Manila</p>
            <h1 class="reportLabel">Indirect Material Issuance</h1>
        </div>		
    </div>
    <div id="body">
   		<div id="m-container">
   			<div id="hdr">
            	<div id="supplier-title">
                <?php
					#$location = Location::find_by_id($isshdr->locationid);
				?>
                <div></div>
                </div>           	
                <table id="meta">
                	<tbody>
                    	<tr>
                        	<td>Reference #</td><td><?=$isshdr->refno?></td>
                        </tr>
                        <tr>
                        	<td>Date</td><td><?=short_date($isshdr->date)?></td>
                        </tr>
                        <tr>
                        	<td>Operator</td><td><?=Operator::row($isshdr->operatorid,1)?></td>
                        </tr>
                        <tr>
                            <td>Branch</td><td><?=Branch::row($isshdr->branchid,1)?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="clear:both"></div>
            </div>
            <table id="items">
            	<thead>
                	<tr>
                    	<th>Item Code</th>
                        <th>Item</th>  
                        <th>Qty</th>   
                        <th>Unit Cost</th>          
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                	<?php
					$rcpdtls = vIssdtl::find_all_by_field_id('isshdr',$isshdr->id);
                    $totamount = 0;
					foreach($rcpdtls as $rcpdtl){
						
						
						echo "<tr>";
						echo "<td>". $rcpdtl->itemcode ."</td>";
                        echo '<td>'. $rcpdtl->item."</em></td>";
                        echo '<td>'. $rcpdtl->qty .'</td>';
                        echo '<td>&#8369; '. number_format($rcpdtl->unitcost,2) ."</td>";
                        echo '<td>&#8369; '. number_format($rcpdtl->amount,2) ."</td>";
						echo "</tr>";

                        $totamount += $rcpdtl->amount;
					}				
					?>
                    <tr>
                    	<td class="blank" colspan="0"></td>
                        <td class="blank" colspan="0"></td>
                        <td class="blank" colspan="0"></td>
                        <td class="total-line" colspan="0">Total Amount</td>
                        <td class="total-value" colspan="0">&#8369; <?=number_format($totamount,2)?></td>
                    </tr>
                    
                </tbody>
            </table>
    	</div>
        <div style="margin: 0 20px 50px;"><strong>Notes:</strong> <em><?=$isshdr->notes?></em></div>
    </div>
    <div id="footer" class="bottom">
    	<div>
        
        </div>
    </div>
</div>
<div id="settings-dialog" class="show">
	<div>
    	<a href="javascript:window.print()" class="btn btn-default print-preview">
        <span class="glyphicon glyphicon-print"></span> 
        Print Preview</a>
  	</div>
    <?php
		$n = vIsshdr::next('refno', $isshdr->refno);

		$p = vIsshdr::previous('refno', $isshdr->refno);
        //global $database;
        //echo $database->last_query;
	?>
    <div class="pager-c">
    	<ul class="pager">
          <li class="previous">
          	<?=$p?'<a href="/reports/print-indirect-issuance/'.$p->id.'">Prev</a>':'<span class="disabled">Prev</span>'?>
          </li>
          <li class="next">
          	<?=$n?'<a href="/reports/print-indirect-issuance/'.$n->id.'">Next</a>':'<span class="disabled">Next</span>'?>
          </li>
        </ul>
    </div>
    
</div>
</body>
</html>