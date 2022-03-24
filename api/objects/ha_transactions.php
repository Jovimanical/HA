<?php

class Ha_Transactions
{

    // database connection and table name
    private $conn;
    private $table_name = "ha_transactions";
    public $pageNo = 1;
    public $no_of_records_per_page = 30;
    // object properties

    public $id;
    public $sender_id;
    public $receiver_id;
    public $amount;
    public $charge;
    public $post_balance;
    public $transaction_type;
    public $sender_Account;
    public $receiver_Account;
    public $trx;
    public $details;
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

    // read ha_transactions
    function read()
    {
        if (isset($_GET["pageNo"])) {
            $this->pageNo = $_GET["pageNo"];
        }
        $offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
        // select all query
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.receiver_id = ? OR t.sender_id = ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->sender_id);
        $stmt->bindParam(2, $this->sender_id);

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
        $query = "SELECT  t.* FROM " . $this->table_name . " t  WHERE t.id LIKE ? OR t.sender_id LIKE ?  OR t.receiver_id LIKE ?  OR t.amount LIKE ?  OR t.charge LIKE ?  OR t.post_balance LIKE ?  OR t.transaction_type LIKE ?  OR t.sender_Account LIKE ?  OR t.receiver_Account LIKE ?  OR t.trx LIKE ?  OR t.details LIKE ?  OR t.created_at LIKE ?  OR t.updated_at LIKE ?  LIMIT " . $offset . " , " . $this->no_of_records_per_page . "";

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
            $this->sender_id = $row['sender_id'];
            $this->receiver_id = $row['receiver_id'];
            $this->amount = $row['amount'];
            $this->charge = $row['charge'];
            $this->post_balance = $row['post_balance'];
            $this->transaction_type = $row['transaction_type'];
            $this->sender_Account = $row['sender_Account'];
            $this->receiver_Account = $row['receiver_Account'];
            $this->trx = $row['trx'];
            $this->details = $row['details'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        } else {
            $this->id = null;
        }
    }


    // create ha_transactions
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET sender_id=:sender_id,receiver_id=:receiver_id,amount=:amount,charge=:charge,post_balance=:post_balance,transaction_type=:transaction_type,sender_Account=:sender_Account,receiver_Account=:receiver_Account,trx=:trx,details=:details,updated_at=:updated_at";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->sender_id = htmlspecialchars(strip_tags($this->sender_id));
        $this->receiver_id = htmlspecialchars(strip_tags($this->receiver_id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->charge = htmlspecialchars(strip_tags($this->charge));
        $this->post_balance = htmlspecialchars(strip_tags($this->post_balance));
        $this->transaction_type = htmlspecialchars(strip_tags($this->transaction_type));
        $this->sender_Account = htmlspecialchars(strip_tags($this->sender_Account));
        $this->receiver_Account = htmlspecialchars(strip_tags($this->receiver_Account));
        $this->trx = htmlspecialchars(strip_tags($this->trx));
        $this->details = htmlspecialchars(strip_tags($this->details));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind values

        $stmt->bindParam(":sender_id", $this->sender_id);
        $stmt->bindParam(":receiver_id", $this->receiver_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":charge", $this->charge);
        $stmt->bindParam(":post_balance", $this->post_balance);
        $stmt->bindParam(":transaction_type", $this->transaction_type);
        $stmt->bindParam(":sender_Account", $this->sender_Account);
        $stmt->bindParam(":receiver_Account", $this->receiver_Account);
        $stmt->bindParam(":trx", $this->trx);
        $stmt->bindParam(":details", $this->details);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return 0;

    }


    // update the ha_transactions
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " SET sender_id=:sender_id,receiver_id=:receiver_id,amount=:amount,charge=:charge,post_balance=:post_balance,transaction_type=:transaction_type,sender_Account=:sender_Account,receiver_Account=:receiver_Account,trx=:trx,details=:details,updated_at=:updated_at WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->sender_id = htmlspecialchars(strip_tags($this->sender_id));
        $this->receiver_id = htmlspecialchars(strip_tags($this->receiver_id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->charge = htmlspecialchars(strip_tags($this->charge));
        $this->post_balance = htmlspecialchars(strip_tags($this->post_balance));
        $this->transaction_type = htmlspecialchars(strip_tags($this->transaction_type));
        $this->sender_Account = htmlspecialchars(strip_tags($this->sender_Account));
        $this->receiver_Account = htmlspecialchars(strip_tags($this->receiver_Account));
        $this->trx = htmlspecialchars(strip_tags($this->trx));
        $this->details = htmlspecialchars(strip_tags($this->details));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values

        $stmt->bindParam(":sender_id", $this->sender_id);
        $stmt->bindParam(":receiver_id", $this->receiver_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":charge", $this->charge);
        $stmt->bindParam(":post_balance", $this->post_balance);
        $stmt->bindParam(":transaction_type", $this->transaction_type);
        $stmt->bindParam(":sender_Account", $this->sender_Account);
        $stmt->bindParam(":receiver_Account", $this->receiver_Account);
        $stmt->bindParam(":trx", $this->trx);
        $stmt->bindParam(":details", $this->details);
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

    // delete the ha_transactions
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
