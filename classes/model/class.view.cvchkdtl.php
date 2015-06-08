<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vCvchkdtl extends DatabaseObject{
	
	protected static $table_name="vcvchkdtl";
	protected static $db_fields = array('checkno' ,'checkdate' ,'amount' ,'id' ,'refno' ,'payee' ,'posted' ,'cvhdrdate' ,'cvhdrid' ,'supplier' ,'suppliercode' ,'supplierid' ,'bank' ,'bankcode' ,'acctno' ,'bankid');
	
	/*
	* Database related fields
	*/
	public $id;
	public $checkno;
	public $checkdate;
	public $amount;
	public $refno;
	public $payee;
	public $posted;
	public $cvhdrdate;
	public $cvhdrid;
	public $supplier;
	public $suppliercode;
	public $supplierid;
	public $bank;
	public $bankcode;
	public $acctno;
	public $bankid;

	
	
	public static function find_all($order=NULL) {
		if(empty($order) || $order==NULL) {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name. " ORDER BY checkdate DESC");
		} else {
			return parent::find_by_sql("SELECT * FROM ".static::$table_name." ".$order);
		}
  	}


  	public static function by_date_range($fr, $to){

		$sql = 'select c.code as bankcode, c.descriptor as bank, c.id as bankid, c.acctno, b.checkdate, a.payee, b.checkno, b.amount ';
		$sql .= 'from cvhdr a ';
		$sql .= 'left join cvchkdtl b on a.id=b.cvhdrid ';
		$sql .= 'left join bank c on b.bankacctid=c.id ';
		$sql .= 'where b.checkdate BETWEEN "'.$fr.'" and "'.$to.'" ';
		$sql .= 'order by 2,3,4,5 ';
	
		return parent::find_by_sql($sql);
	}
}

