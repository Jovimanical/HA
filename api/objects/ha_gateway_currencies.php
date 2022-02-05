<?php
class Ha_Gateway_Currencies{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_gateway_currencies";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $name;
public $currency;
public $symbol;
public $method_code;
public $gateway_alias;
public $min_amount;
public $max_amount;
public $percent_charge;
public $fixed_charge;
public $rate;
public $image;
public $gateway_parameter;
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
	// read ha_gateway_currencies
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.name LIKE ?  OR t.currency LIKE ?  OR t.symbol LIKE ?  OR t.method_code LIKE ?  OR t.gateway_alias LIKE ?  OR t.min_amount LIKE ?  OR t.max_amount LIKE ?  OR t.percent_charge LIKE ?  OR t.fixed_charge LIKE ?  OR t.rate LIKE ?  OR t.image LIKE ?  OR t.gateway_parameter LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->currency = $row['currency'];
$this->symbol = $row['symbol'];
$this->method_code = $row['method_code'];
$this->gateway_alias = $row['gateway_alias'];
$this->min_amount = $row['min_amount'];
$this->max_amount = $row['max_amount'];
$this->percent_charge = $row['percent_charge'];
$this->fixed_charge = $row['fixed_charge'];
$this->rate = $row['rate'];
$this->image = $row['image'];
$this->gateway_parameter = $row['gateway_parameter'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_gateway_currencies
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET name=:name,currency=:currency,symbol=:symbol,method_code=:method_code,gateway_alias=:gateway_alias,min_amount=:min_amount,max_amount=:max_amount,percent_charge=:percent_charge,fixed_charge=:fixed_charge,rate=:rate,image=:image,gateway_parameter=:gateway_parameter,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->name=htmlspecialchars(strip_tags($this->name));
$this->currency=htmlspecialchars(strip_tags($this->currency));
$this->symbol=htmlspecialchars(strip_tags($this->symbol));
$this->method_code=htmlspecialchars(strip_tags($this->method_code));
$this->gateway_alias=htmlspecialchars(strip_tags($this->gateway_alias));
$this->min_amount=htmlspecialchars(strip_tags($this->min_amount));
$this->max_amount=htmlspecialchars(strip_tags($this->max_amount));
$this->percent_charge=htmlspecialchars(strip_tags($this->percent_charge));
$this->fixed_charge=htmlspecialchars(strip_tags($this->fixed_charge));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->gateway_parameter=htmlspecialchars(strip_tags($this->gateway_parameter));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":currency", $this->currency);
$stmt->bindParam(":symbol", $this->symbol);
$stmt->bindParam(":method_code", $this->method_code);
$stmt->bindParam(":gateway_alias", $this->gateway_alias);
$stmt->bindParam(":min_amount", $this->min_amount);
$stmt->bindParam(":max_amount", $this->max_amount);
$stmt->bindParam(":percent_charge", $this->percent_charge);
$stmt->bindParam(":fixed_charge", $this->fixed_charge);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":gateway_parameter", $this->gateway_parameter);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_gateway_currencies
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET name=:name,currency=:currency,symbol=:symbol,method_code=:method_code,gateway_alias=:gateway_alias,min_amount=:min_amount,max_amount=:max_amount,percent_charge=:percent_charge,fixed_charge=:fixed_charge,rate=:rate,image=:image,gateway_parameter=:gateway_parameter,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->name=htmlspecialchars(strip_tags($this->name));
$this->currency=htmlspecialchars(strip_tags($this->currency));
$this->symbol=htmlspecialchars(strip_tags($this->symbol));
$this->method_code=htmlspecialchars(strip_tags($this->method_code));
$this->gateway_alias=htmlspecialchars(strip_tags($this->gateway_alias));
$this->min_amount=htmlspecialchars(strip_tags($this->min_amount));
$this->max_amount=htmlspecialchars(strip_tags($this->max_amount));
$this->percent_charge=htmlspecialchars(strip_tags($this->percent_charge));
$this->fixed_charge=htmlspecialchars(strip_tags($this->fixed_charge));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->gateway_parameter=htmlspecialchars(strip_tags($this->gateway_parameter));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":currency", $this->currency);
$stmt->bindParam(":symbol", $this->symbol);
$stmt->bindParam(":method_code", $this->method_code);
$stmt->bindParam(":gateway_alias", $this->gateway_alias);
$stmt->bindParam(":min_amount", $this->min_amount);
$stmt->bindParam(":max_amount", $this->max_amount);
$stmt->bindParam(":percent_charge", $this->percent_charge);
$stmt->bindParam(":fixed_charge", $this->fixed_charge);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":gateway_parameter", $this->gateway_parameter);
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
	// delete the ha_gateway_currencies
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
