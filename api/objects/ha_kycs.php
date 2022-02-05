<?php
class Ha_Kycs{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_kycs";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $first_name;
public $last_name;
public $city;
public $country;
public $city_of_birth;
public $country_of_birth;
public $nationality;
public $document_type;
public $document_number;
public $issuing_authority;
public $issue_on;
public $valid_until;
public $order_amount;
public $internal;
public $external;
public $follow_up;
public $comment;
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
	// read ha_kycs
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.first_name LIKE ?  OR t.last_name LIKE ?  OR t.city LIKE ?  OR t.country LIKE ?  OR t.city_of_birth LIKE ?  OR t.country_of_birth LIKE ?  OR t.nationality LIKE ?  OR t.document_type LIKE ?  OR t.document_number LIKE ?  OR t.issuing_authority LIKE ?  OR t.issue_on LIKE ?  OR t.valid_until LIKE ?  OR t.order_amount LIKE ?  OR t.internal LIKE ?  OR t.external LIKE ?  OR t.follow_up LIKE ?  OR t.comment LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$stmt->bindParam(13, $searchKey);
$stmt->bindParam(14, $searchKey);
$stmt->bindParam(15, $searchKey);
$stmt->bindParam(16, $searchKey);
$stmt->bindParam(17, $searchKey);
$stmt->bindParam(18, $searchKey);
$stmt->bindParam(19, $searchKey);
$stmt->bindParam(20, $searchKey);
	 
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.ERROR_NOPRIMARYKEYFOUND = ? LIMIT 0,1";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// bind id
		$stmt->bindParam(1, $this->ERROR_NOPRIMARYKEYFOUND);
	 
		// execute query
		$stmt->execute();
	 
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		 $num = $stmt->rowCount();
		if($num>0){
		// set values to object properties
		
$this->id = $row['id'];
$this->first_name = $row['first_name'];
$this->last_name = $row['last_name'];
$this->city = $row['city'];
$this->country = $row['country'];
$this->city_of_birth = $row['city_of_birth'];
$this->country_of_birth = $row['country_of_birth'];
$this->nationality = $row['nationality'];
$this->document_type = $row['document_type'];
$this->document_number = $row['document_number'];
$this->issuing_authority = $row['issuing_authority'];
$this->issue_on = $row['issue_on'];
$this->valid_until = $row['valid_until'];
$this->order_amount = $row['order_amount'];
$this->internal = $row['internal'];
$this->external = $row['external'];
$this->follow_up = $row['follow_up'];
$this->comment = $row['comment'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->ERROR_NOPRIMARYKEYFOUND=null;
		}
	}

	
	
	// create ha_kycs
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET id=:id,first_name=:first_name,last_name=:last_name,city=:city,country=:country,city_of_birth=:city_of_birth,country_of_birth=:country_of_birth,nationality=:nationality,document_type=:document_type,document_number=:document_number,issuing_authority=:issuing_authority,issue_on=:issue_on,valid_until=:valid_until,order_amount=:order_amount,internal=:internal,external=:external,follow_up=:follow_up,comment=:comment,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id=htmlspecialchars(strip_tags($this->id));
$this->first_name=htmlspecialchars(strip_tags($this->first_name));
$this->last_name=htmlspecialchars(strip_tags($this->last_name));
$this->city=htmlspecialchars(strip_tags($this->city));
$this->country=htmlspecialchars(strip_tags($this->country));
$this->city_of_birth=htmlspecialchars(strip_tags($this->city_of_birth));
$this->country_of_birth=htmlspecialchars(strip_tags($this->country_of_birth));
$this->nationality=htmlspecialchars(strip_tags($this->nationality));
$this->document_type=htmlspecialchars(strip_tags($this->document_type));
$this->document_number=htmlspecialchars(strip_tags($this->document_number));
$this->issuing_authority=htmlspecialchars(strip_tags($this->issuing_authority));
$this->issue_on=htmlspecialchars(strip_tags($this->issue_on));
$this->valid_until=htmlspecialchars(strip_tags($this->valid_until));
$this->order_amount=htmlspecialchars(strip_tags($this->order_amount));
$this->internal=htmlspecialchars(strip_tags($this->internal));
$this->external=htmlspecialchars(strip_tags($this->external));
$this->follow_up=htmlspecialchars(strip_tags($this->follow_up));
$this->comment=htmlspecialchars(strip_tags($this->comment));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":id", $this->id);
$stmt->bindParam(":first_name", $this->first_name);
$stmt->bindParam(":last_name", $this->last_name);
$stmt->bindParam(":city", $this->city);
$stmt->bindParam(":country", $this->country);
$stmt->bindParam(":city_of_birth", $this->city_of_birth);
$stmt->bindParam(":country_of_birth", $this->country_of_birth);
$stmt->bindParam(":nationality", $this->nationality);
$stmt->bindParam(":document_type", $this->document_type);
$stmt->bindParam(":document_number", $this->document_number);
$stmt->bindParam(":issuing_authority", $this->issuing_authority);
$stmt->bindParam(":issue_on", $this->issue_on);
$stmt->bindParam(":valid_until", $this->valid_until);
$stmt->bindParam(":order_amount", $this->order_amount);
$stmt->bindParam(":internal", $this->internal);
$stmt->bindParam(":external", $this->external);
$stmt->bindParam(":follow_up", $this->follow_up);
$stmt->bindParam(":comment", $this->comment);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_kycs
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET id=:id,first_name=:first_name,last_name=:last_name,city=:city,country=:country,city_of_birth=:city_of_birth,country_of_birth=:country_of_birth,nationality=:nationality,document_type=:document_type,document_number=:document_number,issuing_authority=:issuing_authority,issue_on=:issue_on,valid_until=:valid_until,order_amount=:order_amount,internal=:internal,external=:external,follow_up=:follow_up,comment=:comment,created_at=:created_at,updated_at=:updated_at WHERE ERROR_NOPRIMARYKEYFOUND = :ERROR_NOPRIMARYKEYFOUND";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->id=htmlspecialchars(strip_tags($this->id));
$this->first_name=htmlspecialchars(strip_tags($this->first_name));
$this->last_name=htmlspecialchars(strip_tags($this->last_name));
$this->city=htmlspecialchars(strip_tags($this->city));
$this->country=htmlspecialchars(strip_tags($this->country));
$this->city_of_birth=htmlspecialchars(strip_tags($this->city_of_birth));
$this->country_of_birth=htmlspecialchars(strip_tags($this->country_of_birth));
$this->nationality=htmlspecialchars(strip_tags($this->nationality));
$this->document_type=htmlspecialchars(strip_tags($this->document_type));
$this->document_number=htmlspecialchars(strip_tags($this->document_number));
$this->issuing_authority=htmlspecialchars(strip_tags($this->issuing_authority));
$this->issue_on=htmlspecialchars(strip_tags($this->issue_on));
$this->valid_until=htmlspecialchars(strip_tags($this->valid_until));
$this->order_amount=htmlspecialchars(strip_tags($this->order_amount));
$this->internal=htmlspecialchars(strip_tags($this->internal));
$this->external=htmlspecialchars(strip_tags($this->external));
$this->follow_up=htmlspecialchars(strip_tags($this->follow_up));
$this->comment=htmlspecialchars(strip_tags($this->comment));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->ERROR_NOPRIMARYKEYFOUND=htmlspecialchars(strip_tags($this->ERROR_NOPRIMARYKEYFOUND));
	 
		// bind new values
		
$stmt->bindParam(":id", $this->id);
$stmt->bindParam(":first_name", $this->first_name);
$stmt->bindParam(":last_name", $this->last_name);
$stmt->bindParam(":city", $this->city);
$stmt->bindParam(":country", $this->country);
$stmt->bindParam(":city_of_birth", $this->city_of_birth);
$stmt->bindParam(":country_of_birth", $this->country_of_birth);
$stmt->bindParam(":nationality", $this->nationality);
$stmt->bindParam(":document_type", $this->document_type);
$stmt->bindParam(":document_number", $this->document_number);
$stmt->bindParam(":issuing_authority", $this->issuing_authority);
$stmt->bindParam(":issue_on", $this->issue_on);
$stmt->bindParam(":valid_until", $this->valid_until);
$stmt->bindParam(":order_amount", $this->order_amount);
$stmt->bindParam(":internal", $this->internal);
$stmt->bindParam(":external", $this->external);
$stmt->bindParam(":follow_up", $this->follow_up);
$stmt->bindParam(":comment", $this->comment);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
$stmt->bindParam(":ERROR_NOPRIMARYKEYFOUND", $this->ERROR_NOPRIMARYKEYFOUND);
	 
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
				if($columnName!='ERROR_NOPRIMARYKEYFOUND'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE ERROR_NOPRIMARYKEYFOUND = :ERROR_NOPRIMARYKEYFOUND"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='ERROR_NOPRIMARYKEYFOUND'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":ERROR_NOPRIMARYKEYFOUND", $this->ERROR_NOPRIMARYKEYFOUND);
			$stmt->execute();

			 if($stmt->rowCount()) {
					return true;
				} else {
				   return false;
				}
	}
	// delete the ha_kycs
	function delete(){
	 
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE ERROR_NOPRIMARYKEYFOUND = ? ";
	 
		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		$this->ERROR_NOPRIMARYKEYFOUND=htmlspecialchars(strip_tags($this->ERROR_NOPRIMARYKEYFOUND));
	 
		// bind id of record to delete
		$stmt->bindParam(1, $this->ERROR_NOPRIMARYKEYFOUND);
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
