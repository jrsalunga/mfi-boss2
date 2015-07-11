<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class Stockcard extends DatabaseObject{
	
	protected static $table_name="stockcard";
	protected static $db_fields = array('id', 'itemid' ,'branchid' ,'postdate' ,'txndate' ,'txncode' ,'txnrefno' ,'qty' ,'prevbal' ,'currbal' ,'prevbalx' ,'currbalx' ,'unitcost' ,'avecost');
	
	/*
	* Database related fields
	*/
	public $id;
	public $itemid;
	public $branchid;
	public $postdate;
	public $txndate;
	public $txncode;
	public $txnrefno;
	public $qty;
	public $prevbal;
	public $currbal;
	public $prevbalx;
	public $currbalx;
	public $unitcost;
	public $avecost;




	public static function find_all_by_field_id($field=0, $id=0, $fr, $to) {
		if(!is_uuid($id) && $id==NULL) {
			return false;
		} else {

			$sql = "SELECT * FROM ".static::$table_name." WHERE {$field}id='{$id}' ";
			$sql .= "AND txndate between '".$fr." 00:00:00' and '".$to." 23:59:59' ";
			$sql .= "ORDER BY txndate ASC";

   			$result_array = static::find_by_sql($sql);
			return !empty($result_array) ? $result_array : false;
		}
  	}
	


}

