<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class User extends DatabaseObject{
	
	protected static $table_name="user";
	protected static $db_fields = array('id', 'code' ,'descriptor' ,'password', 'admin');
	
	/*
	* Database related fields
	*/
	public $id;
	public $code;
	public $descriptor;
	public $password;
	public $admin;
	

	
	
	public static function find_all($order=NULL) {
		if(empty($order) || $order==NULL) {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name. " ORDER BY lastname ASC, firstname ASC");
		} else {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name." ".$order);
		}
  	}
	
	public static function auth($u=NULL, $p=NULL){
		global $database;
		if($u!=NULL && $p!=NULL){
			$sql = "SELECT * FROM ".static::$table_name. " WHERE code='". $u ."' AND password = '". $p ."' LIMIT 1";
			$result = self::find_by_sql($sql);	
		}

		return ($database->affected_rows() >= 1) ? array_shift($result) : false ;
		
	}
	
}

