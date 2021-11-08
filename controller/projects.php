<?php
    header('Access-Control-Allow-Origin: *');
 
    // getProjectsList
    function getProjectsList() {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT prjCode, prjName FROM projects";
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
            $response->funcError = $db->dbConn->errorInfo();
        }

        print json_encode(get_object_vars($response));

    }

    // getClientDocuments by Name
    function getProjectsByName ($a) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT * FROM projects WHERE prjName LIKE '%".$a['name']."%'";
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
            $response->funcError = $db->dbConn->errorInfo();
        }
        print json_encode(get_object_vars($response));

    }

    // getClientDocument by ID
    function getProjectByID ($a) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT * FROM projects WHERE prjCode = '".$a['id']."' LIMIT 1";
        $res = $db->dbConn->query($commandString);

        // print $commandString."<br />";
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
            $response->funcError = $db->dbConn->errorInfo();
        }
        print json_encode(get_object_vars($response));

    }

    // addProject
    function addProject ($a, $data) {
        // prepare the parameters...
        $db = new PDODataClass();

        $data = $data["data"];
        try {
            
            $commandString = "INSERT INTO projects (prjCode, prjName, prjDescription, contCode) "
                            ."VALUES (MD5(NOW()), :prjName, :prjDescription, :contCode)";
            
            print_r($data);
            $statement = $db->dbConn->prepare($commandString);
            // $statement->bindParam(':clName', $data["clName"]);
            // $statement->bindParam(':clContactName', $data["clContactName"]);
            // $statement->bindParam(':clContactNumber', $data["clContactNumber"]);
            // $statement->bindParam(':clEmail', $data["clEmail"]);
            // $statement->bindParam(':clAddress', $data["clAddress"]);

            $result = array();

            if($statement->execute($data)) {
                $response = new Response();
                $response->funcSuccess = "Ok";
                
                // return successful insert
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

    // updateProjectByID
    function ($a, $data) {
        // prepare the parameters...
        $db = new PDODataClass();

        $data = $data["data"];
        try {
            
            $commandString = "UPDATE projects SET prjName=:prjName, "
                            ."prjDescription=:prjDescription, contCode=:contCode WHERE prjCode=:prjCode";
            
            $statement = $db->dbConn->prepare($commandString);
            // $statement->bindParam(':clName', $data["clName"]);
            // $statement->bindParam(':clContactName', $data["clContactName"]);
            // $statement->bindParam(':clContactNumber', $data["clContactNumber"]);
            // $statement->bindParam(':clEmail', $data["clEmail"]);
            // $statement->bindParam(':clAddress', $data["clAddress"]);
            // $statement->bindParam(':clID', $data["clID"]);
            
            $result = array();

            // print $commandString."<br />";
            if($statement->execute($data)) {
                $response = new Response();
                $response->funcSuccess = "Ok";
                $result['affectedRows'] = $statement->rowCount();
                $response->funcResult = $result;
                // return successful insert
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
    
?>