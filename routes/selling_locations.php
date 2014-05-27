<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/selling_locations', 'getSellingLocations');
    $app->get('/selling_locations/:id', 'getSellingLocation');


    // FUNCTIONS -->

    function getSellingLocations() {
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
        returnJson($result);
    }
    
    function getSellingLocation($id) {
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
        returnJson($result);
    }
?>