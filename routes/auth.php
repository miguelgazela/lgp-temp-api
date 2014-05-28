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

        if(isset($data['username']) && isset($data['password'])) {
            $username = $data['username'];
            $password = $data['password'];

            $res = User::where('username', '=', $username)->where('password', '=', $password)->get()->toArray();
            
            if(!empty($res)) {
                $user = $res[0];
                $result['result'] = "success";
                $result['user'] = $user;
            } else {
                $result['result'] = "error";
                $result['data'] = "Invalid login";
            }

        } else {
            $result['result'] = "error";
            $result['error_msg'] = "Missing required parameters";
            $result['data'] = array();
        }
        
        returnJson($result);
    }

    function logout() {
        setHeaders();
        $app = \Slim\Slim::getInstance();
        $data = json_decode($app->request()->getBody(), true);
        $result["result"] = "success";
        returnJson($result);
        session_destroy();
    }
?>