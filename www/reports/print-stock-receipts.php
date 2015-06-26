<?php
include_once('../../lib/initialize.php');
include_once('../../classes/class.cleanurl.php');
error_reporting(E_ALL);
ini_set('display_errors','On');
$cleanUrl->setParts('rcphdrid');

//echo $rcphdrid;
if(is_uuid($rcphdrid)){
	$rcphdr = vRcphdr::find_by_id($rcphdrid);
	if(!$rcphdr){
		$rcphdr = vRcphdr::first('refno');
	}
} else {
	$rcphdr = vRcphdr::first('refno');
}
//$rcphdr = vrcphdr::find_by_id($rcphdrid);
//global $database;
//echo $database->last_query;
//  echo var_dump($rcphdr);


?>
<!DOCTYPE HTML>
<html lang="en-ph">
<head>
<meta charset="utf-8">
<title>Stock Receipts : <?=$rcphdr->refno?></title>
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
	<div class="isposted" style="visibility: <?=$rcphdr->posted==1?"visible":"hidden"?>">
    	<h1>Posted</h1>
    </div>
    <div class="iscancelled" style="visibility: <?=$rcphdr->cancelled==1?"visible":"hidden"?>">
    	<h1>Cancelled</h1>
    </div>
    <div id="header">
    	<div id="main-logo">
            <img src="/images/mfi-logo.png" />
        </div>
    	<div id="header-wrap">
        	
        	<h2>ModularFusion Inc</h2>
            <p>1763 Paz M. Guanzon St., Paco, 1007 Manila</p>
            <h1 class="reportLabel">Stock Receipts</h1>
        </div>		
    </div>
    <div id="body">
   		<div id="m-container">
   			<div id="hdr">
            	<div id="supplier-title">
                <?php
					#$location = Location::find_by_id($rcphdr->locationid);
				?>
                <div></div>
                </div>           	
                <table id="meta">
                	<tbody>
                    	<tr>
                        	<td>Reference #</td><td><?=$rcphdr->refno?></td>
                        </tr>
                        <tr>
                        	<td>Date</td><td><?=short_date($rcphdr->date)?></td>
                        </tr>
                        <tr>
                        	<td>Due</td><td><?=date('m/d/Y', strtotime($rcphdr->date . ' + '.$rcphdr->terms.' day'))?></td>
                        </tr>
                        <tr>
                        	<td>Supplier</td><td><?=Supplier::row($rcphdr->supplierid,1)?></td>
                        </tr>
                        <tr>
                        	<td>Supplier Ref #</td><td><?=$rcphdr->supprefno?></td>
                        </tr>
                        <tr>
                        	<td>PO Ref #</td><td><?=$rcphdr->porefno?></td>
                        </tr>
                        <tr>
                            <td>Branch</td><td><?=Branch::row($rcphdr->branchid,1)?></td>
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
					$rcpdtls = vRcpdtl::find_all_by_field_id('rcphdr',$rcphdr->id);
					foreach($rcpdtls as $rcpdtl){
						
						
						echo "<tr>";
						echo "<td>". $rcpdtl->itemcode ."</td>";
                        echo '<td>'. $rcpdtl->item."</em></td>";
                        echo '<td>'. $rcpdtl->qty .'</td>';
                        echo '<td>&#8369; '. number_format($rcpdtl->unitcost,2) ."</td>";
                        echo '<td>&#8369; '. number_format($rcpdtl->amount,2) ."</td>";
						echo "</tr>";
					}				
					?>
                    <tr>
                    	<td class="blank" colspan="0"></td>
                        <td class="blank" colspan="0"></td>
                        <td class="blank" colspan="0"></td>
                        <td class="total-line" colspan="0">Total Amount</td>
                        <td class="total-value" colspan="0">&#8369; <?=number_format($rcphdr->totamount,2)?></td>
                    </tr>
                    
                </tbody>
            </table>
    	</div>
        <div style="margin: 0 20px 50px;"><strong>Notes:</strong> <em><?=$rcphdr->notes?></em></div>
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
		$n = vRcphdr::next('refno', $rcphdr->refno);
		$p = vRcphdr::previous('refno', $rcphdr->refno);
	?>
    <div class="pager-c">
    	<ul class="pager">
          <li class="previous">
          	<?=$p?'<a href="/reports/print-stock-receipts/'.$p->id.'">Prev</a>':'<span class="disabled">Prev</span>'?>
          </li>
          <li class="next">
          	<?=$n?'<a href="/reports/print-stock-receipts/'.$n->id.'">Next</a>':'<span class="disabled">Next</span>'?>
          </li>
        </ul>
    </div>
    
</div>
</body>
</html>