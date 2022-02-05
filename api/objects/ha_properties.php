<?php
class Ha_Properties{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_properties";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $seller_id;
public $brand_id;
public $sku;
public $name;
public $model;
public $has_variants;
public $track_inventory;
public $show_in_frontend;
public $main_image;
public $video_link;
public $description;
public $summary;
public $specification;
public $extra_descriptions;
public $base_price;
public $is_featured;
public $meta_title;
public $meta_description;
public $meta_keywords;
public $status;
public $sold;
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
	// read ha_properties
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.seller_id LIKE ?  OR t.brand_id LIKE ?  OR t.sku LIKE ?  OR t.name LIKE ?  OR t.model LIKE ?  OR t.has_variants LIKE ?  OR t.track_inventory LIKE ?  OR t.show_in_frontend LIKE ?  OR t.main_image LIKE ?  OR t.video_link LIKE ?  OR t.description LIKE ?  OR t.summary LIKE ?  OR t.specification LIKE ?  OR t.extra_descriptions LIKE ?  OR t.base_price LIKE ?  OR t.is_featured LIKE ?  OR t.meta_title LIKE ?  OR t.meta_description LIKE ?  OR t.meta_keywords LIKE ?  OR t.status LIKE ?  OR t.sold LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  OR t.deleted_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$stmt->bindParam(21, $searchKey);
$stmt->bindParam(22, $searchKey);
$stmt->bindParam(23, $searchKey);
$stmt->bindParam(24, $searchKey);
$stmt->bindParam(25, $searchKey);
	 
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
$this->brand_id = $row['brand_id'];
$this->sku = $row['sku'];
$this->name = $row['name'];
$this->model = $row['model'];
$this->has_variants = $row['has_variants'];
$this->track_inventory = $row['track_inventory'];
$this->show_in_frontend = $row['show_in_frontend'];
$this->main_image = $row['main_image'];
$this->video_link = $row['video_link'];
$this->description = $row['description'];
$this->summary = $row['summary'];
$this->specification = $row['specification'];
$this->extra_descriptions = $row['extra_descriptions'];
$this->base_price = $row['base_price'];
$this->is_featured = $row['is_featured'];
$this->meta_title = $row['meta_title'];
$this->meta_description = $row['meta_description'];
$this->meta_keywords = $row['meta_keywords'];
$this->status = $row['status'];
$this->sold = $row['sold'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
$this->deleted_at = $row['deleted_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_properties
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET seller_id=:seller_id,brand_id=:brand_id,sku=:sku,name=:name,model=:model,has_variants=:has_variants,track_inventory=:track_inventory,show_in_frontend=:show_in_frontend,main_image=:main_image,video_link=:video_link,description=:description,summary=:summary,specification=:specification,extra_descriptions=:extra_descriptions,base_price=:base_price,is_featured=:is_featured,meta_title=:meta_title,meta_description=:meta_description,meta_keywords=:meta_keywords,status=:status,sold=:sold,created_at=:created_at,updated_at=:updated_at,deleted_at=:deleted_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->brand_id=htmlspecialchars(strip_tags($this->brand_id));
$this->sku=htmlspecialchars(strip_tags($this->sku));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->model=htmlspecialchars(strip_tags($this->model));
$this->has_variants=htmlspecialchars(strip_tags($this->has_variants));
$this->track_inventory=htmlspecialchars(strip_tags($this->track_inventory));
$this->show_in_frontend=htmlspecialchars(strip_tags($this->show_in_frontend));
$this->main_image=htmlspecialchars(strip_tags($this->main_image));
$this->video_link=htmlspecialchars(strip_tags($this->video_link));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->summary=htmlspecialchars(strip_tags($this->summary));
$this->specification=htmlspecialchars(strip_tags($this->specification));
$this->extra_descriptions=htmlspecialchars(strip_tags($this->extra_descriptions));
$this->base_price=htmlspecialchars(strip_tags($this->base_price));
$this->is_featured=htmlspecialchars(strip_tags($this->is_featured));
$this->meta_title=htmlspecialchars(strip_tags($this->meta_title));
$this->meta_description=htmlspecialchars(strip_tags($this->meta_description));
$this->meta_keywords=htmlspecialchars(strip_tags($this->meta_keywords));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->sold=htmlspecialchars(strip_tags($this->sold));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
	 
		// bind values
		
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":brand_id", $this->brand_id);
$stmt->bindParam(":sku", $this->sku);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":model", $this->model);
$stmt->bindParam(":has_variants", $this->has_variants);
$stmt->bindParam(":track_inventory", $this->track_inventory);
$stmt->bindParam(":show_in_frontend", $this->show_in_frontend);
$stmt->bindParam(":main_image", $this->main_image);
$stmt->bindParam(":video_link", $this->video_link);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":summary", $this->summary);
$stmt->bindParam(":specification", $this->specification);
$stmt->bindParam(":extra_descriptions", $this->extra_descriptions);
$stmt->bindParam(":base_price", $this->base_price);
$stmt->bindParam(":is_featured", $this->is_featured);
$stmt->bindParam(":meta_title", $this->meta_title);
$stmt->bindParam(":meta_description", $this->meta_description);
$stmt->bindParam(":meta_keywords", $this->meta_keywords);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":sold", $this->sold);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
$stmt->bindParam(":deleted_at", $this->deleted_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_properties
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET seller_id=:seller_id,brand_id=:brand_id,sku=:sku,name=:name,model=:model,has_variants=:has_variants,track_inventory=:track_inventory,show_in_frontend=:show_in_frontend,main_image=:main_image,video_link=:video_link,description=:description,summary=:summary,specification=:specification,extra_descriptions=:extra_descriptions,base_price=:base_price,is_featured=:is_featured,meta_title=:meta_title,meta_description=:meta_description,meta_keywords=:meta_keywords,status=:status,sold=:sold,created_at=:created_at,updated_at=:updated_at,deleted_at=:deleted_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->seller_id=htmlspecialchars(strip_tags($this->seller_id));
$this->brand_id=htmlspecialchars(strip_tags($this->brand_id));
$this->sku=htmlspecialchars(strip_tags($this->sku));
$this->name=htmlspecialchars(strip_tags($this->name));
$this->model=htmlspecialchars(strip_tags($this->model));
$this->has_variants=htmlspecialchars(strip_tags($this->has_variants));
$this->track_inventory=htmlspecialchars(strip_tags($this->track_inventory));
$this->show_in_frontend=htmlspecialchars(strip_tags($this->show_in_frontend));
$this->main_image=htmlspecialchars(strip_tags($this->main_image));
$this->video_link=htmlspecialchars(strip_tags($this->video_link));
$this->description=htmlspecialchars(strip_tags($this->description));
$this->summary=htmlspecialchars(strip_tags($this->summary));
$this->specification=htmlspecialchars(strip_tags($this->specification));
$this->extra_descriptions=htmlspecialchars(strip_tags($this->extra_descriptions));
$this->base_price=htmlspecialchars(strip_tags($this->base_price));
$this->is_featured=htmlspecialchars(strip_tags($this->is_featured));
$this->meta_title=htmlspecialchars(strip_tags($this->meta_title));
$this->meta_description=htmlspecialchars(strip_tags($this->meta_description));
$this->meta_keywords=htmlspecialchars(strip_tags($this->meta_keywords));
$this->status=htmlspecialchars(strip_tags($this->status));
$this->sold=htmlspecialchars(strip_tags($this->sold));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->deleted_at=htmlspecialchars(strip_tags($this->deleted_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":seller_id", $this->seller_id);
$stmt->bindParam(":brand_id", $this->brand_id);
$stmt->bindParam(":sku", $this->sku);
$stmt->bindParam(":name", $this->name);
$stmt->bindParam(":model", $this->model);
$stmt->bindParam(":has_variants", $this->has_variants);
$stmt->bindParam(":track_inventory", $this->track_inventory);
$stmt->bindParam(":show_in_frontend", $this->show_in_frontend);
$stmt->bindParam(":main_image", $this->main_image);
$stmt->bindParam(":video_link", $this->video_link);
$stmt->bindParam(":description", $this->description);
$stmt->bindParam(":summary", $this->summary);
$stmt->bindParam(":specification", $this->specification);
$stmt->bindParam(":extra_descriptions", $this->extra_descriptions);
$stmt->bindParam(":base_price", $this->base_price);
$stmt->bindParam(":is_featured", $this->is_featured);
$stmt->bindParam(":meta_title", $this->meta_title);
$stmt->bindParam(":meta_description", $this->meta_description);
$stmt->bindParam(":meta_keywords", $this->meta_keywords);
$stmt->bindParam(":status", $this->status);
$stmt->bindParam(":sold", $this->sold);
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
	// delete the ha_properties
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
