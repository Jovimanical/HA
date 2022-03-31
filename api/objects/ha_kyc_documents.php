<?php

class Ha_Kyc_Documents
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_kyc_documents";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $user_id;
    public $file_name;
    public $file_url;
    public $file_status;
    public $follow_up;
    public $file_password;
    public $provider;
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

    function total_user_record_count()
    {
        $query = "SELECT COUNT(1) as total from " . $this->table_name . " t  WHERE t.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
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

    // read ha_kyc_documents
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
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.user_id LIKE ?  OR t.file_name LIKE ?  OR t.file_url LIKE ?  OR t.file_status LIKE ?  OR t.follow_up LIKE ?  OR t.file_password LIKE ?  OR t.provider LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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
            $this->user_id = $row['user_id'];
            $this->file_name = $row['file_name'];
            $this->file_url = $row['file_url'];
            $this->file_status = $row['file_status'];
            $this->follow_up = $row['follow_up'];
            $this->file_password = $row['file_password'];
            $this->provider = $row['provider'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }


    // create ha_kyc_documents
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET file_password=:file_password,user_id=:user_id,file_name=:file_name,file_url=:file_url,file_status=:file_status,follow_up=:follow_up,provider=:provider,updated_at=:updated_at";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->file_password = htmlspecialchars(strip_tags($this->file_password));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->file_url = htmlspecialchars(strip_tags($this->file_url));
        $this->file_status = htmlspecialchars(strip_tags($this->file_status));
        $this->follow_up = htmlspecialchars(strip_tags($this->follow_up));
        $this->provider = htmlspecialchars(strip_tags($this->provider));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind values

        $stmt->bindParam(":file_password", $this->file_password);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":file_name", $this->file_name);
        $stmt->bindParam(":file_url", $this->file_url);
        $stmt->bindParam(":file_status", $this->file_status);
        $stmt->bindParam(":follow_up", $this->follow_up);
        $stmt->bindParam(":provider", $this->provider);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_kyc_documents
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET user_id=:user_id,file_name=:file_name,file_url=:file_url,file_status=:file_status,follow_up=:follow_up,file_password=:file_password,provider=:provider,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->file_url = htmlspecialchars(strip_tags($this->file_url));
        $this->file_status = htmlspecialchars(strip_tags($this->file_status));
        $this->follow_up = htmlspecialchars(strip_tags($this->follow_up));
        $this->file_password = htmlspecialchars(strip_tags($this->file_password));
        $this->provider = htmlspecialchars(strip_tags($this->provider));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":file_name", $this->file_name);
        $stmt->bindParam(":file_url", $this->file_url);
        $stmt->bindParam(":file_status", $this->file_status);
        $stmt->bindParam(":follow_up", $this->follow_up);
        $stmt->bindParam(":file_password", $this->file_password);
        $stmt->bindParam(":provider", $this->provider);
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

    // delete the ha_kyc_documents
    function delete(): bool
    {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? AND user_id = ? ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->user_id);
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
