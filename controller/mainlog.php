<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Origin, Authorization');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
 
    // getContractsAssets
    function getAssetMainLog ($aid) {
        // prepare the parameters...
        $db = new PDODataClass();

        $commandString = "SELECT * FROM mainlog WHERE assetId = '".$aid."'";
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