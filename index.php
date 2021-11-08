<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Origin, Authorization');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

    require 'altorouter/AltoRouter.php';
    require_once('dataclass/data.class.php');


    $router = new AltoRouter();
    $router->setBasePath('/esm-backend-api');

    $router->map( 'GET', '/', function() {
        echo "This is the home page.";
    } );

    // Clients API
    $router->map('GET', '/client/[*:id]', 'clients', 'getClient');
    $router->map('GET', '/clients', 'clients', 'getClientsList');
    $router->map('GET', '/clients/[*:cn]', 'clients', 'getClientsByName');
    $router->map('POST', '/client', 'clients', 'newClient');
    $router->map('PUT', '/client', 'clients', 'updateClient');

    // Contracts API
    $router->map('GET', '/contracts/client/[*:id]', 'contracts', 'getContractsByClient');
    $router->map('GET', '/contract/[*:id]', 'contracts', 'getContractByID');

    // Assets API
    $router->map('GET', '/assets/contract/[*:id]', 'assets', 'getContractAssets');
    $router->map('GET', '/asset/[*:id]', 'assets', 'getAssetById');
    $router->map('POST', '/asset', 'assets', 'newAsset');
    $router->map('PUT', '/asset', 'assets', 'updateAsset');

    // Maintenance Log API
    $router->map('GET', '/asset/mainlog/[*:id]', 'mainlog', 'getAssetMainLog');


    $router->map('POST', '/test', function() {
        $data = $_POST;

        print_r($data);
    });

    $match = $router->match();

    // call closure or throw 404 status
    if( $match  ) {
        if(!is_callable( $match['target'])){
            require(__DIR__.'/controller/'.$match['target'].'.php');
            
            call_user_func_array( $match['name'], $match['params'] ); 
        }
        else {
            call_user_func_array( $match['target'], $match['params'] ); 

        }

    } else {
        // no route was matched
        // header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "Target: ".$match['target']."<br />";
        echo "Params: ".$match['params']."<br />";
        echo "Name: ".$match['name']."<br />";
        echo "Didn't find it!";

    }

?>
