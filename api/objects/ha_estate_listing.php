<?php

class Ha_Estate_Listing
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_estate_listing";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $EntityParent;
    public $LinkedEntity;
    public $PropertyFloor;
    public $PropertyId;
    public $PropertyName;
    public $PropertyAmount;
    public $PropertyJson;
    public $PropertyType;
    public $PropertyStatus;
    public $ApplicationStatus;
    public $userid;
    public $createdAt;


    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function total_record_count()
    {
        $query = "SELECT COUNT(1) as total FROM " . $this->table_name . "";
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

    /**
     * @return int|mixed
     */
    function user_total_record_count()
    {
        $query = "SELECT COUNT(1) AS total FROM " . $this->table_name . " t  WHERE t.user_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        // bind id
        $stmt->bindParam(1, $this->userid);

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

    // read ha_estate_listing
    function read()
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
        // select all query
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.userid = ? LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->userid);

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
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.EntityParent LIKE ?  OR t.LinkedEntity LIKE ?  OR t.PropertyFloor LIKE ?  OR t.PropertyId LIKE ?  OR t.PropertyName LIKE ?  OR t.PropertyAmount LIKE ?  OR t.PropertyJson LIKE ?  OR t.PropertyType LIKE ?  OR t.PropertyStatus LIKE ?  OR t.userid LIKE ?  OR t.createdAt LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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
            $this->EntityParent = $row['EntityParent'];
            $this->LinkedEntity = $row['LinkedEntity'];
            $this->PropertyFloor = $row['PropertyFloor'];
            $this->PropertyId = $row['PropertyId'];
            $this->PropertyName = $row['PropertyName'];
            $this->PropertyAmount = $row['PropertyAmount'];
            $this->PropertyJson = $row['PropertyJson'];
            $this->PropertyType = $row['PropertyType'];
            $this->PropertyStatus = $row['PropertyStatus'];
            $this->ApplicationStatus = $row['ApplicationStatus'];
            $this->userid = $row['userid'];
            $this->createdAt = $row['createdAt'];
        } else {
            $this->id = null;
        }
    }


    // create ha_estate_listing
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET EntityParent=:EntityParent,LinkedEntity=:LinkedEntity,PropertyFloor=:PropertyFloor,PropertyId=:PropertyId,PropertyName=:PropertyName,PropertyAmount=:PropertyAmount,PropertyJson=:PropertyJson,PropertyType=:PropertyType,PropertyStatus=:PropertyStatus,ApplicationStatus=:ApplicationStatus,userid=:userid";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->EntityParent = htmlspecialchars(strip_tags($this->EntityParent));
        $this->LinkedEntity = htmlspecialchars(strip_tags($this->LinkedEntity));
        $this->PropertyFloor = htmlspecialchars(strip_tags($this->PropertyFloor));
        $this->PropertyId = htmlspecialchars(strip_tags($this->PropertyId));
        $this->PropertyName = htmlspecialchars(strip_tags($this->PropertyName));
        $this->PropertyAmount = htmlspecialchars(strip_tags($this->PropertyAmount));
        $this->PropertyJson = json_encode($this->PropertyJson);
        $this->PropertyType = htmlspecialchars(strip_tags($this->PropertyType));
        $this->PropertyStatus = htmlspecialchars(strip_tags($this->PropertyStatus));
        $this->ApplicationStatus = htmlspecialchars(strip_tags($this->ApplicationStatus));
        $this->userid = htmlspecialchars(strip_tags($this->userid));

        // bind values

        $stmt->bindParam(":EntityParent", $this->EntityParent);
        $stmt->bindParam(":LinkedEntity", $this->LinkedEntity);
        $stmt->bindParam(":PropertyFloor", $this->PropertyFloor);
        $stmt->bindParam(":PropertyId", $this->PropertyId);
        $stmt->bindParam(":PropertyName", $this->PropertyName);
        $stmt->bindParam(":PropertyAmount", $this->PropertyAmount);
        $stmt->bindParam(":PropertyJson", $this->PropertyJson);
        $stmt->bindParam(":PropertyType", $this->PropertyType);
        $stmt->bindParam(":PropertyStatus", $this->PropertyStatus);
        $stmt->bindParam(":ApplicationStatus", $this->ApplicationStatus);
        $stmt->bindParam(":userid", $this->userid);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_estate_listing
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET EntityParent=:EntityParent,LinkedEntity=:LinkedEntity,PropertyFloor=:PropertyFloor,PropertyId=:PropertyId,PropertyName=:PropertyName,PropertyAmount=:PropertyAmount,PropertyJson=:PropertyJson,PropertyType=:PropertyType,PropertyStatus=:PropertyStatus,ApplicationStatus=:ApplicationStatus,userid=:userid WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->EntityParent = htmlspecialchars(strip_tags($this->EntityParent));
        $this->LinkedEntity = htmlspecialchars(strip_tags($this->LinkedEntity));
        $this->PropertyFloor = htmlspecialchars(strip_tags($this->PropertyFloor));
        $this->PropertyId = htmlspecialchars(strip_tags($this->PropertyId));
        $this->PropertyName = htmlspecialchars(strip_tags($this->PropertyName));
        $this->PropertyAmount = htmlspecialchars(strip_tags($this->PropertyAmount));
        $this->PropertyJson = json_encode($this->PropertyJson);
        $this->PropertyType = htmlspecialchars(strip_tags($this->PropertyType));
        $this->PropertyStatus = htmlspecialchars(strip_tags($this->PropertyStatus));
        $this->ApplicationStatus = htmlspecialchars(strip_tags($this->ApplicationStatus));
        $this->userid = htmlspecialchars(strip_tags($this->userid));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":EntityParent", $this->EntityParent);
        $stmt->bindParam(":LinkedEntity", $this->LinkedEntity);
        $stmt->bindParam(":PropertyFloor", $this->PropertyFloor);
        $stmt->bindParam(":PropertyId", $this->PropertyId);
        $stmt->bindParam(":PropertyName", $this->PropertyName);
        $stmt->bindParam(":PropertyAmount", $this->PropertyAmount);
        $stmt->bindParam(":PropertyJson", $this->PropertyJson);
        $stmt->bindParam(":PropertyType", $this->PropertyType);
        $stmt->bindParam(":PropertyStatus", $this->PropertyStatus);
        $stmt->bindParam(":ApplicationStatus", $this->ApplicationStatus);
        $stmt->bindParam(":userid", $this->userid);
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

    // delete the ha_estate_listing
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
