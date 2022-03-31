<?php

class Ha_Coupons
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_coupons";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $coupon_name;
    public $coupon_code;
    public $discount_type;
    public $coupon_amount;
    public $description;
    public $minimum_spend;
    public $maximum_spend;
    public $usage_limit_per_coupon;
    public $usage_limit_per_user;
    public $status;
    public $start_date;
    public $end_date;
    public $created_at;
    public $updated_at;


    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function total_record_count()
    {
        $query = "select count(1) as total from " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0) {
            return $row['total'];
        } else {
            return 0;
        }
    }

    function search_record_count($columnArray, $orAnd)
    {

        $where = "";

        foreach ($columnArray as $col) {
            $columnName = htmlspecialchars(strip_tags($col->columnName));
            $columnLogic = $col->columnLogic;
            if ($where == "") {
                $where = "LOWER(t." . $columnName . ") " . $columnLogic . " :" . $columnName;
            } else {
                $where = $where . " " . $orAnd . " LOWER(t." . $columnName . ") " . $columnLogic . " :" . $columnName;
            }
        }
        $query = "SELECT count(1) as total FROM " . $this->table_name . " t  WHERE " . $where . "";

        $stmt = $this->conn->prepare($query);
        $paramCount = 1;
        foreach ($columnArray as $col) {
            $columnName = htmlspecialchars(strip_tags($col->columnName));
            if (strtoupper($col->columnLogic) == "LIKE") {
                $columnValue = "%" . strtolower($col->columnValue) . "%";
            } else {
                $columnValue = strtolower($col->columnValue);
            }

            $stmt->bindValue(":" . $columnName, $columnValue);
            $paramCount++;

        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0) {
            return $row['total'];
        } else {
            return 0;
        }
    }

    // read ha_coupons
    function read()
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
        // select all query
        $query = "SELECT  t.* FROM " . $this->table_name . " t  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    //Search table
    function search($searchKey)
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;

        // select all query
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.coupon_name LIKE ?  OR t.coupon_code LIKE ?  OR t.discount_type LIKE ?  OR t.coupon_amount LIKE ?  OR t.description LIKE ?  OR t.minimum_spend LIKE ?  OR t.maximum_spend LIKE ?  OR t.usage_limit_per_coupon LIKE ?  OR t.usage_limit_per_user LIKE ?  OR t.status LIKE ?  OR t.start_date LIKE ?  OR t.end_date LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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

    function searchByColumn($columnArray, $orAnd)
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
        $where = "";

        foreach ($columnArray as $col) {
            $columnName = htmlspecialchars(strip_tags($col->columnName));
            $columnLogic = $col->columnLogic;
            if ($where == "") {
                $where = "LOWER(t." . $columnName . ") " . $columnLogic . " :" . $columnName;
            } else {
                $where = $where . " " . $orAnd . " LOWER(t." . $columnName . ") " . $columnLogic . " :" . $columnName;
            }
        }
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE " . $where . " LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

        $stmt = $this->conn->prepare($query);
        $paramCount = 1;
        foreach ($columnArray as $col) {
            $columnName = htmlspecialchars(strip_tags($col->columnName));
            if (strtoupper($col->columnLogic) == "LIKE") {
                $columnValue = "%" . strtolower($col->columnValue) . "%";
            } else {
                $columnValue = strtolower($col->columnValue);
            }

            $stmt->bindValue(":" . $columnName, $columnValue);
            $paramCount++;

        }

        $stmt->execute();
        return $stmt;
    }


    function readOne()
    {

        // query to read single record
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id = ? LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0) {
            // set values to object properties

            $this->id = $row['id'];
            $this->coupon_name = $row['coupon_name'];
            $this->coupon_code = $row['coupon_code'];
            $this->discount_type = $row['discount_type'];
            $this->coupon_amount = $row['coupon_amount'];
            $this->description = $row['description'];
            $this->minimum_spend = $row['minimum_spend'];
            $this->maximum_spend = $row['maximum_spend'];
            $this->usage_limit_per_coupon = $row['usage_limit_per_coupon'];
            $this->usage_limit_per_user = $row['usage_limit_per_user'];
            $this->status = $row['status'];
            $this->start_date = $row['start_date'];
            $this->end_date = $row['end_date'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }


    // create ha_coupons
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET coupon_name=:coupon_name,coupon_code=:coupon_code,discount_type=:discount_type,coupon_amount=:coupon_amount,description=:description,minimum_spend=:minimum_spend,maximum_spend=:maximum_spend,usage_limit_per_coupon=:usage_limit_per_coupon,usage_limit_per_user=:usage_limit_per_user,status=:status,start_date=:start_date,end_date=:end_date,created_at=:created_at,updated_at=:updated_at";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->coupon_name = htmlspecialchars(strip_tags($this->coupon_name));
        $this->coupon_code = htmlspecialchars(strip_tags($this->coupon_code));
        $this->discount_type = htmlspecialchars(strip_tags($this->discount_type));
        $this->coupon_amount = htmlspecialchars(strip_tags($this->coupon_amount));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->minimum_spend = htmlspecialchars(strip_tags($this->minimum_spend));
        $this->maximum_spend = htmlspecialchars(strip_tags($this->maximum_spend));
        $this->usage_limit_per_coupon = htmlspecialchars(strip_tags($this->usage_limit_per_coupon));
        $this->usage_limit_per_user = htmlspecialchars(strip_tags($this->usage_limit_per_user));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind values

        $stmt->bindParam(":coupon_name", $this->coupon_name);
        $stmt->bindParam(":coupon_code", $this->coupon_code);
        $stmt->bindParam(":discount_type", $this->discount_type);
        $stmt->bindParam(":coupon_amount", $this->coupon_amount);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":minimum_spend", $this->minimum_spend);
        $stmt->bindParam(":maximum_spend", $this->maximum_spend);
        $stmt->bindParam(":usage_limit_per_coupon", $this->usage_limit_per_coupon);
        $stmt->bindParam(":usage_limit_per_user", $this->usage_limit_per_user);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_coupons
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET coupon_name=:coupon_name,coupon_code=:coupon_code,discount_type=:discount_type,coupon_amount=:coupon_amount,description=:description,minimum_spend=:minimum_spend,maximum_spend=:maximum_spend,usage_limit_per_coupon=:usage_limit_per_coupon,usage_limit_per_user=:usage_limit_per_user,status=:status,start_date=:start_date,end_date=:end_date,created_at=:created_at,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->coupon_name = htmlspecialchars(strip_tags($this->coupon_name));
        $this->coupon_code = htmlspecialchars(strip_tags($this->coupon_code));
        $this->discount_type = htmlspecialchars(strip_tags($this->discount_type));
        $this->coupon_amount = htmlspecialchars(strip_tags($this->coupon_amount));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->minimum_spend = htmlspecialchars(strip_tags($this->minimum_spend));
        $this->maximum_spend = htmlspecialchars(strip_tags($this->maximum_spend));
        $this->usage_limit_per_coupon = htmlspecialchars(strip_tags($this->usage_limit_per_coupon));
        $this->usage_limit_per_user = htmlspecialchars(strip_tags($this->usage_limit_per_user));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":coupon_name", $this->coupon_name);
        $stmt->bindParam(":coupon_code", $this->coupon_code);
        $stmt->bindParam(":discount_type", $this->discount_type);
        $stmt->bindParam(":coupon_amount", $this->coupon_amount);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":minimum_spend", $this->minimum_spend);
        $stmt->bindParam(":maximum_spend", $this->maximum_spend);
        $stmt->bindParam(":usage_limit_per_coupon", $this->usage_limit_per_coupon);
        $stmt->bindParam(":usage_limit_per_user", $this->usage_limit_per_user);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    function update_patch($jsonObj)
    {
        $query = "UPDATE " . $this->table_name;
        $setValue = "";
        $colCount = 1;
        foreach ($jsonObj as $key => $value) {
            $columnName = htmlspecialchars(strip_tags($key));
            if ($columnName != 'id') {
                if ($colCount === 1) {
                    $setValue = $columnName . "=:" . $columnName;
                } else {
                    $setValue = $setValue . "," . $columnName . "=:" . $columnName;
                }
                $colCount++;
            }
        }
        $setValue = rtrim($setValue, ',');
        $query = $query . " " . $setValue . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        foreach ($jsonObj as $key => $value) {
            $columnName = htmlspecialchars(strip_tags($key));
            if ($columnName != 'id') {
                $colValue = htmlspecialchars(strip_tags($value));
                $stmt->bindValue(":" . $columnName, $colValue);
            }
        }
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    // delete the ha_coupons
    function delete()
    {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }

    }


    //extra function will be generated for one to many relations
}

?>
