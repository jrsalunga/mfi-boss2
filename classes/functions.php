<?php

class DateRange {
	
	public $fr;
	public $to;
	
	function __construct($fr=NULL, $to=NULL, $save=true, $max_diff=365){
	
		
		if(is_null($fr) && !is_null($to)){
			$this->to = date('Y-m-d', strtotime($to));
			$this->fr = date('Y-m-01', strtotime($to));	
		} else if (!is_null($fr) && is_null($to)){
			$this->to = date('Y-m-t', strtotime($fr));
			$this->fr = date('Y-m-d', strtotime($fr));
		} else if(is_null($fr) && is_null($to)){
			$this->get_current();
		} else {

			 
			if(strtotime($to) >= strtotime($fr)){

				try {
				    $f = new DateTime($fr);
				} catch (Exception $e) {
				   	$f = new DateTime(date('Y-m-01', strtotime('now')));
				}

				try{
					$t = new DateTime($to);
				} catch (Exception $e) {
					$t = new DateTime(date('Y-m-t', strtotime($f->format('Y-m-d'))));
				}
			
	            $diff = $f->diff($t);
	            if($diff->format('%a') > $max_diff){
	            	//echo 'too long range</br>';
	            	$this->fr = date('Y-m-01', strtotime($to));	
	            	$this->to = date('Y-m-t', strtotime($to));
					

	            } else {
	            	//echo 'on range</br>';
	            	$this->fr = $f->format('Y-m-d');
	            	$this->to = $t->format('Y-m-d');
	            }
	           
	        } else {
	            //echo 'invalid range</br>';
	           	$this->fr = date('Y-m-01', strtotime('now'));
	            $this->to = date('Y-m-t', strtotime($this->fr));
	        }  
			/*
			if(!$this->is_valid_date($to)){
				
			} else {
				$this->to = date('Y-m-d', strtotime($to));
			}

			if(is_string($fr)){
				if(strlen(strtotime($fr)) == 0){

				} else {
					if(preg_match($date_regex,$fr) && strlen($fr)==10){
						$this->fr = date('Y-m-d', strtotime($fr));
					} else {
						$this->fr = date('Y-m-d', strtotime($fr));
					}
					
				}

			} else if(preg_match($date_regex,$fr) && strlen($fr)==10){
				$this->fr = date('Y-m-d', strtotime($fr));
			} else {
				echo 'fr is nothing<br>';
			}
			*/
		}
		
		
		if($save && !headers_sent()){
			setcookie("to", $this->to, time() + (86400)); // 86400 = 1 day
			setcookie("fr", $this->fr, time() + (86400)); // 86400 = 1 day
		}
		
	}
	
	public function is_valid_date($x){
		$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';

		if(is_string($x)){
			if(strlen(strtotime($x)) == 0){

			} else {
				return $x;
			}

		} else if(preg_match($date_regex,$x) && strlen($x)==10){
			return $x;
		} else {
			return false;
		}
	}
							
							
	public function get_current(){
		$query_date = 'now';
		
		if(!empty($_COOKIE['to'])){
			$this->to = $_COOKIE['to'];
		} else {
			 // Last day of the month.
       		$this->to = date('Y-m-t', strtotime($query_date));
		}
		
		
		if(!empty($_COOKIE['fr'])){
			$this->fr = $_COOKIE['fr'];
		} else {
			  // First day of the month.
        	$this->fr = date('Y-m-01', strtotime($query_date));
		}		
	}

	public static function current(){
		$query_date = 'now';

		$me = new stdClass; 
		
		if(!empty($_COOKIE['to'])){
			$me->to = $_COOKIE['to'];
		} else {
			 // Last day of the month.
       		$me->to = date('Y-m-t', strtotime($query_date));
		}
		
		
		if(!empty($_COOKIE['fr'])){
			$me->fr = $_COOKIE['fr'];
		} else {
			  // First day of the month.
        	$me->fr = date('Y-m-01', strtotime($query_date));
		}

		return $me;		
	}
	
	
	public function getDaysInterval(){
		$begin = new DateTime($this->fr);
		$end = new DateTime($this->to);
		$end = $end->modify('+1 day'); 
		
		$interval = new DateInterval('P1D');
		return $daterange = new DatePeriod($begin, $interval ,$end);
	}


	public function fr_prev_day(){
		$fr = new DateTime($this->fr);
		$fr->modify('-1 day'); 
		return $fr->format("Y-m-d");
	}

	public function fr_next_day(){
		$fr = new DateTime($this->fr);
		$fr->modify('+1 day'); 
		return $fr->format("Y-m-d");
	}

	public function to_prev_day(){
		$to = new DateTime($this->to);
		$to->modify('-1 day'); 
		return $to->format("Y-m-d");
	}

	public function to_next_day(){
		$to = new DateTime($this->to);
		$to->modify('+1 day'); 
		return $to->format("Y-m-d");
	}
								
	
	/*							
	$fr = $r->get('fr');
    $to = $r->get('to');


    if(!empty($to) && !empty($fr)){
        
        if(strtotime($to) >= strtotime($fr)){
            //return 'correct range';
        } else {
            return 'invalid range';
        }   
    } else {
        $query_date = 'now';
        // First day of the month.
        $fr = date('Y-m-01', strtotime($query_date));
        // Last day of the month.
        $to = date('Y-m-t', strtotime('now'));
        
        // Minus 15 days from now
        //$fr = date('Y-m-d', strtotime('-14 day'));
        //$to = date('Y-m-d', strtotime('now'));
    }
								
	}
	*/
							
}

function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
}


function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}


function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

function output_message($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

//function __autoload($class_name) {
//	$class_name = strtolower($class_name);
//  $path = CLASS_LIB.DS."{$class_name}.php";
//  if(file_exists($path)) {
//    require_once($path);
//  } else {
//		die("The file {$class_name}.php could not be found.");
//	}
//}

function include_template($template="") {
	include_once(TEMPLATE_PATH.DS.$template);
}

function log_action($action, $message="") {
	$logfile = ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
  if($handle = fopen($logfile, 'a')) { // append
    $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
	#$timestamp = strftime("%Y-%m-%d %H:%M:%S", time()+(28800));
	$ip = $_SERVER['REMOTE_ADDR'];
	$brw = $_SERVER['HTTP_USER_AGENT'];
		$content = "{$timestamp} | {$ip} | {$action}: {$message} \t\t\t {$brw}\n";
    fwrite($handle, $content);
    fclose($handle);
    if($new) { chmod($logfile, 0755); }
  } else {
    echo "Could not open log file for writing.";
  }
}

function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}

function iso_date($date="now") {
	$unixdatetime = strtotime($date);
	return strftime("%Y-%m-%d", $unixdatetime);	
}

function long_date($date="now") {
	$unixdatetime = strtotime($date);
	return strftime("%A, %B %d, %Y", $unixdatetime);	
}

function short_date($date="now") {
	$unixdatetime = strtotime($date);
	return strftime("%m/%d/%Y", $unixdatetime);	
}






function uc_first_word($string) {
    $s = explode(' ', $string);
    $s[0] = ucwords(strtolower($s[0]));
    $s = implode(' ', $s);
    return $s;
}


function uc_first($string) {
    $s = ucwords(strtolower($string));
    return $s;
}



function is_uuid($uuid=0) {
	return preg_match('/^[A-Fa-f0-9]{32}+$/',$uuid) ? $uuid : false;
}

function is_uid($uuid=0) {
	return preg_match('/^[A-Fa-f0-9]{32}+$/',$uuid) ? true : false;
}

function id_isset($val) {
	return (isset($val) && !empty($val) && is_uuid($val)) ? $val : false;	
}


function uuid_isset($val) {
	return (isset($val) && !empty($val) && is_uuid($val)) ? $val : false;	
}




// 														if $datas is obj
function groupSummary($datas, $uf='id', $ttt=array(), $obj=TRUE, $push = false){
	$arr = array();

	foreach ($ttt as $key => $value) {
		 $arr['gt_' . $value] = 0;
	}

	$arr['rs'] = array();

	$chkctr=0;
	if($obj) {
		foreach($datas as $data){
			if(array_key_exists($data->{$uf}, $arr['rs'])) {

				//$arr[$data->{$uf}]['totamt'] +=  $data->amount;
				foreach ($data as $key => $value) {
					if(in_array($key, $ttt)){
						//$arr['rs'][$data->{$uf}][$key] += $value;
						$arr['gt_' . $key] += $value;
					}
						
				}
			} else {
				foreach ($data as $key => $value) {
					//$arr['rs'][$data->{$uf}][$key] = $value;
					if(in_array($key, $ttt)){
						$arr['gt_' . $key] += $value;
					}
				}

			}
			
			if($push)
				array_push($arr['rs'], $data);
		}
	} else {
		foreach($datas as $key => $data){
			if(array_key_exists($data[$uf], $arr)) {				
				$arr[$data[$uf]]['totamt'] +=  $data['amount'];
			} else {
				$arr[$data[$uf]]['totamt'] =  $data['amount'];
				$arr[$data[$uf]]['checkdate'] = $data['checkdate'];
			}
		}
	}
	
	return $arr;	
}




function groupSummaryCategory($datas, $uf='id', $cat='catcode', $ttt=array(), $obj=TRUE){
	$arr = array();
	$chkctr=0;
	if($obj) {
		foreach($datas as $data){
			if(array_key_exists($data->{$uf}, $arr)) {

				foreach ($data as $key => $value) {
					if(in_array($key, $ttt))
						$arr[$data->{$uf}][$key] += $value;
				}
			} else {
				foreach ($data as $key => $value) {
					$arr[$data->{$uf}][$key] = $value;
				}

			}
		}
	} else {
		foreach($datas as $key => $data){
			if(array_key_exists($data[$uf], $arr)) {				
				$arr[$data[$uf]]['totamt'] +=  $data['amount'];
			} else {
				$arr[$data[$uf]]['totamt'] =  $data['amount'];
				$arr[$data[$uf]]['checkdate'] = $data['checkdate'];
			}
		}
	}
	
	return $arr;	
}



?>