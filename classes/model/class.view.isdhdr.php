<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vIsdhdr extends DatabaseObject{
	
	protected static $table_name="isdhdr";
	protected static $db_fields = array('id', 'refno' ,'date' ,'branchid' ,'projectid' ,'operatorid' ,'totqty' ,'notes' ,'posted' ,'cancelled' ,'printctr' ,'totline');
	
	/*
	* Database related fields
	*/
	public $id;
	public $refno;
	public $date;
	public $branchid;
	public $projectid;
	public $operatorid;
	public $totqty;
	public $notes;
	public $posted;
	public $cancelled;
	public $printctr;
	public $totline;
	
	
	
	
	
	

	
}



