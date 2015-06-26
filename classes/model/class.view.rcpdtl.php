<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vRcpdtl extends DatabaseObject{
	
	protected static $table_name="vrcpdtl";
	protected static $db_fields = array('id', 'itemcode' ,'item' ,'rcphdrid' ,'itemid' ,'qty' ,'unitcost' ,'amount');
	
	/*
	* Database related fields
	*/
	public $id;
	public $itemcode;
	public $item;
	public $rcphdrid;
	public $itemid;
	public $qty;
	public $unitcost;
	public $amount;

	
	
	
	
	
	

	
}



