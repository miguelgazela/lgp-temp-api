<?php
   $app->get('/read_tag', function () use($app) {
        setup($app);
        $ret["error"] = "000";

        if(!isRequestValid($app)) {
            $ret["error"] = "001";
        } else if(param($app, "token") != "valid") {
            $ret["error"] = "005";
        } else {
          $tag = new TagReading;
          $tag->longitude = param($app, "lon");
          $tag->latitude = param($app, "lat");
          $tag->product_id = param($app, "product");
          $tag->android_user_id = param($app, "user");
          $tag->created_at = DateTime::createFromFormat("D F d H:i:s eP Y", param($app, "date"));
          $tag->save();

          $ret["tag"] = array("id" => $tag->id);
          $ret["product"] = Product::find($tag->product_id)->toArray();
        }
        
        echo json_encode($ret);
    });

?>