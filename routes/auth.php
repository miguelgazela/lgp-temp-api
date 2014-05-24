<?php

	// type -> 0 - visitor, 1 - user, 2 - admin, 3 - superuser
    $app->post('/account/login', function () use ($app) {
        setup($app);
        $data = json_decode($app->request()->getBody(), true);

        $result = array();

        if($data["username"] == "user1") {
            if($data["password"] == "a722c63db8ec8625af6cf71cb8c2d939") { //pass1
                $result["result"] = "success";
                $result["user"] = array("id"=>1, "name"=>"John", "username"=>"user1", "type"=>"superuser");
            } else {
                $result["result"] = "error";
                $result["data"] = "wrong password";
            }
        } else if($data["username"] == "user2") {
            if($data["password"] == "c1572d05424d0ecb2a65ec6a82aeacbf") { //pass2
                $result["result"] = "success";
                $result["user"] = array("id"=>2, "name"=>"Michael", "username"=>"user2", "type"=>"admin");
            } else {
                $result["result"] = "error";
                $result["data"] = "wrong password";
            }
        } else {
            $result["result"] = "error";
            $result["data"] = "User ".$data["username"]." does not exist";
        }
        
        echo json_encode($result);
    });

    $app->post('/account/logout', function () use ($app) {
        setup($app);
        $data = json_decode($app->request()->getBody(), true);
        $result["result"] = "success";
        echo json_encode($result);
    });

?>