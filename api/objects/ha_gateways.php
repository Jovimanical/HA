<?php
class Ha_Gateways{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_gateways";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $code;
public $name;
public $alias;
public $image;
public $status;
public $gateway_parameters;
public $supported_currencies;
public $crypto;
public $extra;
public $description;
public $input_form;
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
	// read ha_gateways
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.code LIKE ?  OR t.name LIKE ?  OR t.alias LIKE ?  OR t.image LIKE ?  OR t.status LIKE ?  OR t.gateway_parameters LIKE ?  OR t.supported_currencies LIKE ?  OR t.crypto LIKE ?  OR t.extra LIKE ?  OR t.description LIKE ?  OR t.input_form LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->code = $row['code'];
$this->name = $row['name'];
$this->alias = $row['alias'];
$this->image = $row['image'];
$this->status = $row['status'];
$this->gateway_parameters = $row['gateway_parameters'];
$this->supported_currencies = $row['supported_currencies'];
$this->crypto = $row['crypto'];
$this->extra = $row['extra'];
$this->description = $row['description'];
$this->input_form = $row['input_form'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_gateways
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET code=:code,name=:name,alias=:alias,image=:image,status=:status,gateway_parameters=:gateway_parameters,supported_currencies=:supported_currencies,crypto=:crypto,extra=:extra,description=:description,input_form=:input_form,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->code=htmlspecialchars(strip_tags($this->code));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->alias=htmlspecialchars(strip_tags($this->alias));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->gateway_parameters=htmlspecialchars(strip_tags($this->gateway_parameters));
$this->supported_currencies=htmlspecialchars(strip_tags($this->supported_currencies));
$this->crypto=htmlspecialchars(strip_tags($this->crypto));
$this->extra=htmlspecialchars(strip_tags($this->extra));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->input_form=htmlspecialchars(strip_tags($this->input_form));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":code", $this->code);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":alias", $this->alias);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":gateway_parameters", $this->gateway_parameters);
$stmt->bindParam(":supported_currencies", $this->supported_currencies);
$stmt->bindParam(":crypto", $this->crypto);
$stmt->bindParam(":extra", $this->extra);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":input_form", $this->input_form);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_gateways
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET code=:code,name=:name,alias=:alias,image=:image,status=:status,gateway_parameters=:gateway_parameters,supported_currencies=:supported_currencies,crypto=:crypto,extra=:extra,description=:description,input_form=:input_form,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->code=htmlspecialchars(strip_tags($this->code));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->alias=htmlspecialchars(strip_tags($this->alias));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->gateway_parameters=htmlspecialchars(strip_tags($this->gateway_parameters));
$this->supported_currencies=htmlspecialchars(strip_tags($this->supported_currencies));
$this->crypto=htmlspecialchars(strip_tags($this->crypto));
$this->extra=htmlspecialchars(strip_tags($this->extra));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->input_form=htmlspecialchars(strip_tags($this->input_form));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":code", $this->code);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":alias", $this->alias);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":gateway_parameters", $this->gateway_parameters);
$stmt->bindParam(":supported_currencies", $this->supported_currencies);
$stmt->bindParam(":crypto", $this->crypto);
$stmt->bindParam(":extra", $this->extra);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":input_form", $this->input_form);
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
	// delete the ha_gateways
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
