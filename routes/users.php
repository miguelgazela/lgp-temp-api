<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->
    $app->post('/users', 'createUser');
    $app->put('/users/:id', 'changeUser');
    $app->delete('/users/:id', 'deleteUser');

    // FUNCTIONS -->
    function createUser() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;

        if(isset($data['name']) && isset($data['email']) && isset($data['access_level']) && isset($data['client_id'])) {
          $name = $data['name'];
          $email = $data['email'];
          $access_level = $data['access_level'];
          $client_id = $data['client_id'];
          $password = substr(uniqid(), 0, 6);

          $user = new User;
          $user->name = $name;
          $user->username = $email;
          $user->password = md5($password);
          $user->email = $email;
          $user->access_level = $access_level;
          $user->client_id = $client_id;
          $user->save();

          mail($email, "Bulla account created", "Your Bulla account was created. You can login into it with the following credentials: \n\nEmail: ".$email."\nPassword: ".$password."\n\nEnjoy the service :)");


          $result["user"] = $user->toArray();
        } else {
            $result["error"] = true;
            $result["message"] = "User does not exist";
        }

        returnJson($result);
    }

    function changeUser($id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;
        $user = User::find($id);

        if($user != null) {
            $access_level = $data["access_level"];

            if($access_level != null) {
                $user->access_level = $access_level;
            }

            $user->save();
            $result["user"] = $user->toArray();
        } else {
            $result["error"] = true;
            $result["message"] = "User does not exist";
        }

        returnJson($result);
    }

    function deleteUser($id) {
        setHeaders();
        
        $result["error"] = false;
        $user = User::find($id);

        if($user != null) {
            $user->delete();
        } else {
            $result["error"] = true;
            $result["message"] = "Client does not exist";
        }

        returnJson($result);
    }
?>