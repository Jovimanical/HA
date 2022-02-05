<?php
class Ha_Withdraw_Methods{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_withdraw_methods";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $name;
public $image;
public $min_limit;
public $max_limit;
public $delay;
public $fixed_charge;
public $rate;
public $percent_charge;
public $currency;
public $user_data;
public $description;
public $status;
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
	// read ha_withdraw_methods
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.name LIKE ?  OR t.image LIKE ?  OR t.min_limit LIKE ?  OR t.max_limit LIKE ?  OR t.delay LIKE ?  OR t.fixed_charge LIKE ?  OR t.rate LIKE ?  OR t.percent_charge LIKE ?  OR t.currency LIKE ?  OR t.user_data LIKE ?  OR t.description LIKE ?  OR t.status LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->name = $row['name'];
$this->image = $row['image'];
$this->min_limit = $row['min_limit'];
$this->max_limit = $row['max_limit'];
$this->delay = $row['delay'];
$this->fixed_charge = $row['fixed_charge'];
$this->rate = $row['rate'];
$this->percent_charge = $row['percent_charge'];
$this->currency = $row['currency'];
$this->user_data = $row['user_data'];
$this->description = $row['description'];
$this->status = $row['status'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_withdraw_methods
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET name=:name,image=:image,min_limit=:min_limit,max_limit=:max_limit,delay=:delay,fixed_charge=:fixed_charge,rate=:rate,percent_charge=:percent_charge,currency=:currency,user_data=:user_data,description=:description,status=:status,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->name=htmlspecialchars(strip_tags($this->name));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->min_limit=htmlspecialchars(strip_tags($this->min_limit));
$this->max_limit=htmlspecialchars(strip_tags($this->max_limit));
$this->delay=htmlspecialchars(strip_tags($this->delay));
$this->fixed_charge=htmlspecialchars(strip_tags($this->fixed_charge));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->percent_charge=htmlspecialchars(strip_tags($this->percent_charge));
$this->currency=htmlspecialchars(strip_tags($this->currency));
$this->user_data=htmlspecialchars(strip_tags($this->user_data));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":min_limit", $this->min_limit);
$stmt->bindParam(":max_limit", $this->max_limit);
$stmt->bindParam(":delay", $this->delay);
$stmt->bindParam(":fixed_charge", $this->fixed_charge);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":percent_charge", $this->percent_charge);
$stmt->bindParam(":currency", $this->currency);
$stmt->bindParam(":user_data", $this->user_data);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_withdraw_methods
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET name=:name,image=:image,min_limit=:min_limit,max_limit=:max_limit,delay=:delay,fixed_charge=:fixed_charge,rate=:rate,percent_charge=:percent_charge,currency=:currency,user_data=:user_data,description=:description,status=:status,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->name=htmlspecialchars(strip_tags($this->name));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->min_limit=htmlspecialchars(strip_tags($this->min_limit));
$this->max_limit=htmlspecialchars(strip_tags($this->max_limit));
$this->delay=htmlspecialchars(strip_tags($this->delay));
$this->fixed_charge=htmlspecialchars(strip_tags($this->fixed_charge));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->percent_charge=htmlspecialchars(strip_tags($this->percent_charge));
$this->currency=htmlspecialchars(strip_tags($this->currency));
$this->user_data=htmlspecialchars(strip_tags($this->user_data));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":min_limit", $this->min_limit);
$stmt->bindParam(":max_limit", $this->max_limit);
$stmt->bindParam(":delay", $this->delay);
$stmt->bindParam(":fixed_charge", $this->fixed_charge);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":percent_charge", $this->percent_charge);
$stmt->bindParam(":currency", $this->currency);
$stmt->bindParam(":user_data", $this->user_data);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":status", $this->status);
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
	// delete the ha_withdraw_methods
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
