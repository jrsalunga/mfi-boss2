<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(ROOT.DS.'classes'.DS.'database.php');

class vItem extends DatabaseObject{
	
	protected static $table_name="item";
	protected static $db_fields = array('id', 'code' ,'descriptor' ,'type' ,'itemcatid' ,'uom' ,'longdesc' ,
									'onhand' ,'minlevel' ,'maxlevel' ,'reorderqty' , 'avecost', 'value', 
									'catcode' ,'catname', 'item' ,'totqty', 'porefno', 'operatorcode',
									'operator', 'projectcode', 'project');
	
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
	public $item;
	public $totqty;
	public $porefno;
	public $projectcode;
	public $project;
	public $operatorcode;
	public $operator;




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



	public static function findAllStockReceiptsByDateRange($fr=NULL, $to=NULL, $posted='1'){
		$sql = 'SELECT d.descriptor AS catname , c.code, c.descriptor , SUM(b.qty) AS totqty, c.uom, a.porefno ';
		$sql .= 'FROM rcphdr a ';
		$sql .= 'LEFT JOIN rcpdtl b ON a.id = b.rcphdrid ';
		$sql .= 'LEFT JOIN item c ON b.itemid = c.id ';
		$sql .= 'LEFT JOIN itemcat d ON c.itemcatid = d.id ';
		$sql .= "WHERE a.date BETWEEN '".$fr."' AND '".$to."' AND a.posted = '".$posted."' ";
		$sql .= 'GROUP BY 1, 3, 2, 5 ';
		return parent::find_by_sql($sql);
	}

	public static function DirectMaterialIssuancesByDateRange($fr=NULL, $to=NULL, $posted='1'){
		$sql = 'select d.descriptor as catname, c.code , c.descriptor, ';
		$sql .= 'sum( b.qty ) as totqty, c.uom, ';
		$sql .= 'e.code as projectcode, e.descriptor as project, f.descriptor as operator, f.code as operatorcode ';
		$sql .= 'from isdhdr a ';
		$sql .= 'left join isddtl b on a.id=b.isdhdrid ';
		$sql .= 'left join item c on b.itemid=c.id ';
		$sql .= 'left join itemcat d on c.itemcatid=d.id ';
		$sql .= 'left join project e on e.id = a.projectid ';
		$sql .= 'left join operator f on f.id = a.operatorid ';
		$sql .= "WHERE a.date BETWEEN '".$fr."' AND '".$to."' AND a.posted = '".$posted."' ";
		$sql .= 'GROUP BY 3 ';
		return parent::find_by_sql($sql);
	}
	
}

