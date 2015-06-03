<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class Bom extends DatabaseObject{
	
	protected static $table_name="bom";
	protected static $db_fields = array('id', 'projectid' ,'itemid' ,'qty' ,'unitcost' ,'amount' );
	
	/*
	* Database related fields
	*/
	public $id;
	public $projectid;
	public $itemid;
	public $qty;
	public $unitcost;
	public $amount;






	



	
	
}

