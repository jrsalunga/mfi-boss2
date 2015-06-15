<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vRcpvhdr extends DatabaseObject{
	
	protected static $table_name="rcpvhdr";
	protected static $db_fields = array('id', 'refno' ,'date' ,'branchid' ,'supplierid' ,'supprefno' ,'porefno' ,'terms' ,'totqty' ,'totamount' ,'notes' ,'posted' ,'cancelled' ,'printctr' ,'totline');
	
	/*
	* Database related fields
	*/
	public $id;
	public $refno;
	public $date;
	public $branchid;
	public $supplierid;
	public $supprefno;
	public $porefno;
	public $terms;
	public $totqty;
	public $totamount;
	public $notes;
	public $posted;
	public $cancelled;
	public $printctr;
	public $totline;
	
	
	
	public static function queryReport($to, $from){
		$sql = "SELECT * FROM vapvhdr WHERE due BETWEEN '". $to ."' AND '". $from ."' ORDER BY due ASC";
		
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		
	}
	
	
	public static function getDue($duedate, $posted){
		if(is_int($posted) && $posted==0 || $posted==1){
			$sql = "SELECT * FROM vapvhdr WHERE due <= '". $duedate ."' AND posted = ". $posted ." ORDER BY due ASC";
		} else {
			$sql = "SELECT * FROM vapvhdr WHERE due <= '". $duedate ."' ORDER BY due ASC";
		}
		
		
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		
	}
	
	

	
}



