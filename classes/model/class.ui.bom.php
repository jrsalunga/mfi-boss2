<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class UIBom extends DatabaseObject{
	
	protected static $table_name="bom";
	protected static $db_fields = array('id', 'catcode' , 'catname', 'itemcode' ,'itemname', 'avecost', 'bomcost', 'uom', 'qty', 'qtyused', 'totamt', 'itemid');
	
	/*
	* Database related fields
	*/
	public $id;
	public $catcode;
	public $catname;
	public $itemcode;
	public $itemname;
	public $itemid;
	public $avecost;
	public $bomcost;
	public $uom;
	public $qty;
	public $qtyused;
	public $totamt;






	public static function getBom($projectid){

		$sql = "select c.code as catcode, c.descriptor as catname, b.code as itemcode, ";
		$sql .= "b.descriptor as itemname, b.uom, b.avecost, a.qty, a.itemid, ";
		$sql .= "(b.avecost * a.qty) as bomcost, ";
		$sql .= "(select sum(y.qty) from isdhdr x join isddtl y on x.id=y.isdhdrid ";
	  	$sql .= "where x.projectid='".$projectid."' and y.itemid=a.itemid) as qtyused, ";
	  	$sql .= "((select sum(y.qty) from isdhdr x join isddtl y on x.id=y.isdhdrid ";
	  	$sql .= "where x.projectid='".$projectid."' and y.itemid=a.itemid) ";
		$sql .= " * b.avecost) as totamt, a.id ";
		$sql .= "from bom a ";
		$sql .= "left join item b on a.itemid=b.id ";
		$sql .= "left join itemcat c on b.itemcatid=c.id ";
		$sql .= "where a.projectid='".$projectid."' ";
		$sql .= "order by catname, itemname ";

		$result_array = static::find_by_sql($sql);
		
		return !empty($result_array) ? $result_array : false;



	}




	
	
}

