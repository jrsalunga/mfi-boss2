<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class Prodhdr extends DatabaseObject{
	
	protected static $table_name="prodhdr";
	protected static $db_fields = array('id', 'refno' ,'date' ,'projectid' ,'opnid' ,'operatorid' ,'helper1id' ,'helper2id' ,'machineid' ,'tstart' ,'tstop' ,'totwboard' ,'totcboard' ,'totparts' ,'notes' ,'posted' ,'cancelled' ,'printctr' ,'totline' ,'uidcreate' ,'uidedit' ,'uidprint' ,'uidpost');
	
	/*
	* Database related fields
	*/
	public $id;
	public $refno;
	public $date;
	public $projectid;
	public $opnid;
	public $operatorid;
	public $helper1id;
	public $helper2id;
	public $machineid;
	public $tstart;
	public $tstop;
	public $totwboard;
	public $totcboard;
	public $totparts;
	public $notes;
	public $posted;
	public $cancelled;
	public $printctr;
	public $totline;
	public $uidcreate;
	public $uidedit;
	public $uidprint;
	public $uidpost;


	
	
	public static function find_all($order=NULL) {
		if(empty($order) || $order==NULL) {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name. " ORDER BY ordinal ASC");
		} else {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name." ".$order);
		}
  	}
	
	
}

