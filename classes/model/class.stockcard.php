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
	


}

