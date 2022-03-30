<?php

class Ha_Customer_Assets
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_customer_assets";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $user_id;
    public $assetType;
    public $description;
    public $value;
    public $createdAt;
    public $updatedAt;


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

    // read ha_customer_assets
    function read()
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
        // select all query
        $query = "SELECT  t.* FROM " . $this->table_name . " t WHERE t.user_id = ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
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
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.user_id LIKE ?  OR t.assetType LIKE ?  OR t.description LIKE ?  OR t.value LIKE ?  OR t.createdAt LIKE ?  OR t.updatedAt LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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
            $this->assetType = $row['assetType'];
            $this->description = $row['description'];
            $this->value = $row['value'];
            $this->createdAt = $row['createdAt'];
            $this->updatedAt = $row['updatedAt'];
        } else {
            $this->id = null;
        }
    }


    // create ha_customer_assets
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id,assetType=:assetType,description=:description,value=:value,updatedAt=:updatedAt";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->assetType = htmlspecialchars(strip_tags($this->assetType));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->value = htmlspecialchars(strip_tags($this->value));
        $this->updatedAt = htmlspecialchars(strip_tags($this->updatedAt));

        // bind values

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":assetType", $this->assetType);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":value", $this->value);
        $stmt->bindParam(":updatedAt", $this->updatedAt);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_customer_assets
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET user_id=:user_id,assetType=:assetType,description=:description,value=:value,updatedAt=:updatedAt WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->assetType = htmlspecialchars(strip_tags($this->assetType));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->value = htmlspecialchars(strip_tags($this->value));
        $this->updatedAt = htmlspecialchars(strip_tags($this->updatedAt));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":assetType", $this->assetType);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":value", $this->value);
        $stmt->bindParam(":updatedAt", $this->updatedAt);
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

    // delete the ha_customer_assets
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
