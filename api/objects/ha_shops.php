<?php
class Ha_Shops{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_shops";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $seller_id;
public $name;
public $phone;
public $logo;
public $cover;
public $opens_at;
public $closed_at;
public $address;
public $social_links;
public $meta_title;
public $meta_description;
public $meta_keywords;
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
	// read ha_shops
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.seller_id LIKE ?  OR t.name LIKE ?  OR t.phone LIKE ?  OR t.logo LIKE ?  OR t.cover LIKE ?  OR t.opens_at LIKE ?  OR t.closed_at LIKE ?  OR t.address LIKE ?  OR t.social_links LIKE ?  OR t.meta_title LIKE ?  OR t.meta_description LIKE ?  OR t.meta_keywords LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->seller_id = $row['seller_id'];
$this->name = $row['name'];
$this->phone = $row['phone'];
$this->logo = $row['logo'];
$this->cover = $row['cover'];
$this->opens_at = $row['opens_at'];
$this->closed_at = $row['closed_at'];
$this->address = $row['address'];
$this->social_links = $row['social_links'];
$this->meta_title = $row['meta_title'];
$this->meta_description = $row['meta_description'];
$this->meta_keywords = $row['meta_keywords'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_shops
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET seller_id=:seller_id,name=:name,phone=:phone,logo=:logo,cover=:cover,opens_at=:opens_at,closed_at=:closed_at,address=:address,social_links=:social_links,meta_title=:meta_title,meta_description=:meta_description,meta_keywords=:meta_keywords,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->phone=htmlspecialchars(strip_tags($this->phone));
$this->logo=htmlspecialchars(strip_tags($this->logo));
$this->cover=htmlspecialchars(strip_tags($this->cover));
$this->opens_at=htmlspecialchars(strip_tags($this->opens_at));
$this->closed_at=htmlspecialchars(strip_tags($this->closed_at));
$this->address=htmlspecialchars(strip_tags($this->address));
$this->social_links=htmlspecialchars(strip_tags($this->social_links));
$this->meta_title=htmlspecialchars(strip_tags($this->meta_title));
$this->meta_description=htmlspecialchars(strip_tags($this->meta_description));
$this->meta_keywords=htmlspecialchars(strip_tags($this->meta_keywords));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":phone", $this->phone);
$stmt->bindParam(":logo", $this->logo);
$stmt->bindParam(":cover", $this->cover);
$stmt->bindParam(":opens_at", $this->opens_at);
$stmt->bindParam(":closed_at", $this->closed_at);
$stmt->bindParam(":address", $this->address);
$stmt->bindParam(":social_links", $this->social_links);
$stmt->bindParam(":meta_title", $this->meta_title);
$stmt->bindParam(":meta_description", $this->meta_description);
$stmt->bindParam(":meta_keywords", $this->meta_keywords);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_shops
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET seller_id=:seller_id,name=:name,phone=:phone,logo=:logo,cover=:cover,opens_at=:opens_at,closed_at=:closed_at,address=:address,social_links=:social_links,meta_title=:meta_title,meta_description=:meta_description,meta_keywords=:meta_keywords,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->phone=htmlspecialchars(strip_tags($this->phone));
$this->logo=htmlspecialchars(strip_tags($this->logo));
$this->cover=htmlspecialchars(strip_tags($this->cover));
$this->opens_at=htmlspecialchars(strip_tags($this->opens_at));
$this->closed_at=htmlspecialchars(strip_tags($this->closed_at));
$this->address=htmlspecialchars(strip_tags($this->address));
$this->social_links=htmlspecialchars(strip_tags($this->social_links));
$this->meta_title=htmlspecialchars(strip_tags($this->meta_title));
$this->meta_description=htmlspecialchars(strip_tags($this->meta_description));
$this->meta_keywords=htmlspecialchars(strip_tags($this->meta_keywords));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":phone", $this->phone);
$stmt->bindParam(":logo", $this->logo);
$stmt->bindParam(":cover", $this->cover);
$stmt->bindParam(":opens_at", $this->opens_at);
$stmt->bindParam(":closed_at", $this->closed_at);
$stmt->bindParam(":address", $this->address);
$stmt->bindParam(":social_links", $this->social_links);
$stmt->bindParam(":meta_title", $this->meta_title);
$stmt->bindParam(":meta_description", $this->meta_description);
$stmt->bindParam(":meta_keywords", $this->meta_keywords);
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
	// delete the ha_shops
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
