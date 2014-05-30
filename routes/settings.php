<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->put('/settings/:id/name', 'changeName');
    $app->put('/settings/:id/password', 'changePassword');


    // FUNCTIONS -->

    function changeName($id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;
        $user = User::find($id);

        if($user != null) {
            $name = $data["name"];

            if($name != null) {
                $user->name = $name;
            }

            $user->save();
        } else {
            $result["error"] = true;
            $result["message"] = "User does not exist";
        }

        returnJson($result);
    }

    function changePassword($id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;
        $user = User::find($id);

        if($user != null) {
          $password = $data["old_password"];
          if($user->password == $password) {
            $n_password = $data["new_password"];

            if($n_password == $data["confirmation_password"]) {
                $user->password = $n_password;
                $user->save();
            } else {
              $result["error"] = true;
              $result["message"] = "New password and confirmation password do not match";
            }
          } else {
            $result["error"] = true;
            $result["message"] = "Invalid password for user";
          }
        } else {
            $result["error"] = true;
            $result["message"] = "User does not exist";
        }

        returnJson($result);
    }
?>