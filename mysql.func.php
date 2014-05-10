<?php

class db_pdo {

	function db_pdo(){
	
		$this->dsn = '';
		
		$this->connetion = array( "host" => DBHOST , "name" => DBNAME, "user" => DBUSER, "pass" => DBPASS );
		
		$this->consult = array();
		
		$this->query = array();
	
	}
	
	function getDSN($dsn){			
				
		preg_match_all('*(mysql://([a-zA-Z0-9]+)+:([a-zA-Z0-9]+)+@([a-zA-Z0-9./]+)+/([a-zA-Z0-9]+)+)*',$dsn,$db);
		
		$db[host] = $db[4][0];
		$db[name] = $db[5][0];
		$db[user] = $db[2][0];
		$db[pass] = $db[3][0];
		
		return $db;
		
	}
	
	function connect_db(){
	
		
		$db = $this->connetion;
		
		$pdo = new PDO('mysql:host='.$db[host].';dbname='.$db[name], 
								  $db[user], 	
								  $db[pass]	
					   );
		
		return $pdo;
	}
	
	function add_consult( $consult, $exec = NULL ){
	
		$this->consult[] = array($consult,$exec);
	
	}
	
	function unset_consult(){
		
		unset( $this->consult );
		
		$this->consult = array();
		
	}
	
	function removenumber($array){
		
		if($array){
			
			for($i = 0; $i <= count($array); $i++ ){
				
				unset($array[$i]);
				
			}
			
		}
		
		return $array;
		
	}
	
	function query(){
		
		$pdo = self::connect_db();
		
		$response = array();
		
		while( list($l,$v) = each($this->consult) ){
		
			$query = $pdo->prepare($v[0]);
		
			$query->execute( $v[1] );			
			
			
			while($row = $query->fetch()) $response[$l][] = self::removenumber($row);
			
		}		 
		
		$query->closeCursor();	
		
		return $response;
	
	}
	
	function select( $table, $where = NULL, $order = NULL , $limit = NULL , $sel = '*' ){
		
		$select = "SELECT $sel FROM $table ";
		
		
		echo $select;
		
	}
	
	function insert( $table, $attr ){
		
		$attrib = array();
		$values = array();
		
		while( list($k,$v) = each($attr) ){
			
			$values[val][] = $k;
			$values[sub][] = ":".$k;			
			$attrib[":".$k] = $v;
			
		}
		
		$val = join(",",$values[val]);
		
		$sub = join(",",$values[sub]);		
		
		$pdo = self::connect_db();						
		
		$sql = "INSERT INTO $table ($val) VALUES ($sub)";
		
		$query = $pdo->prepare($sql);
		
		$query->execute( $attrib );
		
		$error = $query->errorInfo();
		
		if( $error[0] === '00000' ){ return true; }
		else { $this->error = $error; return false; }
	}
	
	
	function update( $table, $sql ,$condition = NULL, $conf = array( "where_sep" => 'AND' ) ){
			
		$set = array();
		
		while( list($k,$v) = each($sql)) $set[] = $k."='".$v."'";
		
		if( !is_null($condition) ):
			
		$add = ' WHERE '; $adds = array();
			
		$conds = array();
	
		while( list($k,$v) = each($condition)){		
			$conds[] = $v;
			$adds[]= "$k=?";		
		}
		
		$add = $add.join(" ".$conf[where_sep]." ",$adds);
		
		endif;	
		
		$sql = "UPDATE $table SET ".join(",",$set).$add;		
			
		$pdo = self::connect_db();	
			
		$query = $pdo->prepare($sql);
		
		$query->execute( $conds );
		
		$error = $query->errorInfo();
	
		if( $error[0] === '00000' ){ return true; }
		else { $this->error = $error; return false; }

		
	
	}
	
	function delete($table , $condition = NULL, $conf = array( "where_sep" => 'AND' )){
		
		if( !is_null($condition) ):
			
		$add = ' WHERE '; $adds = array();
			
		$conds = array();
	
		while( list($k,$v) = each($condition)){		
			$conds[] = $v;
			$adds[]= "$k=?";		
		}
		
		$add = $add.join(" ".$conf[where_sep]." ",$adds);
		
		endif;	
		
		$sql = "DELETE FROM $table".$add;		
				
		$pdo = self::connect_db();	
			
		$query = $pdo->prepare($sql);
		
		$query->execute( $conds );
		
		$error = $query->errorInfo();
		
				
		if( $error[0] === '00000' ){ return true; }
		else { $this->error = $error; return false; }
		
	}
	
	function numRows($array = NULL){
		
		if( is_null($array) ){
				
			$pdo = self::connect_db();
				
			$response = array();
			
			while( list($l,$v) = each($this->consult) ){
				
				$query = $pdo->prepare($v[0]);
				
				$query->execute( $v[1] );			
				
				$response[$l][] = $query->rowCount();
					
			}
			
			$this->response = $response;
				
				
		} else {
				
			$response = count($array);
			
		}	
	
		return $response;
	
	}

}
