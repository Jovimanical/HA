<?php
class Ha_Support_Tickets{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_support_tickets";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $user_id;
public $seller_id;
public $name;
public $email;
public $ticket;
public $subject;
public $status;
public $priority;
public $last_reply;
public $created_at;
public $updated_at;
    
 
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
	// read ha_support_tickets
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.user_id LIKE ?  OR t.seller_id LIKE ?  OR t.name LIKE ?  OR t.email LIKE ?  OR t.ticket LIKE ?  OR t.subject LIKE ?  OR t.status LIKE ?  OR t.priority LIKE ?  OR t.last_reply LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$stmt->bindParam(11, $searchKey);
$stmt->bindParam(12, $searchKey);
	 
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
$this->user_id = $row['user_id'];
$this->seller_id = $row['seller_id'];
$this->name = $row['name'];
$this->email = $row['email'];
$this->ticket = $row['ticket'];
$this->subject = $row['subject'];
$this->status = $row['status'];
$this->priority = $row['priority'];
$this->last_reply = $row['last_reply'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_support_tickets
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET user_id=:user_id,seller_id=:seller_id,name=:name,email=:email,ticket=:ticket,subject=:subject,status=:status,priority=:priority,last_reply=:last_reply,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->user_id=htmlspecialchars(strip_tags($this->user_id));
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->email=htmlspecialchars(strip_tags($this->email));
$this->ticket=htmlspecialchars(strip_tags($this->ticket));
$this->subject=htmlspecialchars(strip_tags($this->subject));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->priority=htmlspecialchars(strip_tags($this->priority));
$this->last_reply=htmlspecialchars(strip_tags($this->last_reply));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":user_id", $this->user_id);
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":email", $this->email);
$stmt->bindParam(":ticket", $this->ticket);
$stmt->bindParam(":subject", $this->subject);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":priority", $this->priority);
$stmt->bindParam(":last_reply", $this->last_reply);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_support_tickets
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET user_id=:user_id,seller_id=:seller_id,name=:name,email=:email,ticket=:ticket,subject=:subject,status=:status,priority=:priority,last_reply=:last_reply,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->user_id=htmlspecialchars(strip_tags($this->user_id));
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->email=htmlspecialchars(strip_tags($this->email));
$this->ticket=htmlspecialchars(strip_tags($this->ticket));
$this->subject=htmlspecialchars(strip_tags($this->subject));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->priority=htmlspecialchars(strip_tags($this->priority));
$this->last_reply=htmlspecialchars(strip_tags($this->last_reply));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":user_id", $this->user_id);
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":email", $this->email);
$stmt->bindParam(":ticket", $this->ticket);
$stmt->bindParam(":subject", $this->subject);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":priority", $this->priority);
$stmt->bindParam(":last_reply", $this->last_reply);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
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
	// delete the ha_support_tickets
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