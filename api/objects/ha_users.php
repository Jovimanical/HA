<?php

class Ha_Users
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_users";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $country_code;
    public $mobile;
    public $password;
    public $profileImage;
    public $address;
    public $status;
    public $email_verified;
    public $sms_verified;
    public $verification_code;
    public $verification_code_send_at;
    public $two_factor_status;
    public $two_factor_verified;
    public $roles;
    public $remember_token;
    public $created_at;
    public $updated_at;


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
        $query = "SELECT COUNT(1) as total FROM " . $this->table_name . " t  WHERE " . $where . "";

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

    // read ha_users
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
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.firstname LIKE ?  OR t.lastname LIKE ?  OR t.username LIKE ?  OR t.email LIKE ?  OR t.country_code LIKE ?  OR t.mobile LIKE ?  OR t.password LIKE ?  OR t.profileImage LIKE ?  OR t.address LIKE ?  OR t.status LIKE ?  OR t.email_verified LIKE ?  OR t.sms_verified LIKE ?  OR t.verification_code LIKE ?  OR t.verification_code_send_at LIKE ?  OR t.two_factor_status LIKE ?  OR t.two_factor_verified LIKE ?  OR t.roles LIKE ?  OR t.remember_token LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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

    /**
     * @function login_validation
     */
    function login_validation()
    {
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.email = ?  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0 && password_verify($this->password, $row['password'])) {

            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->country_code = $row['country_code'];
            $this->mobile = $row['mobile'];
            $this->profileImage = $row['profileImage'];
            $this->address = $row['address'];
            $this->status = $row['status'];
            $this->roles = $row['roles'];
            $this->email_verified = $row['email_verified'];
            $this->sms_verified = $row['sms_verified'];
            $this->two_factor_status = $row['two_factor_status'];
            $this->remember_token = $row['remember_token'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }

    function emailExits(): bool
    {
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.email = ?  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email, PDO::PARAM_STR);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    function isValidVerificationToken(): bool
    {
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.verification_code = ? AND t.email = ?  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->verification_code, PDO::PARAM_STR);
        $stmt->bindParam(2, $this->email, PDO::PARAM_STR);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    function resendUserVerificationToken()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET verification_code=:verification_code,verification_code_send_at=:verification_code_send_at WHERE email = :email";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->verification_code = htmlspecialchars(strip_tags($this->verification_code));
        $this->verification_code_send_at = htmlspecialchars(strip_tags($this->verification_code_send_at));
        // bind new values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":verification_code", $this->verification_code);
        $stmt->bindParam(":verification_code_send_at", $this->verification_code_send_at);

        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }



    function passwordVerification(): bool
    {
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id = ?  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0 && password_verify($this->password, $row['password'])) {
            return true;
        } else {
            return false;
        }
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
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->country_code = $row['country_code'];
            $this->mobile = $row['mobile'];
            $this->password = $row['password'];
            $this->profileImage = $row['profileImage'];
            $this->address = $row['address'];
            $this->status = $row['status'];
            $this->email_verified = $row['email_verified'];
            $this->sms_verified = $row['sms_verified'];
            $this->verification_code = $row['verification_code'];
            $this->verification_code_send_at = $row['verification_code_send_at'];
            $this->two_factor_status = $row['two_factor_status'];
            $this->two_factor_verified = $row['two_factor_verified'];
            $this->roles = $row['roles'];
            $this->remember_token = $row['remember_token'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }

    function readProfileOne()
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
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->country_code = $row['country_code'];
            $this->mobile = $row['mobile'];
            $this->password = $row['password'];
            $this->profileImage = $row['profileImage'];
            $this->address = $row['address'];
            $this->status = $row['status'];
            $this->email_verified = $row['email_verified'];
            $this->sms_verified = $row['sms_verified'];
            $this->verification_code = $row['verification_code'];
            $this->verification_code_send_at = $row['verification_code_send_at'];
            $this->two_factor_status = $row['two_factor_status'];
            $this->two_factor_verified = $row['two_factor_verified'];
            $this->roles = $row['roles'];
            $this->remember_token = $row['remember_token'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }


    // create ha_users
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET firstname=:firstname,lastname=:lastname,username=:username,email=:email,country_code=:country_code,mobile=:mobile,password=:password,profileImage=:profileImage,address=:address,status=:status,email_verified=:email_verified,sms_verified=:sms_verified,verification_code=:verification_code,verification_code_send_at=:verification_code_send_at,two_factor_status=:two_factor_status,two_factor_verified=:two_factor_verified,roles=:roles,remember_token=:remember_token,created_at=:created_at,updated_at=:updated_at";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->country_code = htmlspecialchars(strip_tags($this->country_code));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->profileImage = htmlspecialchars(strip_tags($this->profileImage));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->email_verified = htmlspecialchars(strip_tags($this->email_verified));
        $this->sms_verified = htmlspecialchars(strip_tags($this->sms_verified));
        $this->verification_code = htmlspecialchars(strip_tags($this->verification_code));
        $this->verification_code_send_at = htmlspecialchars(strip_tags($this->verification_code_send_at));
        $this->two_factor_status = htmlspecialchars(strip_tags($this->two_factor_status));
        $this->two_factor_verified = htmlspecialchars(strip_tags($this->two_factor_verified));
        $this->roles = htmlspecialchars(strip_tags($this->roles));
        $this->remember_token = htmlspecialchars(strip_tags($this->remember_token));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind values

        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":country_code", $this->country_code);
        $stmt->bindParam(":mobile", $this->mobile);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":profileImage", $this->profileImage);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":email_verified", $this->email_verified);
        $stmt->bindParam(":sms_verified", $this->sms_verified);
        $stmt->bindParam(":verification_code", $this->verification_code);
        $stmt->bindParam(":verification_code_send_at", $this->verification_code_send_at);
        $stmt->bindParam(":two_factor_status", $this->two_factor_status);
        $stmt->bindParam(":two_factor_verified", $this->two_factor_verified);
        $stmt->bindParam(":roles", $this->roles);
        $stmt->bindParam(":remember_token", $this->remember_token);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_users
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET firstname=:firstname,lastname=:lastname,username=:username,email=:email,country_code=:country_code,mobile=:mobile,password=:password,profileImage=:profileImage,address=:address,status=:status,email_verified=:email_verified,sms_verified=:sms_verified,verification_code=:verification_code,verification_code_send_at=:verification_code_send_at,two_factor_status=:two_factor_status,two_factor_verified=:two_factor_verified,roles=:roles,remember_token=:remember_token,created_at=:created_at,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->country_code = htmlspecialchars(strip_tags($this->country_code));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->profileImage = htmlspecialchars(strip_tags($this->profileImage));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->email_verified = htmlspecialchars(strip_tags($this->email_verified));
        $this->sms_verified = htmlspecialchars(strip_tags($this->sms_verified));
        $this->verification_code = htmlspecialchars(strip_tags($this->verification_code));
        $this->verification_code_send_at = htmlspecialchars(strip_tags($this->verification_code_send_at));
        $this->two_factor_status = htmlspecialchars(strip_tags($this->two_factor_status));
        $this->two_factor_verified = htmlspecialchars(strip_tags($this->two_factor_verified));
        $this->roles = htmlspecialchars(strip_tags($this->roles));
        $this->remember_token = htmlspecialchars(strip_tags($this->remember_token));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":country_code", $this->country_code);
        $stmt->bindParam(":mobile", $this->mobile);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":profileImage", $this->profileImage);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":email_verified", $this->email_verified);
        $stmt->bindParam(":sms_verified", $this->sms_verified);
        $stmt->bindParam(":verification_code", $this->verification_code);
        $stmt->bindParam(":verification_code_send_at", $this->verification_code_send_at);
        $stmt->bindParam(":two_factor_status", $this->two_factor_status);
        $stmt->bindParam(":two_factor_verified", $this->two_factor_verified);
        $stmt->bindParam(":roles", $this->roles);
        $stmt->bindParam(":remember_token", $this->remember_token);
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

    function activateUser(): bool
    {
        // update query
        $query = "UPDATE " . $this->table_name . " SET email_verified=:email_verified WHERE email = :email";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->email_verified = htmlspecialchars(strip_tags($this->email_verified));

        // bind new values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":email_verified", $this->email_verified);


        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    function updateProfile(): bool
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET firstname=:firstname,lastname=:lastname,email=:email,mobile=:mobile,profileImage=:profileImage,address=:address,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->profileImage = htmlspecialchars(strip_tags($this->profileImage));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mobile", $this->mobile);
        $stmt->bindParam(":profileImage", $this->profileImage);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    function changeUserPassword(): bool
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET password=:password WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));
        // bind new values
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    function resetUserPassword(): bool
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET password=:password WHERE email = :email";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        // bind new values
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);

        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @function Update_patch
     * @param $jsonObj
     * @return bool
     */
    function update_patch($jsonObj): bool
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

    // delete the ha_users
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

    function CheckEmailExist($email): bool
    {
        $query = "SELECT *  FROM " . $this->table_name . " WHERE `email` = ? ";

        // prepare query
        $stmt = $this->conn->prepare($query);
        // bind id of record to delete
        $stmt->bindParam(1, $email);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    function CheckMobileExist($mobile): bool
    {
        $query = "SELECT *  FROM " . $this->table_name . " WHERE `mobile` = ? ";

        // prepare query
        $stmt = $this->conn->prepare($query);
        // bind id of record to delete
        $stmt->bindParam(1, $mobile);
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
