<?php
class Ha_General_Settings{
 
    // database connection and table name
    private $conn;
    private $table_name = "ha_general_settings";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
    // object properties
	
public $id;
public $sitename;
public $cur_text;
public $cur_sym;
public $email_from;
public $email_template;
public $sms_api;
public $base_color;
public $secondary_color;
public $mail_config;
public $sms_config;
public $ev;
public $en;
public $sv;
public $sn;
public $force_ssl;
public $secure_password;
public $agree;
public $cod;
public $registration;
public $active_template;
public $product_commission;
public $seller_withdraw_limit;
public $sys_version;
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
	// read ha_general_settings
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.id LIKE ? OR t.sitename LIKE ?  OR t.cur_text LIKE ?  OR t.cur_sym LIKE ?  OR t.email_from LIKE ?  OR t.email_template LIKE ?  OR t.sms_api LIKE ?  OR t.base_color LIKE ?  OR t.secondary_color LIKE ?  OR t.mail_config LIKE ?  OR t.sms_config LIKE ?  OR t.ev LIKE ?  OR t.en LIKE ?  OR t.sv LIKE ?  OR t.sn LIKE ?  OR t.force_ssl LIKE ?  OR t.secure_password LIKE ?  OR t.agree LIKE ?  OR t.cod LIKE ?  OR t.registration LIKE ?  OR t.active_template LIKE ?  OR t.product_commission LIKE ?  OR t.seller_withdraw_limit LIKE ?  OR t.sys_version LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
	 
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
$stmt->bindParam(26, $searchKey);
	 
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
$this->sitename = $row['sitename'];
$this->cur_text = $row['cur_text'];
$this->cur_sym = $row['cur_sym'];
$this->email_from = $row['email_from'];
$this->email_template = $row['email_template'];
$this->sms_api = $row['sms_api'];
$this->base_color = $row['base_color'];
$this->secondary_color = $row['secondary_color'];
$this->mail_config = $row['mail_config'];
$this->sms_config = $row['sms_config'];
$this->ev = $row['ev'];
$this->en = $row['en'];
$this->sv = $row['sv'];
$this->sn = $row['sn'];
$this->force_ssl = $row['force_ssl'];
$this->secure_password = $row['secure_password'];
$this->agree = $row['agree'];
$this->cod = $row['cod'];
$this->registration = $row['registration'];
$this->active_template = $row['active_template'];
$this->product_commission = $row['product_commission'];
$this->seller_withdraw_limit = $row['seller_withdraw_limit'];
$this->sys_version = $row['sys_version'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
		$this->id=null;
		}
	}

	
	
	// create ha_general_settings
	function create(){
	 
		// query to insert record
		$query ="INSERT INTO ".$this->table_name." SET sitename=:sitename,cur_text=:cur_text,cur_sym=:cur_sym,email_from=:email_from,email_template=:email_template,sms_api=:sms_api,base_color=:base_color,secondary_color=:secondary_color,mail_config=:mail_config,sms_config=:sms_config,ev=:ev,en=:en,sv=:sv,sn=:sn,force_ssl=:force_ssl,secure_password=:secure_password,agree=:agree,cod=:cod,registration=:registration,active_template=:active_template,product_commission=:product_commission,seller_withdraw_limit=:seller_withdraw_limit,sys_version=:sys_version,created_at=:created_at,updated_at=:updated_at";

		// prepare query
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->sitename=htmlspecialchars(strip_tags($this->sitename));
$this->cur_text=htmlspecialchars(strip_tags($this->cur_text));
$this->cur_sym=htmlspecialchars(strip_tags($this->cur_sym));
$this->email_from=htmlspecialchars(strip_tags($this->email_from));
$this->email_template=htmlspecialchars(strip_tags($this->email_template));
$this->sms_api=htmlspecialchars(strip_tags($this->sms_api));
$this->base_color=htmlspecialchars(strip_tags($this->base_color));
$this->secondary_color=htmlspecialchars(strip_tags($this->secondary_color));
$this->mail_config=htmlspecialchars(strip_tags($this->mail_config));
$this->sms_config=htmlspecialchars(strip_tags($this->sms_config));
$this->ev=htmlspecialchars(strip_tags($this->ev));
$this->en=htmlspecialchars(strip_tags($this->en));
$this->sv=htmlspecialchars(strip_tags($this->sv));
$this->sn=htmlspecialchars(strip_tags($this->sn));
$this->force_ssl=htmlspecialchars(strip_tags($this->force_ssl));
$this->secure_password=htmlspecialchars(strip_tags($this->secure_password));
$this->agree=htmlspecialchars(strip_tags($this->agree));
$this->cod=htmlspecialchars(strip_tags($this->cod));
$this->registration=htmlspecialchars(strip_tags($this->registration));
$this->active_template=htmlspecialchars(strip_tags($this->active_template));
$this->product_commission=htmlspecialchars(strip_tags($this->product_commission));
$this->seller_withdraw_limit=htmlspecialchars(strip_tags($this->seller_withdraw_limit));
$this->sys_version=htmlspecialchars(strip_tags($this->sys_version));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
	 
		// bind values
		
$stmt->bindParam(":sitename", $this->sitename);
$stmt->bindParam(":cur_text", $this->cur_text);
$stmt->bindParam(":cur_sym", $this->cur_sym);
$stmt->bindParam(":email_from", $this->email_from);
$stmt->bindParam(":email_template", $this->email_template);
$stmt->bindParam(":sms_api", $this->sms_api);
$stmt->bindParam(":base_color", $this->base_color);
$stmt->bindParam(":secondary_color", $this->secondary_color);
$stmt->bindParam(":mail_config", $this->mail_config);
$stmt->bindParam(":sms_config", $this->sms_config);
$stmt->bindParam(":ev", $this->ev);
$stmt->bindParam(":en", $this->en);
$stmt->bindParam(":sv", $this->sv);
$stmt->bindParam(":sn", $this->sn);
$stmt->bindParam(":force_ssl", $this->force_ssl);
$stmt->bindParam(":secure_password", $this->secure_password);
$stmt->bindParam(":agree", $this->agree);
$stmt->bindParam(":cod", $this->cod);
$stmt->bindParam(":registration", $this->registration);
$stmt->bindParam(":active_template", $this->active_template);
$stmt->bindParam(":product_commission", $this->product_commission);
$stmt->bindParam(":seller_withdraw_limit", $this->seller_withdraw_limit);
$stmt->bindParam(":sys_version", $this->sys_version);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
	 
		// execute query
		if($stmt->execute()){
			return  $this->conn->lastInsertId();
		}
	 
		return 0;
		 
	}
	
	
	
	// update the ha_general_settings
	function update(){
	 
		// update query
		$query ="UPDATE ".$this->table_name." SET sitename=:sitename,cur_text=:cur_text,cur_sym=:cur_sym,email_from=:email_from,email_template=:email_template,sms_api=:sms_api,base_color=:base_color,secondary_color=:secondary_color,mail_config=:mail_config,sms_config=:sms_config,ev=:ev,en=:en,sv=:sv,sn=:sn,force_ssl=:force_ssl,secure_password=:secure_password,agree=:agree,cod=:cod,registration=:registration,active_template=:active_template,product_commission=:product_commission,seller_withdraw_limit=:seller_withdraw_limit,sys_version=:sys_version,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// sanitize
		
$this->sitename=htmlspecialchars(strip_tags($this->sitename));
$this->cur_text=htmlspecialchars(strip_tags($this->cur_text));
$this->cur_sym=htmlspecialchars(strip_tags($this->cur_sym));
$this->email_from=htmlspecialchars(strip_tags($this->email_from));
$this->email_template=htmlspecialchars(strip_tags($this->email_template));
$this->sms_api=htmlspecialchars(strip_tags($this->sms_api));
$this->base_color=htmlspecialchars(strip_tags($this->base_color));
$this->secondary_color=htmlspecialchars(strip_tags($this->secondary_color));
$this->mail_config=htmlspecialchars(strip_tags($this->mail_config));
$this->sms_config=htmlspecialchars(strip_tags($this->sms_config));
$this->ev=htmlspecialchars(strip_tags($this->ev));
$this->en=htmlspecialchars(strip_tags($this->en));
$this->sv=htmlspecialchars(strip_tags($this->sv));
$this->sn=htmlspecialchars(strip_tags($this->sn));
$this->force_ssl=htmlspecialchars(strip_tags($this->force_ssl));
$this->secure_password=htmlspecialchars(strip_tags($this->secure_password));
$this->agree=htmlspecialchars(strip_tags($this->agree));
$this->cod=htmlspecialchars(strip_tags($this->cod));
$this->registration=htmlspecialchars(strip_tags($this->registration));
$this->active_template=htmlspecialchars(strip_tags($this->active_template));
$this->product_commission=htmlspecialchars(strip_tags($this->product_commission));
$this->seller_withdraw_limit=htmlspecialchars(strip_tags($this->seller_withdraw_limit));
$this->sys_version=htmlspecialchars(strip_tags($this->sys_version));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind new values
		
$stmt->bindParam(":sitename", $this->sitename);
$stmt->bindParam(":cur_text", $this->cur_text);
$stmt->bindParam(":cur_sym", $this->cur_sym);
$stmt->bindParam(":email_from", $this->email_from);
$stmt->bindParam(":email_template", $this->email_template);
$stmt->bindParam(":sms_api", $this->sms_api);
$stmt->bindParam(":base_color", $this->base_color);
$stmt->bindParam(":secondary_color", $this->secondary_color);
$stmt->bindParam(":mail_config", $this->mail_config);
$stmt->bindParam(":sms_config", $this->sms_config);
$stmt->bindParam(":ev", $this->ev);
$stmt->bindParam(":en", $this->en);
$stmt->bindParam(":sv", $this->sv);
$stmt->bindParam(":sn", $this->sn);
$stmt->bindParam(":force_ssl", $this->force_ssl);
$stmt->bindParam(":secure_password", $this->secure_password);
$stmt->bindParam(":agree", $this->agree);
$stmt->bindParam(":cod", $this->cod);
$stmt->bindParam(":registration", $this->registration);
$stmt->bindParam(":active_template", $this->active_template);
$stmt->bindParam(":product_commission", $this->product_commission);
$stmt->bindParam(":seller_withdraw_limit", $this->seller_withdraw_limit);
$stmt->bindParam(":sys_version", $this->sys_version);
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
	// delete the ha_general_settings
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
