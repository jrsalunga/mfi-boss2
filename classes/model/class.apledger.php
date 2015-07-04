<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class Apledger extends DatabaseObject{
	
	protected static $table_name="apledger";
	protected static $db_fields = array('id', 'supplierid' ,'postdate' ,'txndate' ,'txncode' ,'txnrefno' ,'amount' ,'prevbal' ,'currbal');
	
	/*
	* Database related fields
	*/
	public $id;
	public $supplierid;
	public $postdate;
	public $txndate;
	public $txncode;
	public $txnrefno;
	public $amount;
	public $prevbal;
	public $currbal;
	


}

