<?php

class Ha_Kyc_Employment_Status
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_kyc_employment_status";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $customer_employment_status;
    public $customer_employer_name;
    public $customer_employer_office_number;
    public $customer_employer_address;
    public $customer_employer_nearest_bustop;
    public $customer_employer_state;
    public $customer_employer_city;
    public $customer_employer_lga;
    public $customer_employer_country;
    public $customer_employer_doe;
    public $customer_account_bvn;
    public $customer_account_monthly_salary;
    public $customer_account_primary_bank;
    public $customer_account_primary_bank_account;
    public $user_id;
    public $follow_up;
    public $comment;
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

    // read ha_kyc_employment_status
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
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.customer_employment_status LIKE ?  OR t.customer_employer_name LIKE ?  OR t.customer_employer_office_number LIKE ?  OR t.customer_employer_address LIKE ?  OR t.customer_employer_nearest_bustop LIKE ?  OR t.customer_employer_state LIKE ?  OR t.customer_employer_city LIKE ?  OR t.customer_employer_lga LIKE ?  OR t.customer_employer_country LIKE ?  OR t.customer_employer_doe LIKE ?  OR t.customer_account_bvn LIKE ?  OR t.customer_account_monthly_salary LIKE ?  OR t.customer_account_primary_bank LIKE ?  OR t.customer_account_primary_bank_account LIKE ?  OR t.user_id LIKE ?  OR t.follow_up LIKE ?  OR t.comment LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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
            $this->customer_employment_status = $row['customer_employment_status'];
            $this->customer_employer_name = $row['customer_employer_name'];
            $this->customer_employer_office_number = $row['customer_employer_office_number'];
            $this->customer_employer_address = $row['customer_employer_address'];
            $this->customer_employer_nearest_bustop = $row['customer_employer_nearest_bustop'];
            $this->customer_employer_state = $row['customer_employer_state'];
            $this->customer_employer_city = $row['customer_employer_city'];
            $this->customer_employer_lga = $row['customer_employer_lga'];
            $this->customer_employer_country = $row['customer_employer_country'];
            $this->customer_employer_doe = $row['customer_employer_doe'];
            $this->customer_account_bvn = $row['customer_account_bvn'];
            $this->customer_account_monthly_salary = $row['customer_account_monthly_salary'];
            $this->customer_account_primary_bank = $row['customer_account_primary_bank'];
            $this->customer_account_primary_bank_account = $row['customer_account_primary_bank_account'];
            $this->user_id = $row['user_id'];
            $this->follow_up = $row['follow_up'];
            $this->comment = $row['comment'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }


    // create ha_kyc_employment_status
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET customer_employment_status=:customer_employment_status,customer_employer_name=:customer_employer_name,customer_employer_office_number=:customer_employer_office_number,customer_employer_address=:customer_employer_address,customer_employer_nearest_bustop=:customer_employer_nearest_bustop,customer_employer_state=:customer_employer_state,customer_employer_city=:customer_employer_city,customer_employer_lga=:customer_employer_lga,customer_employer_country=:customer_employer_country,customer_employer_doe=:customer_employer_doe,customer_account_bvn=:customer_account_bvn,customer_account_monthly_salary=:customer_account_monthly_salary,customer_account_primary_bank=:customer_account_primary_bank,customer_account_primary_bank_account=:customer_account_primary_bank_account,user_id=:user_id,follow_up=:follow_up,comment=:comment,created_at=:created_at,updated_at=:updated_at";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->customer_employment_status = htmlspecialchars(strip_tags($this->customer_employment_status));
        $this->customer_employer_name = htmlspecialchars(strip_tags($this->customer_employer_name));
        $this->customer_employer_office_number = htmlspecialchars(strip_tags($this->customer_employer_office_number));
        $this->customer_employer_address = htmlspecialchars(strip_tags($this->customer_employer_address));
        $this->customer_employer_nearest_bustop = htmlspecialchars(strip_tags($this->customer_employer_nearest_bustop));
        $this->customer_employer_state = htmlspecialchars(strip_tags($this->customer_employer_state));
        $this->customer_employer_city = htmlspecialchars(strip_tags($this->customer_employer_city));
        $this->customer_employer_lga = htmlspecialchars(strip_tags($this->customer_employer_lga));
        $this->customer_employer_country = htmlspecialchars(strip_tags($this->customer_employer_country));
        $this->customer_employer_doe = htmlspecialchars(strip_tags($this->customer_employer_doe));
        $this->customer_account_bvn = htmlspecialchars(strip_tags($this->customer_account_bvn));
        $this->customer_account_monthly_salary = htmlspecialchars(strip_tags($this->customer_account_monthly_salary));
        $this->customer_account_primary_bank = htmlspecialchars(strip_tags($this->customer_account_primary_bank));
        $this->customer_account_primary_bank_account = htmlspecialchars(strip_tags($this->customer_account_primary_bank_account));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->follow_up = htmlspecialchars(strip_tags($this->follow_up));
        $this->comment = htmlspecialchars(strip_tags($this->comment));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind values

        $stmt->bindParam(":customer_employment_status", $this->customer_employment_status);
        $stmt->bindParam(":customer_employer_name", $this->customer_employer_name);
        $stmt->bindParam(":customer_employer_office_number", $this->customer_employer_office_number);
        $stmt->bindParam(":customer_employer_address", $this->customer_employer_address);
        $stmt->bindParam(":customer_employer_nearest_bustop", $this->customer_employer_nearest_bustop);
        $stmt->bindParam(":customer_employer_state", $this->customer_employer_state);
        $stmt->bindParam(":customer_employer_city", $this->customer_employer_city);
        $stmt->bindParam(":customer_employer_lga", $this->customer_employer_lga);
        $stmt->bindParam(":customer_employer_country", $this->customer_employer_country);
        $stmt->bindParam(":customer_employer_doe", $this->customer_employer_doe);
        $stmt->bindParam(":customer_account_bvn", $this->customer_account_bvn);
        $stmt->bindParam(":customer_account_monthly_salary", $this->customer_account_monthly_salary);
        $stmt->bindParam(":customer_account_primary_bank", $this->customer_account_primary_bank);
        $stmt->bindParam(":customer_account_primary_bank_account", $this->customer_account_primary_bank_account);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":follow_up", $this->follow_up);
        $stmt->bindParam(":comment", $this->comment);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_kyc_employment_status
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET customer_employment_status=:customer_employment_status,customer_employer_name=:customer_employer_name,customer_employer_office_number=:customer_employer_office_number,customer_employer_address=:customer_employer_address,customer_employer_nearest_bustop=:customer_employer_nearest_bustop,customer_employer_state=:customer_employer_state,customer_employer_city=:customer_employer_city,customer_employer_lga=:customer_employer_lga,customer_employer_country=:customer_employer_country,customer_employer_doe=:customer_employer_doe,customer_account_bvn=:customer_account_bvn,customer_account_monthly_salary=:customer_account_monthly_salary,customer_account_primary_bank=:customer_account_primary_bank,customer_account_primary_bank_account=:customer_account_primary_bank_account,user_id=:user_id,follow_up=:follow_up,comment=:comment,created_at=:created_at,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->customer_employment_status = htmlspecialchars(strip_tags($this->customer_employment_status));
        $this->customer_employer_name = htmlspecialchars(strip_tags($this->customer_employer_name));
        $this->customer_employer_office_number = htmlspecialchars(strip_tags($this->customer_employer_office_number));
        $this->customer_employer_address = htmlspecialchars(strip_tags($this->customer_employer_address));
        $this->customer_employer_nearest_bustop = htmlspecialchars(strip_tags($this->customer_employer_nearest_bustop));
        $this->customer_employer_state = htmlspecialchars(strip_tags($this->customer_employer_state));
        $this->customer_employer_city = htmlspecialchars(strip_tags($this->customer_employer_city));
        $this->customer_employer_lga = htmlspecialchars(strip_tags($this->customer_employer_lga));
        $this->customer_employer_country = htmlspecialchars(strip_tags($this->customer_employer_country));
        $this->customer_employer_doe = htmlspecialchars(strip_tags($this->customer_employer_doe));
        $this->customer_account_bvn = htmlspecialchars(strip_tags($this->customer_account_bvn));
        $this->customer_account_monthly_salary = htmlspecialchars(strip_tags($this->customer_account_monthly_salary));
        $this->customer_account_primary_bank = htmlspecialchars(strip_tags($this->customer_account_primary_bank));
        $this->customer_account_primary_bank_account = htmlspecialchars(strip_tags($this->customer_account_primary_bank_account));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->follow_up = htmlspecialchars(strip_tags($this->follow_up));
        $this->comment = htmlspecialchars(strip_tags($this->comment));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":customer_employment_status", $this->customer_employment_status);
        $stmt->bindParam(":customer_employer_name", $this->customer_employer_name);
        $stmt->bindParam(":customer_employer_office_number", $this->customer_employer_office_number);
        $stmt->bindParam(":customer_employer_address", $this->customer_employer_address);
        $stmt->bindParam(":customer_employer_nearest_bustop", $this->customer_employer_nearest_bustop);
        $stmt->bindParam(":customer_employer_state", $this->customer_employer_state);
        $stmt->bindParam(":customer_employer_city", $this->customer_employer_city);
        $stmt->bindParam(":customer_employer_lga", $this->customer_employer_lga);
        $stmt->bindParam(":customer_employer_country", $this->customer_employer_country);
        $stmt->bindParam(":customer_employer_doe", $this->customer_employer_doe);
        $stmt->bindParam(":customer_account_bvn", $this->customer_account_bvn);
        $stmt->bindParam(":customer_account_monthly_salary", $this->customer_account_monthly_salary);
        $stmt->bindParam(":customer_account_primary_bank", $this->customer_account_primary_bank);
        $stmt->bindParam(":customer_account_primary_bank_account", $this->customer_account_primary_bank_account);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":follow_up", $this->follow_up);
        $stmt->bindParam(":comment", $this->comment);
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

    // delete the ha_kyc_employment_status
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
