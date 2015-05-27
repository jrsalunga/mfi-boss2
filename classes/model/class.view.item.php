<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vItem extends DatabaseObject{
	
	protected static $table_name="item";
	protected static $db_fields = array('id', 'code' ,'descriptor' ,'type' ,'itemcatid' ,'uom' ,'longdesc' ,'onhand' ,'minlevel' ,'maxlevel' ,'reorderqty' ,'avecost', 'value', 'catcode' ,'catname');
	
	/*
	* Database related fields
	*/
	public $id;
	public $code;
	public $descriptor;
	public $type;
	public $itemcatid;
	public $uom;
	public $longdesc;
	public $onhand;
	public $minlevel;
	public $maxlevel;
	public $reorderqty;
	public $avecost;

	public $catcode;
	public $catname;
	public $value;




	public static function find_all($order=NULL) {
		if(empty($order) || $order==NULL) {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name. " ORDER BY descriptor ASC");
		} else {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name." ".$order);
		}
  	}

  	public static function filterAll($key=NULL, $value=NULL){  		
  		echo json_encode(static::$recordset);	
  	}


	public static function findByCategory($cat1=NULL, $cat2=NULL){
		$sql = 'select b.code as catcode, b.descriptor as catname, a.code, a.descriptor, a.type, a.uom, a.onhand, a.avecost, a.onhand*a.avecost as value ';
		$sql .= 'from item a left join itemcat b on a.itemcatid=b.id ';
		$sql .= "where b.descriptor>='". $cat1 ."' and b.descriptor<='". $cat2."' ";
		$sql .= 'order by catname, a.descriptor';
		return parent::find_by_sql($sql);
	}
	
}

