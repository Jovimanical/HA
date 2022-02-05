<?php
class Ha_Orders{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_orders";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $order_number;
public $user_id;
public $shipping_address;
public $shipping_method_id;
public $shipping_charge;
public $coupon_code;
public $coupon_amount;
public $total_amount;
public $order_type;
public $payment_status;
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
	// read ha_orders
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.order_number LIKE ?  OR t.user_id LIKE ?  OR t.shipping_address LIKE ?  OR t.shipping_method_id LIKE ?  OR t.shipping_charge LIKE ?  OR t.coupon_code LIKE ?  OR t.coupon_amount LIKE ?  OR t.total_amount LIKE ?  OR t.order_type LIKE ?  OR t.payment_status LIKE ?  OR t.status LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->order_number = $row['order_number'];
$this->user_id = $row['user_id'];
$this->shipping_address = $row['shipping_address'];
$this->shipping_method_id = $row['shipping_method_id'];
$this->shipping_charge = $row['shipping_charge'];
$this->coupon_code = $row['coupon_code'];
$this->coupon_amount = $row['coupon_amount'];
$this->total_amount = $row['total_amount'];
$this->order_type = $row['order_type'];
$this->payment_status = $row['payment_status'];
$this->status = $row['status'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_orders
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET order_number=:order_number,user_id=:user_id,shipping_address=:shipping_address,shipping_method_id=:shipping_method_id,shipping_charge=:shipping_charge,coupon_code=:coupon_code,coupon_amount=:coupon_amount,total_amount=:total_amount,order_type=:order_type,payment_status=:payment_status,status=:status,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->order_number=htmlspecialchars(strip_tags($this->order_number));
$this->user_id=htmlspecialchars(strip_tags($this->user_id));
$this->shipping_address=htmlspecialchars(strip_tags($this->shipping_address));
$this->shipping_method_id=htmlspecialchars(strip_tags($this->shipping_method_id));
$this->shipping_charge=htmlspecialchars(strip_tags($this->shipping_charge));
$this->coupon_code=htmlspecialchars(strip_tags($this->coupon_code));
$this->coupon_amount=htmlspecialchars(strip_tags($this->coupon_amount));
$this->total_amount=htmlspecialchars(strip_tags($this->total_amount));
$this->order_type=htmlspecialchars(strip_tags($this->order_type));
$this->payment_status=htmlspecialchars(strip_tags($this->payment_status));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":order_number", $this->order_number);
$stmt->bindParam(":user_id", $this->user_id);
$stmt->bindParam(":shipping_address", $this->shipping_address);
$stmt->bindParam(":shipping_method_id", $this->shipping_method_id);
$stmt->bindParam(":shipping_charge", $this->shipping_charge);
$stmt->bindParam(":coupon_code", $this->coupon_code);
$stmt->bindParam(":coupon_amount", $this->coupon_amount);
$stmt->bindParam(":total_amount", $this->total_amount);
$stmt->bindParam(":order_type", $this->order_type);
$stmt->bindParam(":payment_status", $this->payment_status);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_orders
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET order_number=:order_number,user_id=:user_id,shipping_address=:shipping_address,shipping_method_id=:shipping_method_id,shipping_charge=:shipping_charge,coupon_code=:coupon_code,coupon_amount=:coupon_amount,total_amount=:total_amount,order_type=:order_type,payment_status=:payment_status,status=:status,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->order_number=htmlspecialchars(strip_tags($this->order_number));
$this->user_id=htmlspecialchars(strip_tags($this->user_id));
$this->shipping_address=htmlspecialchars(strip_tags($this->shipping_address));
$this->shipping_method_id=htmlspecialchars(strip_tags($this->shipping_method_id));
$this->shipping_charge=htmlspecialchars(strip_tags($this->shipping_charge));
$this->coupon_code=htmlspecialchars(strip_tags($this->coupon_code));
$this->coupon_amount=htmlspecialchars(strip_tags($this->coupon_amount));
$this->total_amount=htmlspecialchars(strip_tags($this->total_amount));
$this->order_type=htmlspecialchars(strip_tags($this->order_type));
$this->payment_status=htmlspecialchars(strip_tags($this->payment_status));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":order_number", $this->order_number);
$stmt->bindParam(":user_id", $this->user_id);
$stmt->bindParam(":shipping_address", $this->shipping_address);
$stmt->bindParam(":shipping_method_id", $this->shipping_method_id);
$stmt->bindParam(":shipping_charge", $this->shipping_charge);
$stmt->bindParam(":coupon_code", $this->coupon_code);
$stmt->bindParam(":coupon_amount", $this->coupon_amount);
$stmt->bindParam(":total_amount", $this->total_amount);
$stmt->bindParam(":order_type", $this->order_type);
$stmt->bindParam(":payment_status", $this->payment_status);
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
	// delete the ha_orders
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
