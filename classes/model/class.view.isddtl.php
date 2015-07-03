<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vIsddtl extends DatabaseObject{
	
	protected static $table_name="visddtl";
	protected static $db_fields = array('id', 'itemcode' ,'item' ,'isdhdrid' ,'itemid' ,'qty' ,'unitcost' ,'amount');
	
	/*
	* Database related fields
	*/
	public $id;
	public $itemcode;
	public $item;
	public $isdhdrid;
	public $itemid;
	public $qty;
	public $unitcost;
	public $amount;

	
	
	
	
	
	

	
}



