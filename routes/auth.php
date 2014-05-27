<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/register', 'addRegister');              // change this to POST
    $app->get('/register/delete', 'deleteRegister');    // change this to DELETE
    $app->post('/account/login', 'login');
    $app->post('/account/logout', 'logout');


    // FUNCTIONS -->

    function addRegister() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

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

        returnJson($ret);
    }

    function deleteRegister() {
        setHeaders();
        AndroidUser::truncate();
        returnJson(array("error" => "000"));
    }

	// type -> 0 - visitor, 1 - user, 2 - admin, 3 - superuser
    function login() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

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
        
        returnJson($result);
    }

    // $app->options('/account/login', function () use ($app) {
    //     setHeadersApp($app);
    // });

    function logout() {
        setHeaders();
        $app = \Slim\Slim::getInstance();
        $data = json_decode($app->request()->getBody(), true);
        $result["result"] = "success";
        returnJson($result);
    }
?>