<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Origin, Authorization');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
 
    // getContractsAssets
    function getContractAssets ($contId) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT assetID, title FROM assets WHERE contID = '".$contId."'";
        $res = $db->dbConn->query($commandString);
        
        $response = new Response();
        $Result = array();
        
        $response->funcDebug = "SELECT assetID, title FROM assets WHERE contID = '".$contId."'";

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
    
    function getAssetById ($aid) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT * FROM assets WHERE assetID = '".$aid."'";
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

        header('Content-type: application/json');
        print json_encode(get_object_vars($response));

    }

    function newAsset () {
        $json_str = file_get_contents('php://input');
        $json_data = json_decode($json_str);
        
        // contID;
        // var ttl = asset.Title;
        // var cnt = asset.Count;
        // var adesc = asset.Description;
        // var remarks = asset.Remarks;
        // var location = asset.Location;

        $contID = $json_data->contID;
        $ttl = $json_data->asset->Title;
        $cnt = $json_data->asset->Count;
        $adesc = $json_data->asset->Description;
        $remarks = $json_data->asset->Remarks;
        $location = $json_data->asset->Location;

        $db = new PDODataClass();

        try {
            
            $commandString = "INSERT INTO assets (assetID, contID, Title, [count], [Description], Location, Remarks) "
                            ."VALUES (MD5(NOW()), :contID, :ttl, :cnt, :adesc, :remarks, :location)";
            
            // print_r($data);
            $statement = $db->dbConn->prepare($commandString);
            $statement->bindParam(':contID', $contID);
            $statement->bindParam(':ttl', $ttl);
            $statement->bindParam(':cnt', $cnt);
            $statement->bindParam(':adesc', $adesc);
            $statement->bindParam(':remarks', $remarks);
            $statement->bindParam(':location', $location);

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

    function updateAsset () {
        $json_str = file_get_contents('php://input');
        $json_data = json_decode($json_str);
        
        // contID;
        // var ttl = asset.Title;
        // var cnt = asset.Count;
        // var adesc = asset.Description;
        // var remarks = asset.Remarks;
        // var location = asset.Location;

        $contID = $json_data->contID;
        $assetID = $json_data->asset->ID;
        $ttl = $json_data->asset->Title;
        $cnt = $json_data->asset->Count;
        $adesc = $json_data->asset->Description;
        $remarks = $json_data->asset->Remarks;
        $location = $json_data->asset->Location;

        $db = new PDODataClass();

        try {
            
            $commandString = "UPDATE assets "
                            ."SET Title = ':ttl', [count] = :cnt, "
                            ."[Description] = ':adesc', Location = ':location', "
                            ."Remarks = ':remarks'"
                            ."WHERE assetID = ':assetID' and contID = ':contID'";
            
            // print_r($data);
            $statement = $db->dbConn->prepare($commandString);
            $statement->bindParam(':assetID', $assetID);
            $statement->bindParam(':contID', $contID);
            $statement->bindParam(':ttl', $ttl);
            $statement->bindParam(':cnt', $cnt);
            $statement->bindParam(':adesc', $adesc);
            $statement->bindParam(':remarks', $remarks);
            $statement->bindParam(':location', $location);

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
?>