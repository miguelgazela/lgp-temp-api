<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/backoffices', 'getBackoffices');
    $app->get('/backoffices/:id', 'getBackoffice');


    // FUNCTIONS -->

	//users access level -> 0 - base, 1 - mid, 2 - max
    function getBackoffices() {
        setHeaders();
        $result["backoffices"] = array(
            array(
                "id" => 1,
                "name" => "Gucci",
                "users" => array(array("id" => 1, "name" => "John", "access" => 0), array("id" => 2, "name" => "Michael", "access" => 1))
                ),
            array(
                "id" => 2,
                "name" => "Versace",
                "users" => array(array("id" => 1, "name" => "John", "access" => 0))
                )
            );
        returnJson($result);
    }
    
    function getBackoffice($id) {
        setHeaders();
        $result["backoffice"] = array(
            "id" => 1,
            "name" => "Gucci",
            "users" => array(array("id" => 1, "name" => "John", "access" => 0), array("id" => 2, "name" => "Michael", "access" => 1))
        );

        if($id == 2) {
            $result["backoffice"] = array(
                "id" => 2,
                "name" => "Versace",
                "users" => array(array("id" => 1, "name" => "John", "access" => 0))
            );
        }
        returnJson($result);
    }

?>