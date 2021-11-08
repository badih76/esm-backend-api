<?php

    header('Access-Control-Allow-Origin: *');

    function newClient () {
        $json_str = file_get_contents('php://input');
        $json_data = json_decode($json_str);
        
        // $cn = $_POST['clName'];
        // $ce = $_POST['clEmail'];
        // $cpnam = $_POST['clContactName'];
        // $cpnum = $_POST['clContactNumber'];
        // $cadd = $_POST['clAddress'];

        $cn = $json_data->clName;
        $ce = $json_data->clEmail;
        $cpnam = $json_data->clContactName;
        $cpnum = $json_data->clContactNumber;
        $cadd = $json_data->clAddress;

        $db = new PDODataClass();

        try {
            
            $commandString = "INSERT INTO clients (clID, clName, clContactName, clContactNumber, clEmail, clAddress) "
                            ."VALUES (MD5(NOW()), :clName, :clContactName, :clContactNumber, :clEmail, :clAddress)";
            
            // print_r($data);
            $statement = $db->dbConn->prepare($commandString);
            $statement->bindParam(':clName', $cn);
            $statement->bindParam(':clContactName', $cpnam);
            $statement->bindParam(':clContactNumber', $cpnum);
            $statement->bindParam(':clEmail', $ce);
            $statement->bindParam(':clAddress', $cadd);

            $result = array();

            if($statement->execute()) {
                $response = new Response();
                $response->funcSuccess = "Ok";
                
                // return successful insert
                header('Content-type: application/json');
                print json_encode(get_object_vars($response));
                
            }
            else {
                $response = new Response();
                $response->funcSuccess = "No";
                $response->funcError = $statement->errorInfo();
                // print "Error occured. ";
                // print_r();
            }

        }
        catch (Exception $ex) {
            print "Error: ".$ex->getMessage()."<br />";
            // return error
        }

    }

    function getClient ($id) {
        $db = new PDODataClass();

        try {
            
            $commandString = "SELECT * FROM clients WHERE clID = '".$id."'";
            
            // print_r($data);
            $res = $db->dbConn->query($commandString);
            
            $Result = array();
    
            if($res) {

                while($row = $res->fetch(PDO::FETCH_ASSOC))
                {
                    array_push($Result, $row);    
                }

                $response = new Response();
                $response->funcSuccess = "Ok";
                $response->funcResult = $Result;
        
                // print_r($Result);

                // return successful insert
                header('Content-type: application/json');
                print json_encode(get_object_vars($response));
                
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

    // updateClientById
    function updateClient () {
        $json_str = file_get_contents('php://input');
        $json_data = json_decode($json_str);

        // $cid = $_POST['clID'];
        // $cn = $_POST['clName'];
        // $ce = $_POST['clEmail'];
        // $cpnam = $_POST['clContactName'];
        // $cpnum = $_POST['clContactNumber'];
        // $cadd = $_POST['clAddress'];

        $cid = $json_data->clID;
        $cn = $json_data->clName;
        $ce = $json_data->clEmail;
        $cpnam = $json_data->clContactName;
        $cpnum = $json_data->clContactNumber;
        $cadd = $json_data->clAddress;

        $db = new PDODataClass();
        try {
            
            $commandString = "UPDATE clients SET clName=:clName, clContactName=:clContactName, "
                                ."clContactNumber=:clContactNumber, clEmail=:clEmail, clAddress=:clAddress "
                                ."WHERE clID = :clID";
            
            $statement = $db->dbConn->prepare($commandString);
            $statement->bindParam(':clName', $cn);
            $statement->bindParam(':clContactName', $cpnam);
            $statement->bindParam(':clContactNumber', $cpnum);
            $statement->bindParam(':clEmail', $ce);
            $statement->bindParam(':clAddress', $cadd);
            $statement->bindParam(':clID', $cid);
            
            $result = array();

            // print $commandString."<br />";
            if($statement->execute()) {
                $response = new Response();
                $response->funcSuccess = "Ok";
                $result['affectedRows'] = $statement->rowCount();
                $response->funcResult = $result;
                
                // return successful insert
                header('Content-type: application/json');
                print json_encode(get_object_vars($response));
                
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

    // getClientsList
    function getClientsList () {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT clID, clName FROM Clients";
        
        $response = new Response();

        try {
            $res = $db->dbConn->query($commandString);
            
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
        catch(Exception $ex)
        {
            $response->funcSuccess = "Error";
            $response->funcResult = "";
            $response->funcError = $ex->getMessage();
        }

    }

    // getClientDocuments by Name
    function getClientsByName ($cn) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT * FROM Clients WHERE clName LIKE '%".$cn."%'";
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
?>