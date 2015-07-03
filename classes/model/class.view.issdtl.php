<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vIssdtl extends DatabaseObject{
	
	protected static $table_name="vissdtl";
	protected static $db_fields = array('id', 'itemcode' ,'item' ,'isshdrid' ,'itemid' ,'qty' ,'unitcost' ,'amount');
	
	/*
	* Database related fields
	*/
	public $id;
	public $itemcode;
	public $item;
	public $isshdrid;
	public $itemid;
	public $qty;
	public $unitcost;
	public $amount;

	
	
	
	
	
	

	
}



