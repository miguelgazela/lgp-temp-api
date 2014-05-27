<?php

    $app->get('/register', function () use ($app) {
        setup($app);
        
        $ret["error"] = "000";

        if(!isRequestValid($app)) {
            $ret["error"] = "001";
        } else {
            $uid = param($app, 'uid');
            $email = param($app, 'email');
            $key = param($app, 'key');

            $local_key = "5lgp1";

            if(md5($email.$uid.$local_key) != $key) {
                $ret["error"] = "002";
            } else {
                $found = AndroidUser::where('email', '=', $email)->get()->toArray();
                if (!empty($found)) {
                    $ret["error"] = "003";

                    $ret["user"] = array("id" => $found[0]["id"], "token" => $found[0]["token"]);
                } else { 
                    $token = md5(time());
                    $a_user = new AndroidUser;
                    $a_user->email = $email;
                    $a_user->uid = $uid;
                    $a_user->token = $token;
                    $a_user->token_validity = time() + 60 * 60 * 1000;
                    $a_user->save();

                    $ret["user"] = array("id" => $a_user->id, "token" => $token);
                }
            }
        }

        echo json_encode($ret);
    });

    $app->get('/register/delete', function () use ($app) {
        setup($app);
        AndroidUser::truncate();
        echo json_encode(array("error" => "000"));
    });

	// type -> 0 - visitor, 1 - user, 2 - admin, 3 - superuser
    $app->post('/account/login', function () use ($app) {
        // setup($app);

        $data = json_decode($app->request()->getBody(), true);

        $result = array();

        if($data["username"] == "user") {
            if($data["password"] == "1a1dc91c907325c69271ddf0c944bc72") { //pass1
                $result["result"] = "success";
                $result["user"] = array("id"=>1, "name"=>"John", "username"=>"user1", "type"=>"superuser");
            } else {
                $result["result"] = "error";
                $result["data"] = "wrong password";
            }
        } else if($data["username"] == "admin") {
            if($data["password"] == "1a1dc91c907325c69271ddf0c944bc72") { //pass2
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

    $app->options('/account/login', function () use ($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->headers->set('Access-Control-Allow-Origin', 'http://localhost:8000');
        $app->response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS, HEAD');
        $app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $app->response->headers->set('Access-Control-Allow-Credentials', 'application/json');
    });

    $app->post('/account/logout', function () use ($app) {
        setup($app);
        $data = json_decode($app->request()->getBody(), true);
        $result["result"] = "success";
        echo json_encode($result);
    });

    $app->get('/account/login', function () use ($app) {
    	echo "Hello, World!";
    });

?>