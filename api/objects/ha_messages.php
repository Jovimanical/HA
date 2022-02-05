<?php
class Ha_Messages{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_messages";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $user_to;
public $user_from;
public $subject;
public $message;
public $respond;
public $sender_open;
public $receiver_open;
public $sender_delete;
public $receiver_delete;
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

	function total_record_count() {
	$query = "select count(1) as total from ". $this->table_name ."";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			return $row['total'];
		}else{
			return 0;
		}
	}
	
	function search_record_count($columnArray,$orAnd){
		
		$where="";
		
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			$columnLogic=$col->columnLogic;
			if($where==""){
				$where="LOWER(t.".$columnName . ") ".$columnLogic." :".$columnName;
			}else{
				$where=$where." ". $orAnd ." LOWER(t." . $columnName . ") ".$columnLogic." :".$columnName;
			}
		}
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  WHERE ".$where."";
		
		$stmt = $this->conn->prepare($query);
		$paramCount=1;
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
		if(strtoupper($col->columnLogic)=="LIKE"){
		$columnValue="%".strtolower($col->columnValue)."%";
		}else{
		$columnValue=strtolower($col->columnValue);
		}
			
			$stmt->bindValue(":".$columnName, $columnValue);
			$paramCount++;
			
		}
		
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			return $row['total'];
		}else{
			return 0;
		}
	}
	// read ha_messages
	function read(){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		// select all query
		$query = "SELECT  t.* FROM ". $this->table_name ." t  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	//Search table
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 

		// select all query
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.user_to LIKE ?  OR t.user_from LIKE ?  OR t.subject LIKE ?  OR t.message LIKE ?  OR t.respond LIKE ?  OR t.sender_open LIKE ?  OR t.receiver_open LIKE ?  OR t.sender_delete LIKE ?  OR t.receiver_delete LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind searchKey
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
$stmt->bindParam(8, $searchKey);
$stmt->bindParam(9, $searchKey);
$stmt->bindParam(10, $searchKey);
	 
		// execute query
		$stmt->execute();
	 
		return $stmt;
	}
	function searchByColumn($columnArray,$orAnd){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$where="";
		
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			$columnLogic=$col->columnLogic;
			if($where==""){
				$where="LOWER(t.".$columnName . ") ".$columnLogic." :".$columnName;
			}else{
				$where=$where." ". $orAnd ." LOWER(t." . $columnName . ") ".$columnLogic." :".$columnName;
			}
		}
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
		$stmt = $this->conn->prepare($query);
		$paramCount=1;
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
		if(strtoupper($col->columnLogic)=="LIKE"){
		$columnValue="%".strtolower($col->columnValue)."%";
		}else{
		$columnValue=strtolower($col->columnValue);
		}
			
			$stmt->bindValue(":".$columnName, $columnValue);
			$paramCount++;
			
		}
		
		$stmt->execute();
		return $stmt;
	}
	
	

	function readOne(){
	 
		// query to read single record
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// bind id
		$stmt->bindParam(1, $this->id);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		 $num = $stmt->rowCount();
		if($num>0){
		// set values to object properties
		
$this->id = $row['id'];
$this->user_to = $row['user_to'];
$this->user_from = $row['user_from'];
$this->subject = $row['subject'];
$this->message = $row['message'];
$this->respond = $row['respond'];
$this->sender_open = $row['sender_open'];
$this->receiver_open = $row['receiver_open'];
$this->sender_delete = $row['sender_delete'];
$this->receiver_delete = $row['receiver_delete'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_messages
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET user_to=:user_to,user_from=:user_from,subject=:subject,message=:message,respond=:respond,sender_open=:sender_open,receiver_open=:receiver_open,sender_delete=:sender_delete,receiver_delete=:receiver_delete";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->user_to=htmlspecialchars(strip_tags($this->user_to));
$this->user_from=htmlspecialchars(strip_tags($this->user_from));
$this->subject=htmlspecialchars(strip_tags($this->subject));
$this->message=htmlspecialchars(strip_tags($this->message));
$this->respond=htmlspecialchars(strip_tags($this->respond));
$this->sender_open=htmlspecialchars(strip_tags($this->sender_open));
$this->receiver_open=htmlspecialchars(strip_tags($this->receiver_open));
$this->sender_delete=htmlspecialchars(strip_tags($this->sender_delete));
$this->receiver_delete=htmlspecialchars(strip_tags($this->receiver_delete));
	 
		// bind values
		
$stmt->bindParam(":user_to", $this->user_to);
$stmt->bindParam(":user_from", $this->user_from);
$stmt->bindParam(":subject", $this->subject);
$stmt->bindParam(":message", $this->message);
$stmt->bindParam(":respond", $this->respond);
$stmt->bindParam(":sender_open", $this->sender_open);
$stmt->bindParam(":receiver_open", $this->receiver_open);
$stmt->bindParam(":sender_delete", $this->sender_delete);
$stmt->bindParam(":receiver_delete", $this->receiver_delete);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_messages
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET user_to=:user_to,user_from=:user_from,subject=:subject,message=:message,respond=:respond,sender_open=:sender_open,receiver_open=:receiver_open,sender_delete=:sender_delete,receiver_delete=:receiver_delete WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->user_to=htmlspecialchars(strip_tags($this->user_to));
$this->user_from=htmlspecialchars(strip_tags($this->user_from));
$this->subject=htmlspecialchars(strip_tags($this->subject));
$this->message=htmlspecialchars(strip_tags($this->message));
$this->respond=htmlspecialchars(strip_tags($this->respond));
$this->sender_open=htmlspecialchars(strip_tags($this->sender_open));
$this->receiver_open=htmlspecialchars(strip_tags($this->receiver_open));
$this->sender_delete=htmlspecialchars(strip_tags($this->sender_delete));
$this->receiver_delete=htmlspecialchars(strip_tags($this->receiver_delete));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":user_to", $this->user_to);
$stmt->bindParam(":user_from", $this->user_from);
$stmt->bindParam(":subject", $this->subject);
$stmt->bindParam(":message", $this->message);
$stmt->bindParam(":respond", $this->respond);
$stmt->bindParam(":sender_open", $this->sender_open);
$stmt->bindParam(":receiver_open", $this->receiver_open);
$stmt->bindParam(":sender_delete", $this->sender_delete);
$stmt->bindParam(":receiver_delete", $this->receiver_delete);
$stmt->bindParam(":id", $this->id);
	 
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	function update_patch($jsonObj) {
			$query ="UPDATE ".$this->table_name;
			$setValue="";
			$colCount=1;
			foreach($jsonObj as $key => $value) 
			{
				$columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE id = :id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":id", $this->id);
			$stmt->execute();

			 if($stmt->rowCount()) {
					return true;
				} else {
				   return false;
				}
	}
	// delete the ha_messages
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->id);
	 $stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
	//extra function will be generated for one to many relations
}
?>
