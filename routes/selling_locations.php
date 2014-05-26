<?php
    $app->get('/selling_locations/', function () use ($app) {
        setup($app);
        $result["locations"] = array(
            array(
                "id" => 1,
                "name" => "Porto",
                "coordinates"=> array("latitude"=>"0.0", "longitude"=>"0.0")
            ), array(
                "id" => 2,
                "name" => "Lisboa",
                "coordinates" => array("latitude"=>"38.76", "longitude"=>"-9.09")
            ), array(
                "id" => 3,
                "name" => "Lisboa",
                "coordinates" => array("latitude"=>"38.77", "longitude"=>"-9.09")
            ), array(
                "id" => 4,
                "name" => "Lisboa",
                "coordinates" => array("latitude"=>"38.75", "longitude"=>"-9.09")
            ));
        echo json_encode($result);
    });
    
    $app->get('/selling_locations/:id', function ($id) use ($app) {
        setup($app);
        $result["location"] = array(
            "id" => 1,
            "name" => "Porto",
            "coordinates"=> array("latitude"=>"0.0", "longitude"=>"0.0")
        );

        if($id == 2) {
            $result["location"] = array(
                "id" => 2,
                "name" => "Lisboa",
                "coordinates"=> array("latitude"=>"38.76", "longitude"=>"-9.09")
            );
        }
        echo json_encode($result);
    });
?>