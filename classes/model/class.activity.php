<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class Activity extends DatabaseObject{
	
	protected static $table_name="activity";
	protected static $db_fields = array('id', 'code' ,'descriptor' ,'notes' ,'stageid' ,'action' ,'projectid' ,'date' ,'userid', 'filename', 'datetime');
	
	/*
	* Database related fields
	*/
	public $id;
	public $code;
	public $descriptor;
	public $notes;
	public $stageid;
	public $action;
	public $projectid;
	public $date;
	public $userid;
	public $filename;
	public $datetime;


	public static function find_all($order=NULL) {
		if(empty($order) || $order==NULL) {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name. " ORDER BY date ASC");
		} else {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name." ".$order);
		}
  	}
	
	
}

