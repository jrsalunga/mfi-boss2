<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');
require_once(ROOT.DS.'classes'.DS.'model'.DS.'class.apvdtl.php');

class Apvhdr extends DatabaseObject{
	
	protected static $table_name="apvhdr";
	protected static $db_fields = array('id', 'refno' ,'date' ,'locationid' ,'supplierid' ,'supprefno' ,'porefno' ,'terms' ,'notes', 'totline','totqty' ,'totamount' ,'totdebit' ,'totcredit', 'balance' ,'posted', 'cancelled');
	
	/*
	* Database related fields
	*/
	public $id;
	public $refno;
	public $date;
	public $locationid;
	public $supplierid;
	public $supprefno;
	public $porefno;
	public $terms;
	public $notes;
	public $totline;
	public $totqty;
	public $totamount;
	public $totdebit;
	public $totcredit;
	public $balance;
	public $posted;
	public $cancelled;
	
	
	
	
	
	public function delete(){
		global $database;
		
		$database->startTransaction();
		
		if($this->posted==1){
			//echo "error on deletion: already posted";	
			$database->rollback();
			return false;
		} else {
			//echo "ready for deletion";	
			//parent::$id = $this->id;
			
			if(parent::delete()){
				$respone = Apvdtl::delete_all_by_field_id(self::$table_name, $this->id);
			/*
			*  commendted out because even if $respone is false we have to commit on deleteing the apvhdr w/o details
			*/	
			//	if($respone){
				  	$database->commit();
					return true;
			//	} else {
			//		$database->rollback();
			//		return false;
			//	}
			} else {
				$database->rollback();
				return false;	
			}	
		}
	} 
	
	// comment out: check if posted is already on posting routine
	//  api line: 1119
	/*public function save(){
		if($this->posted==1){
			return false;
		} else {
			parent::save();
			return true;
		}
	}*/


	
}

