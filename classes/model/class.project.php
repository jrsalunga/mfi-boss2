<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class Project extends DatabaseObject{
	
	protected static $table_name="project";
	protected static $db_fields = array('id', 'code' ,'descriptor' ,'customerid' ,'salesmanid' ,'location' ,'type' ,'datestart' ,'dateend' ,'dateendx' ,'amount' ,'balance' ,'notes');
	
	/*
	* Database related fields
	*/
	public $id;
	public $code;
	public $descriptor;
	public $customerid;
	public $salesmanid;
	public $location;
	public $type;
	public $datestart;
	public $dateend;
	public $dateendx;
	public $amount;
	public $balance;
	public $notes;
	

	
	
	public static function find_all($order=NULL) {
		if(empty($order) || $order==NULL) {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name. " ORDER BY datestart DESC");
		} else {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name." ".$order);
		}
  	}
	
	
	
}

