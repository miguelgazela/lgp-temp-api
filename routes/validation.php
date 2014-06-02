<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/read_tag', 'addTagReading');


    // FUNCTIONS -->

    function addTagReading() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $ret["error"] = "000";

        if(!isRequestValid($app)) {
            $ret["error"] = "001";
        } else {
            $tag = new TagReading;
            $tag->longitude = param($app, "lon");
            $tag->latitude = param($app, "lat");
            $tag->product_id = param($app, "product");
            $tag->android_user_id = param($app, "user");
            $date = str_replace('*', '+', param($app, "created_at"));
            $tag->scan_date = $date;

            //DateTime::createFromFormat(DateTime::W3C, $date);
            
            if(param($app, "token") != "valid") {
                $ret["error"] = "005";
                $ret["message"] = "Invalid token";
                $tag->authentic = 0;
            }

            $tag->save();

            $ret["tag"] = array("id" => $tag->id);
            $ret["product"] = Product::find($tag->product_id)->toArray();
        }

        returnJson($ret);
    }
?>