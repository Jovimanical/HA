<?php
class Ha_Wallet_Deposits{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_wallet_deposits";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $user_id;
public $order_id;
public $method_code;
public $amount;
public $method_currency;
public $charge;
public $rate;
public $final_amo;
public $detail;
public $btc_amo;
public $btc_wallet;
public $trx;
public $try;
public $status;
public $from_api;
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
	// read ha_wallet_deposits
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.user_id LIKE ?  OR t.order_id LIKE ?  OR t.method_code LIKE ?  OR t.amount LIKE ?  OR t.method_currency LIKE ?  OR t.charge LIKE ?  OR t.rate LIKE ?  OR t.final_amo LIKE ?  OR t.detail LIKE ?  OR t.btc_amo LIKE ?  OR t.btc_wallet LIKE ?  OR t.trx LIKE ?  OR t.try LIKE ?  OR t.status LIKE ?  OR t.from_api LIKE ?  OR t.admin_feedback LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->order_id = $row['order_id'];
$this->method_code = $row['method_code'];
$this->amount = $row['amount'];
$this->method_currency = $row['method_currency'];
$this->charge = $row['charge'];
$this->rate = $row['rate'];
$this->final_amo = $row['final_amo'];
$this->detail = $row['detail'];
$this->btc_amo = $row['btc_amo'];
$this->btc_wallet = $row['btc_wallet'];
$this->trx = $row['trx'];
$this->try = $row['try'];
$this->status = $row['status'];
$this->from_api = $row['from_api'];
$this->admin_feedback = $row['admin_feedback'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_wallet_deposits
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET user_id=:user_id,order_id=:order_id,method_code=:method_code,amount=:amount,method_currency=:method_currency,charge=:charge,rate=:rate,final_amo=:final_amo,detail=:detail,btc_amo=:btc_amo,btc_wallet=:btc_wallet,trx=:trx,try=:try,status=:status,from_api=:from_api,admin_feedback=:admin_feedback,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->user_id=htmlspecialchars(strip_tags($this->user_id));
$this->order_id=htmlspecialchars(strip_tags($this->order_id));
$this->method_code=htmlspecialchars(strip_tags($this->method_code));
$this->amount=htmlspecialchars(strip_tags($this->amount));
$this->method_currency=htmlspecialchars(strip_tags($this->method_currency));
$this->charge=htmlspecialchars(strip_tags($this->charge));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->final_amo=htmlspecialchars(strip_tags($this->final_amo));
$this->detail=htmlspecialchars(strip_tags($this->detail));
$this->btc_amo=htmlspecialchars(strip_tags($this->btc_amo));
$this->btc_wallet=htmlspecialchars(strip_tags($this->btc_wallet));
$this->trx=htmlspecialchars(strip_tags($this->trx));
$this->try=htmlspecialchars(strip_tags($this->try));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->from_api=htmlspecialchars(strip_tags($this->from_api));
$this->admin_feedback=htmlspecialchars(strip_tags($this->admin_feedback));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":user_id", $this->user_id);
$stmt->bindParam(":order_id", $this->order_id);
$stmt->bindParam(":method_code", $this->method_code);
$stmt->bindParam(":amount", $this->amount);
$stmt->bindParam(":method_currency", $this->method_currency);
$stmt->bindParam(":charge", $this->charge);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":final_amo", $this->final_amo);
$stmt->bindParam(":detail", $this->detail);
$stmt->bindParam(":btc_amo", $this->btc_amo);
$stmt->bindParam(":btc_wallet", $this->btc_wallet);
$stmt->bindParam(":trx", $this->trx);
$stmt->bindParam(":try", $this->try);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":from_api", $this->from_api);
$stmt->bindParam(":admin_feedback", $this->admin_feedback);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_wallet_deposits
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET user_id=:user_id,order_id=:order_id,method_code=:method_code,amount=:amount,method_currency=:method_currency,charge=:charge,rate=:rate,final_amo=:final_amo,detail=:detail,btc_amo=:btc_amo,btc_wallet=:btc_wallet,trx=:trx,try=:try,status=:status,from_api=:from_api,admin_feedback=:admin_feedback,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->user_id=htmlspecialchars(strip_tags($this->user_id));
$this->order_id=htmlspecialchars(strip_tags($this->order_id));
$this->method_code=htmlspecialchars(strip_tags($this->method_code));
$this->amount=htmlspecialchars(strip_tags($this->amount));
$this->method_currency=htmlspecialchars(strip_tags($this->method_currency));
$this->charge=htmlspecialchars(strip_tags($this->charge));
$this->rate=htmlspecialchars(strip_tags($this->rate));
$this->final_amo=htmlspecialchars(strip_tags($this->final_amo));
$this->detail=htmlspecialchars(strip_tags($this->detail));
$this->btc_amo=htmlspecialchars(strip_tags($this->btc_amo));
$this->btc_wallet=htmlspecialchars(strip_tags($this->btc_wallet));
$this->trx=htmlspecialchars(strip_tags($this->trx));
$this->try=htmlspecialchars(strip_tags($this->try));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->from_api=htmlspecialchars(strip_tags($this->from_api));
$this->admin_feedback=htmlspecialchars(strip_tags($this->admin_feedback));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":user_id", $this->user_id);
$stmt->bindParam(":order_id", $this->order_id);
$stmt->bindParam(":method_code", $this->method_code);
$stmt->bindParam(":amount", $this->amount);
$stmt->bindParam(":method_currency", $this->method_currency);
$stmt->bindParam(":charge", $this->charge);
$stmt->bindParam(":rate", $this->rate);
$stmt->bindParam(":final_amo", $this->final_amo);
$stmt->bindParam(":detail", $this->detail);
$stmt->bindParam(":btc_amo", $this->btc_amo);
$stmt->bindParam(":btc_wallet", $this->btc_wallet);
$stmt->bindParam(":trx", $this->trx);
$stmt->bindParam(":try", $this->try);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":from_api", $this->from_api);
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
	// delete the ha_wallet_deposits
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
