<?php
class Ha_Categories{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_categories";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $parent_id;
public $name;
public $icon;
public $meta_title;
public $meta_description;
public $meta_keywords;
public $image;
public $is_top;
public $is_special;
public $in_filter_menu;
public $created_at;
public $updated_at;
public $deleted_at;
    
 
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
	// read ha_categories
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.parent_id LIKE ?  OR t.name LIKE ?  OR t.icon LIKE ?  OR t.meta_title LIKE ?  OR t.meta_description LIKE ?  OR t.meta_keywords LIKE ?  OR t.image LIKE ?  OR t.is_top LIKE ?  OR t.is_special LIKE ?  OR t.in_filter_menu LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  OR t.deleted_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$this->parent_id = $row['parent_id'];
$this->name = $row['name'];
$this->icon = $row['icon'];
$this->meta_title = $row['meta_title'];
$this->meta_description = $row['meta_description'];
$this->meta_keywords = $row['meta_keywords'];
$this->image = $row['image'];
$this->is_top = $row['is_top'];
$this->is_special = $row['is_special'];
$this->in_filter_menu = $row['in_filter_menu'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
$this->deleted_at = $row['deleted_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_categories
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET parent_id=:parent_id,name=:name,icon=:icon,meta_title=:meta_title,meta_description=:meta_description,meta_keywords=:meta_keywords,image=:image,is_top=:is_top,is_special=:is_special,in_filter_menu=:in_filter_menu,created_at=:created_at,updated_at=:updated_at,deleted_at=:deleted_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->parent_id=htmlspecialchars(strip_tags($this->parent_id));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->icon=htmlspecialchars(strip_tags($this->icon));
$this->meta_title=htmlspecialchars(strip_tags($this->meta_title));
$this->meta_description=htmlspecialchars(strip_tags($this->meta_description));
$this->meta_keywords=htmlspecialchars(strip_tags($this->meta_keywords));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->is_top=htmlspecialchars(strip_tags($this->is_top));
$this->is_special=htmlspecialchars(strip_tags($this->is_special));
$this->in_filter_menu=htmlspecialchars(strip_tags($this->in_filter_menu));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
	 
		// bind values
		
$stmt->bindParam(":parent_id", $this->parent_id);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":icon", $this->icon);
$stmt->bindParam(":meta_title", $this->meta_title);
$stmt->bindParam(":meta_description", $this->meta_description);
$stmt->bindParam(":meta_keywords", $this->meta_keywords);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":is_top", $this->is_top);
$stmt->bindParam(":is_special", $this->is_special);
$stmt->bindParam(":in_filter_menu", $this->in_filter_menu);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
$stmt->bindParam(":deleted_at", $this->deleted_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_categories
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET parent_id=:parent_id,name=:name,icon=:icon,meta_title=:meta_title,meta_description=:meta_description,meta_keywords=:meta_keywords,image=:image,is_top=:is_top,is_special=:is_special,in_filter_menu=:in_filter_menu,created_at=:created_at,updated_at=:updated_at,deleted_at=:deleted_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->parent_id=htmlspecialchars(strip_tags($this->parent_id));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->icon=htmlspecialchars(strip_tags($this->icon));
$this->meta_title=htmlspecialchars(strip_tags($this->meta_title));
$this->meta_description=htmlspecialchars(strip_tags($this->meta_description));
$this->meta_keywords=htmlspecialchars(strip_tags($this->meta_keywords));
$this->image=htmlspecialchars(strip_tags($this->image));
$this->is_top=htmlspecialchars(strip_tags($this->is_top));
$this->is_special=htmlspecialchars(strip_tags($this->is_special));
$this->in_filter_menu=htmlspecialchars(strip_tags($this->in_filter_menu));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":parent_id", $this->parent_id);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":icon", $this->icon);
$stmt->bindParam(":meta_title", $this->meta_title);
$stmt->bindParam(":meta_description", $this->meta_description);
$stmt->bindParam(":meta_keywords", $this->meta_keywords);
$stmt->bindParam(":image", $this->image);
$stmt->bindParam(":is_top", $this->is_top);
$stmt->bindParam(":is_special", $this->is_special);
$stmt->bindParam(":in_filter_menu", $this->in_filter_menu);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
$stmt->bindParam(":deleted_at", $this->deleted_at);
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
	// delete the ha_categories
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
