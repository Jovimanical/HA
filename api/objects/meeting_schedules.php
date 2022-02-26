<?php
class Meeting_Schedules{
 
    // database connection and table name
    private $conn;
    private $table_name = "meeting_schedules";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $meeting_topic;
public $meeting_fullname;
public $meeting_email;
public $meeting_phone;
public $meeting_date;
public $meeting_time;
public $meeting_duration_hours;
public $meeting_duration_minutes;
public $meeting_status;
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
	// read meeting_schedules
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.meeting_topic LIKE ?  OR t.meeting_fullname LIKE ?  OR t.meeting_email LIKE ?  OR t.meeting_phone LIKE ?  OR t.meeting_date LIKE ?  OR t.meeting_time LIKE ?  OR t.meeting_duration_hours LIKE ?  OR t.meeting_duration_minutes LIKE ?  OR t.meeting_status LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->meeting_topic = $row['meeting_topic'];
$this->meeting_fullname = $row['meeting_fullname'];
$this->meeting_email = $row['meeting_email'];
$this->meeting_phone = $row['meeting_phone'];
$this->meeting_date = $row['meeting_date'];
$this->meeting_time = $row['meeting_time'];
$this->meeting_duration_hours = $row['meeting_duration_hours'];
$this->meeting_duration_minutes = $row['meeting_duration_minutes'];
$this->meeting_status = $row['meeting_status'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create meeting_schedules
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET meeting_topic=:meeting_topic,meeting_fullname=:meeting_fullname,meeting_email=:meeting_email,meeting_phone=:meeting_phone,meeting_date=:meeting_date,meeting_time=:meeting_time,meeting_duration_hours=:meeting_duration_hours,meeting_duration_minutes=:meeting_duration_minutes,meeting_status=:meeting_status,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->meeting_topic=htmlspecialchars(strip_tags($this->meeting_topic));
$this->meeting_fullname=htmlspecialchars(strip_tags($this->meeting_fullname));
$this->meeting_email=htmlspecialchars(strip_tags($this->meeting_email));
$this->meeting_phone=htmlspecialchars(strip_tags($this->meeting_phone));
$this->meeting_date=htmlspecialchars(strip_tags($this->meeting_date));
$this->meeting_time=htmlspecialchars(strip_tags($this->meeting_time));
$this->meeting_duration_hours=htmlspecialchars(strip_tags($this->meeting_duration_hours));
$this->meeting_duration_minutes=htmlspecialchars(strip_tags($this->meeting_duration_minutes));
$this->meeting_status=htmlspecialchars(strip_tags($this->meeting_status));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":meeting_topic", $this->meeting_topic);
$stmt->bindParam(":meeting_fullname", $this->meeting_fullname);
$stmt->bindParam(":meeting_email", $this->meeting_email);
$stmt->bindParam(":meeting_phone", $this->meeting_phone);
$stmt->bindParam(":meeting_date", $this->meeting_date);
$stmt->bindParam(":meeting_time", $this->meeting_time);
$stmt->bindParam(":meeting_duration_hours", $this->meeting_duration_hours);
$stmt->bindParam(":meeting_duration_minutes", $this->meeting_duration_minutes);
$stmt->bindParam(":meeting_status", $this->meeting_status);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the meeting_schedules
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET meeting_topic=:meeting_topic,meeting_fullname=:meeting_fullname,meeting_email=:meeting_email,meeting_phone=:meeting_phone,meeting_date=:meeting_date,meeting_time=:meeting_time,meeting_duration_hours=:meeting_duration_hours,meeting_duration_minutes=:meeting_duration_minutes,meeting_status=:meeting_status,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->meeting_topic=htmlspecialchars(strip_tags($this->meeting_topic));
$this->meeting_fullname=htmlspecialchars(strip_tags($this->meeting_fullname));
$this->meeting_email=htmlspecialchars(strip_tags($this->meeting_email));
$this->meeting_phone=htmlspecialchars(strip_tags($this->meeting_phone));
$this->meeting_date=htmlspecialchars(strip_tags($this->meeting_date));
$this->meeting_time=htmlspecialchars(strip_tags($this->meeting_time));
$this->meeting_duration_hours=htmlspecialchars(strip_tags($this->meeting_duration_hours));
$this->meeting_duration_minutes=htmlspecialchars(strip_tags($this->meeting_duration_minutes));
$this->meeting_status=htmlspecialchars(strip_tags($this->meeting_status));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":meeting_topic", $this->meeting_topic);
$stmt->bindParam(":meeting_fullname", $this->meeting_fullname);
$stmt->bindParam(":meeting_email", $this->meeting_email);
$stmt->bindParam(":meeting_phone", $this->meeting_phone);
$stmt->bindParam(":meeting_date", $this->meeting_date);
$stmt->bindParam(":meeting_time", $this->meeting_time);
$stmt->bindParam(":meeting_duration_hours", $this->meeting_duration_hours);
$stmt->bindParam(":meeting_duration_minutes", $this->meeting_duration_minutes);
$stmt->bindParam(":meeting_status", $this->meeting_status);
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
	// delete the meeting_schedules
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
