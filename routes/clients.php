<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/clients', 'getClients');
    $app->get('/clients/:id', 'getClient');
    $app->post('/clients', 'createClient');
    $app->delete('/clients/:id', 'deleteClient');


    // FUNCTIONS -->

	//users access level -> 0 - base, 1 - mid, 2 - max
    function getClients() {
        setHeaders();
        $result = array();
        $result["clients"] = Client::all()->toArray();
        returnJson($result);
    }
    
    function getClient($id) {
        setHeaders();
        
        $result["error"] = false;
        $cli = Client::find($id);

        if($cli != null) {
            $result["client"] = $cli->toArray();
        } else {
            $result["error"] = true;
            $result["message"] = "Client does not exist";
        }

        returnJson($result);
    }

    function createClient() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $data = json_decode($app->request()->getBody(), true);
        $result = array();

        $result["error"] = false;

        if(isset($data['name']) && isset($data['email']) && isset($data['responsible'])) {
            $name = $data['name'];
            $found_results = Client::where('name', '=', $name)->get()->toArray();
            if(empty($found_results)) {
                
                //$email = $data['email'];
                $email = "joaopsrleal@gmail.com";
                $responsible_name = $data['responsible'];
                $password = substr(uniqid(), 0, 6);

                $cl = new Client;
                $cl->name = $name;

                $cl->save();

                $u = new User;
                $u->name = $responsible_name;
                $u->email = $email;
                $u->password = md5($password);
                $u->username = $email;
                $u->access_level = 2;
                $u->client_id = $cl->id;
                $u->save();

                mail($email, "Bulla account created", "Your Bulla account was created. You can login into it with the following credentials: \n\nEmail: ".$email."\nPassword: ".$password."\n\nEnjoy the service :)", "From: miguel.gazela@gmail.com");

                $result["user"] = $u->toArray();
                $result["client"] = $cl->toArray();
            } else {
                $result["error"] = true;
                $result["message"] = "Client with that name already exists";
            }
        }

        returnJson($result);
    }

    function deleteClient($id) {
        setHeaders();
        
        $result["error"] = false;
        $cli = Client::find($id);

        if($cli != null) {
            $cli->delete();
        } else {
            $result["error"] = true;
            $result["message"] = "Client does not exist";
        }

        returnJson($result);
    }
?>