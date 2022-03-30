<?php

class Ha_Kyc_Personal_Info
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_kyc_personal_info";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $customer_firstname;
    public $customer_lastname;
    public $customer_dob;
    public $customer_gender;
    public $customer_phone_no;
    public $customer_email;
    public $pencomPin;
    public $bvn;
    public $customer_residence_type;
    public $customer_house_number;
    public $customer_house_address;
    public $customer_state;
    public $customer_city;
    public $customer_lga;
    public $customer_country;
    public $customer_stay_duration;
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
        $query = "SELECT count(1) as total from " . $this->table_name . "";
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
                $where = $where . " " . $orAnd . " LOWER(t." . $columnName . ") " . $columnLogic .
                    " :" . $columnName;
            }
        }
        $query = "SELECT count(1) as total FROM " . $this->table_name . " t  WHERE " . $where .
            "";

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

    // read ha_kyc_personal_info
    function read()
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
        // SELECT all query
        $query = "SELECT  t.* FROM " . $this->table_name . " t  LIMIT " . $offset .
            " , " . $this->no_of_records_per_page . "";

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

        // SELECT all query
        $query = "SELECT  t.* FROM " . $this->table_name .
            " t  WHERE t.id LIKE ? OR t.customer_firstname LIKE ?  OR t.customer_lastname LIKE ?  OR t.customer_dob LIKE ?  OR t.customer_gender LIKE ?  OR t.customer_phone_no LIKE ?  OR t.customer_email LIKE ?  OR t.pencomPin LIKE ?  OR t.bvn LIKE ?  OR t.customer_residence_type LIKE ?  OR t.customer_house_number LIKE ?  OR t.customer_house_address LIKE ?  OR t.customer_state LIKE ?  OR t.customer_city LIKE ?  OR t.customer_lga LIKE ?  OR t.customer_country LIKE ?  OR t.customer_stay_duration LIKE ?  OR t.user_id LIKE ?  OR t.follow_up LIKE ?  OR t.comment LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT " .
            $offset . " , " . $this->no_of_records_per_page . "";

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
                $where = $where . " " . $orAnd . " LOWER(t." . $columnName . ") " . $columnLogic .
                    " :" . $columnName;
            }
        }
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE " . $where .
            " LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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
        $query = "SELECT  t.* FROM " . $this->table_name .
            " t  WHERE t.id = ? LIMIT 0,1";

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
            $this->customer_firstname = $row['customer_firstname'];
            $this->customer_lastname = $row['customer_lastname'];
            $this->customer_dob = $row['customer_dob'];
            $this->customer_gender = $row['customer_gender'];
            $this->customer_phone_no = $row['customer_phone_no'];
            $this->customer_email = $row['customer_email'];
            $this->pencomPin = $row['pencomPin'];
            $this->bvn = $row['bvn'];
            $this->customer_residence_type = $row['customer_residence_type'];
            $this->customer_house_number = $row['customer_house_number'];
            $this->customer_house_address = $row['customer_house_address'];
            $this->customer_state = $row['customer_state'];
            $this->customer_city = $row['customer_city'];
            $this->customer_lga = $row['customer_lga'];
            $this->customer_country = $row['customer_country'];
            $this->customer_stay_duration = $row['customer_stay_duration'];
            $this->user_id = $row['user_id'];
            $this->follow_up = $row['follow_up'];
            $this->comment = $row['comment'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }


    // create ha_kyc_personal_info
    function create(): int
    {
        try {
            //$this->conn->beginTransaction();
            // query to insert record
            $query = "INSERT INTO " . $this->table_name . " SET 
                customer_firstname=:customer_firstname,
                customer_lastname=:customer_lastname,
                customer_dob=:customer_dob,
                customer_gender=:customer_gender,
                customer_phone_no=:customer_phone_no,
                customer_email=:customer_email,
                pencomPin=:pencomPin,
                bvn=:bvn,
                customer_residence_type=:customer_residence_type,
                customer_house_number=:customer_house_number,
                customer_house_address=:customer_house_address,
                customer_state=:customer_state,
                customer_city=:customer_city,
                customer_lga=:customer_lga,
                customer_country=:customer_country,
                customer_stay_duration=:customer_stay_duration,
                user_id=:user_id,
                follow_up=:follow_up,
                comment=:comment,
                updated_at=:updated_at        
        ON DUPLICATE KEY UPDATE
            customer_firstname=:customer_firstname,
            customer_lastname=:customer_lastname,
            customer_dob=:customer_dob,
            customer_gender=:customer_gender,
            customer_phone_no=:customer_phone_no,
            customer_email=:customer_email,
            customer_residence_type=:customer_residence_type,
            customer_house_number=:customer_house_number,
            customer_house_address=:customer_house_address,
            customer_state=:customer_state,
            customer_city=:customer_city,
            customer_lga=:customer_lga,
            customer_country=:customer_country,
            customer_stay_duration=:customer_stay_duration,          
            updated_at=:updated_at";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize

            $this->customer_firstname = htmlspecialchars(strip_tags($this->
            customer_firstname));
            $this->customer_lastname = htmlspecialchars(strip_tags($this->customer_lastname));
            $this->customer_dob = htmlspecialchars(strip_tags($this->customer_dob));
            $this->customer_gender = htmlspecialchars(strip_tags($this->customer_gender));
            $this->customer_phone_no = htmlspecialchars(strip_tags($this->customer_phone_no));
            $this->customer_email = htmlspecialchars(strip_tags($this->customer_email));
            $this->pencomPin = htmlspecialchars(strip_tags($this->pencomPin));
            $this->bvn = htmlspecialchars(strip_tags($this->bvn));
            $this->customer_residence_type = htmlspecialchars(strip_tags($this->
            customer_residence_type));
            $this->customer_house_number = htmlspecialchars(strip_tags($this->
            customer_house_number));
            $this->customer_house_address = htmlspecialchars(strip_tags($this->
            customer_house_address));
            $this->customer_state = htmlspecialchars(strip_tags($this->customer_state));
            $this->customer_city = htmlspecialchars(strip_tags($this->customer_city));
            $this->customer_lga = htmlspecialchars(strip_tags($this->customer_lga));
            $this->customer_country = htmlspecialchars(strip_tags($this->customer_country));
            $this->customer_stay_duration = htmlspecialchars(strip_tags($this->
            customer_stay_duration));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->follow_up = htmlspecialchars(strip_tags($this->follow_up));
            $this->comment = htmlspecialchars(strip_tags($this->comment));
            $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

            // bind values

            $stmt->bindParam(":customer_firstname", $this->customer_firstname);
            $stmt->bindParam(":customer_lastname", $this->customer_lastname);
            $stmt->bindParam(":customer_dob", $this->customer_dob);
            $stmt->bindParam(":customer_gender", $this->customer_gender);
            $stmt->bindParam(":customer_phone_no", $this->customer_phone_no);
            $stmt->bindParam(":customer_email", $this->customer_email);
            $stmt->bindParam(":pencomPin", $this->pencomPin);
            $stmt->bindParam(":bvn", $this->bvn);
            $stmt->bindParam(":customer_residence_type", $this->customer_residence_type);
            $stmt->bindParam(":customer_house_number", $this->customer_house_number);
            $stmt->bindParam(":customer_house_address", $this->customer_house_address);
            $stmt->bindParam(":customer_state", $this->customer_state);
            $stmt->bindParam(":customer_city", $this->customer_city);
            $stmt->bindParam(":customer_lga", $this->customer_lga);
            $stmt->bindParam(":customer_country", $this->customer_country);
            $stmt->bindParam(":customer_stay_duration", $this->customer_stay_duration);
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":follow_up", $this->follow_up);
            $stmt->bindParam(":comment", $this->comment);
            $stmt->bindParam(":updated_at", $this->updated_at);

            // execute query
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }

            return 0;
            //again optional if on MyIASM or DB that doesn't support transactions
            // $this->conn->commit();
        } catch (PDOException $e) {
            //optional as above:
            //$this->conn->rollback();
            return 0;
            //handle your exception here $e->getMessage() or something
        }

    }


    // update the ha_kyc_personal_info
    // update the ha_kyc_personal_info
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name .
            " SET customer_firstname=:customer_firstname,customer_lastname=:customer_lastname,customer_dob=:customer_dob,customer_gender=:customer_gender,customer_phone_no=:customer_phone_no,customer_email=:customer_email,pencomPin=:pencomPin,bvn=:bvn,customer_residence_type=:customer_residence_type,customer_house_number=:customer_house_number,customer_house_address=:customer_house_address,customer_state=:customer_state,customer_city=:customer_city,customer_lga=:customer_lga,customer_country=:customer_country,customer_stay_duration=:customer_stay_duration,user_id=:user_id,follow_up=:follow_up,comment=:comment,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->customer_firstname = htmlspecialchars(strip_tags($this->
        customer_firstname));
        $this->customer_lastname = htmlspecialchars(strip_tags($this->customer_lastname));
        $this->customer_dob = htmlspecialchars(strip_tags($this->customer_dob));
        $this->customer_gender = htmlspecialchars(strip_tags($this->customer_gender));
        $this->customer_phone_no = htmlspecialchars(strip_tags($this->customer_phone_no));
        $this->customer_email = htmlspecialchars(strip_tags($this->customer_email));
        $this->pencomPin = htmlspecialchars(strip_tags($this->pencomPin));
        $this->bvn = htmlspecialchars(strip_tags($this->bvn));
        $this->customer_residence_type = htmlspecialchars(strip_tags($this->
        customer_residence_type));
        $this->customer_house_number = htmlspecialchars(strip_tags($this->
        customer_house_number));
        $this->customer_house_address = htmlspecialchars(strip_tags($this->
        customer_house_address));
        $this->customer_state = htmlspecialchars(strip_tags($this->customer_state));
        $this->customer_city = htmlspecialchars(strip_tags($this->customer_city));
        $this->customer_lga = htmlspecialchars(strip_tags($this->customer_lga));
        $this->customer_country = htmlspecialchars(strip_tags($this->customer_country));
        $this->customer_stay_duration = htmlspecialchars(strip_tags($this->
        customer_stay_duration));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->follow_up = htmlspecialchars(strip_tags($this->follow_up));
        $this->comment = htmlspecialchars(strip_tags($this->comment));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":customer_firstname", $this->customer_firstname);
        $stmt->bindParam(":customer_lastname", $this->customer_lastname);
        $stmt->bindParam(":customer_dob", $this->customer_dob);
        $stmt->bindParam(":customer_gender", $this->customer_gender);
        $stmt->bindParam(":customer_phone_no", $this->customer_phone_no);
        $stmt->bindParam(":customer_email", $this->customer_email);
        $stmt->bindParam(":pencomPin", $this->pencomPin);
        $stmt->bindParam(":bvn", $this->bvn);
        $stmt->bindParam(":customer_residence_type", $this->customer_residence_type);
        $stmt->bindParam(":customer_house_number", $this->customer_house_number);
        $stmt->bindParam(":customer_house_address", $this->customer_house_address);
        $stmt->bindParam(":customer_state", $this->customer_state);
        $stmt->bindParam(":customer_city", $this->customer_city);
        $stmt->bindParam(":customer_lga", $this->customer_lga);
        $stmt->bindParam(":customer_country", $this->customer_country);
        $stmt->bindParam(":customer_stay_duration", $this->customer_stay_duration);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":follow_up", $this->follow_up);
        $stmt->bindParam(":comment", $this->comment);
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

    // delete the ha_kyc_personal_info
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
