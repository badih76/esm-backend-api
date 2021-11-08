<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Origin, Authorization');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
 
    // getContractsByClient
    function getContractsByClient ($clID) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT contID, contDescription FROM contracts WHERE clID = '".$clID."'";
        $res = $db->dbConn->query($commandString);
        
        $response = new Response();
        $Result = array();

        if($res) {    
            while($row = $res->fetch(PDO::FETCH_ASSOC))
            {
                array_push($Result, $row);    
            }

            $response->funcSuccess = "Ok";
            $response->funcResult = $Result;

        }
        else {
            $response->funcSuccess = "Error";
            $response->funcResult = "";
            $response->funcError = $db->errorInfo();
        }

        header('Content-type: application/json');
        print json_encode(get_object_vars($response));

    }

    // getClientDocument by ID
    function getContractByID ($id) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT * FROM contracts WHERE contID = '".$id."' LIMIT 1";
        $res = $db->dbConn->query($commandString);

        // print $commandString."<br />";
        $response = new Response();
        $Result = null;

        if($res) {
            while($row = $res->fetch(PDO::FETCH_ASSOC))
            {
                $Result = $row; 
            }

            $response->funcSuccess = "Ok";
            $response->funcResult = $Result;
        }
        else {
            $response->funcSuccess = "Error";
            $response->funcResult = "";
            $response->funcError = $db->errorInfo();
        }

        header('Content-type: application/json');
        print json_encode(get_object_vars($response));

    }

    // addClient
    function newContract () {
        // prepare the parameters...
        $db = new PDODataClass();

        $data = $_POST;
        try {
            
            $commandString = "INSERT INTO contracts "
                            ."(contID, contDescription, contactEmail, contactPerson, "
                            ."effectiveDate, expiryDate, clID) "
                            ."VALUES "
                            ."(MD5(NOW()), :contDescription, :contactEmail, :contactPerson, "
                            .":effectiveDate, :expiryDate, :clID)";
            
            // print_r($data);
            $statement = $db->dbConn->prepare($commandString);
            $statement->bindParam(':contDescription', $data["contDescription"]);
            $statement->bindParam(':contactEmail', $data["contactEmail"]);
            $statement->bindParam(':contactPerson', $data["contactPerson"]);
            $statement->bindParam(':effeciveDate', $data["effeciveDate"]);
            $statement->bindParam(':expiryDate', $data["expiryDate"]);
            $statement->bindParam(':clID', $data["clID"]);

            $result = array();

            if($statement->execute($data)) {
                $response = new Response();
                $response->funcSuccess = "Ok";
                
                // return successful insert
                header('Content-type: application/json');
                print "Resonse: ".json_encode(get_object_vars($response));
                
            }
            else {
                print "Error occured. ";
                print_r($statement->errorInfo());
            }

        }
        catch (Exception $ex) {
            print "Error: ".$ex->getMessage()."<br />";
            // return error
        }

    }
    
?>