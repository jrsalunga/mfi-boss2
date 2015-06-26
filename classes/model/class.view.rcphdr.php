<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vRcphdr extends DatabaseObject{
	
	protected static $table_name="rcphdr";
	protected static $db_fields = array('id', 'refno' ,'date' ,'branchid' ,'supplierid' ,'supprefno' ,'porefno' ,'terms' ,'totqty' ,'totamount' ,'notes' ,'posted' ,'cancelled' ,'printctr' ,'totline');
	
	/*
	* Database related fields
	*/
	public $id;
	public $refno;
	public $date;
	public $branchid;
	public $supplierid;
	public $supprefno;
	public $porefno;
	public $terms;
	public $totqty;
	public $totamount;
	public $notes;
	public $posted;
	public $cancelled;
	public $printctr;
	public $totline;
	
	
	
	
	
	

	
}



