<?php
class Ha_Withdrawals{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_withdrawals";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $method_id;
public $seller_id;
public $amount;
public $currency;
public $rate;
public $charge;
public $trx;
public $final_amount;
public $after_charge;
public $withdraw_information;
public $status;
public $admin_feedback;
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
	// read ha_withdrawals
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.method_id LIKE ?  OR t.seller_id LIKE ?  OR t.amount LIKE ?  OR t.currency LIKE ?  OR t.rate LIKE ?  OR t.charge LIKE ?  OR t.trx LIKE ?  OR t.final_amount LIKE ?  OR t.after_charge LIKE ?  OR t.withdraw_information LIKE ?  OR t.status LIKE ?  OR t.admin_feedback LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->method_id = $row['method_id'];
$this->seller_id = $row['seller_id'];
$this->amount = $row['amount'];
$this->currency = $row['currency'];
$this->rate = $row['rate'];
$this->charge = $row['charge'];
$this->trx = $row['trx'];
$this->final_amount = $row['final_amount'];
$this->after_charge = $row['after_charge'];
$this->withdraw_information = $row['withdraw_information'];
$this->status = $row['status'];
$this->admin_feedback = $row['admin_feedback'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_withdrawals
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET method_id=:method_id,seller_id=:seller_id,amount=:amount,currency=:currency,rate=:rate,charge=:charge,trx=:trx,final_amount=:final_amount,after_charge=:after_charge,withdraw_information=:withdraw_information,status=:status,admin_feedback=:admin_feedback,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->method_id=htmlspecialchars(strip_tags($this->method_id));
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->amount=htmlspecialchars(strip_tags($this->amount));
$this->currency=htmlspecialchars(strip_tags($this->currency));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->charge=htmlspecialchars(strip_tags($this->charge));
$this->trx=htmlspecialchars(strip_tags($this->trx));
$this->final_amount=htmlspecialchars(strip_tags($this->final_amount));
$this->after_charge=htmlspecialchars(strip_tags($this->after_charge));
$this->withdraw_information=htmlspecialchars(strip_tags($this->withdraw_information));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->admin_feedback=htmlspecialchars(strip_tags($this->admin_feedback));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":method_id", $this->method_id);
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":amount", $this->amount);
$stmt->bindParam(":currency", $this->currency);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":charge", $this->charge);
$stmt->bindParam(":trx", $this->trx);
$stmt->bindParam(":final_amount", $this->final_amount);
$stmt->bindParam(":after_charge", $this->after_charge);
$stmt->bindParam(":withdraw_information", $this->withdraw_information);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":admin_feedback", $this->admin_feedback);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_withdrawals
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET method_id=:method_id,seller_id=:seller_id,amount=:amount,currency=:currency,rate=:rate,charge=:charge,trx=:trx,final_amount=:final_amount,after_charge=:after_charge,withdraw_information=:withdraw_information,status=:status,admin_feedback=:admin_feedback,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->method_id=htmlspecialchars(strip_tags($this->method_id));
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->amount=htmlspecialchars(strip_tags($this->amount));
$this->currency=htmlspecialchars(strip_tags($this->currency));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->charge=htmlspecialchars(strip_tags($this->charge));
$this->trx=htmlspecialchars(strip_tags($this->trx));
$this->final_amount=htmlspecialchars(strip_tags($this->final_amount));
$this->after_charge=htmlspecialchars(strip_tags($this->after_charge));
$this->withdraw_information=htmlspecialchars(strip_tags($this->withdraw_information));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->admin_feedback=htmlspecialchars(strip_tags($this->admin_feedback));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":method_id", $this->method_id);
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":amount", $this->amount);
$stmt->bindParam(":currency", $this->currency);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":charge", $this->charge);
$stmt->bindParam(":trx", $this->trx);
$stmt->bindParam(":final_amount", $this->final_amount);
$stmt->bindParam(":after_charge", $this->after_charge);
$stmt->bindParam(":withdraw_information", $this->withdraw_information);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":admin_feedback", $this->admin_feedback);
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
	// delete the ha_withdrawals
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
