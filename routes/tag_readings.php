<?php
    use Illuminate\Database\Query\Expression as raw;
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/tag_readings', 'getTagReadings');
    $app->get('/tag_readings/:productId', 'getProductTagReadings');
    $app->get('/clients/:client_id/tag_readings', 'allReadings');
    $app->get('/products/:product_id/tag_readings', 'productReadings');
    $app->get('/clients/:client_id/top_readings', 'topReadings');
    $app->get('/clients/:client_id/last_readings', 'lastReadings');
    $app->get('/clients/:client_id/top_locations', 'topLocations');

    // FUNCTIONS -->

    function allReadings($client_id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $result["error"] = false;

        $client = Client::with('products')->find($client_id);

        $products = array();

        if($client == null) {
            $result["error"] = true;
            $result["message"] = "Client not found";
        } else {
            $temp_products = $client->products()->get()->toArray();
            $products_info = array();

            foreach($temp_products as $prod) {
                $products_info[$prod["id"]] = $prod;
                array_push($products, $prod["id"]);
            }

            $group = TagReading::select('id', 'product_id', 'scan_date', 'latitude', 'longitude')->whereIn('product_id', $products)->orderBy('scan_date', 'desc')->get()->toArray();

            $display_products = array();

            foreach($group as $tag_info) {
                array_push($display_products, array("product"=>$products_info[$tag_info["product_id"]], "date"=>$tag_info["scan_date"], "tag_id"=>$tag_info["id"], "latitude"=>$tag_info["latitude"], "longitude"=>$tag_info["longitude"]));
            }

            $result["products"] = $display_products;
        }

        returnJson($result);
    }

    function topReadings($client_id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();
        $page_size = pageSize($app);
        $page = param($app, "page");

        if($page != null && $page < 1) {
            $page = 1;
        }  

        $result["error"] = false;

        $num = param($app, "num");
        $top = $num != null;

        if($top) {
            if($num == null || !is_numeric($num)) {
                $num = 5;
            } else {
                $num = intval($num);
            }
        }

        $client = Client::with('products')->find($client_id);

        $products = array();

        if($client == null) {
            $result["error"] = true;
            $result["message"] = "Client not found";
        } else {
            $temp_products = $client->products()->get()->toArray();
            $products_info = array();
            $display_products = array();

            foreach($temp_products as $prod) {
                $products_info[$prod["id"]] = $prod;
                array_push($products, $prod["id"]);
            }

            $group = TagReading::select('product_id', new raw('count(id) as num'))->whereIn('product_id', $products)->groupBy('product_id')->orderBy('num', 'desc');

            if($page != null) {
                $group = $group->take($page_size)->offset(($page - 1) * $page_size);
            }

            $group = $group->get()->toArray();

            foreach($group as $tag_info) {
                array_push($display_products, array("product"=>$products_info[$tag_info["product_id"]], "count"=>$tag_info["num"]));
            }

            $result["products"] = $display_products;
        }

        returnJson($result);
    }

    function lastReadings($client_id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();
        $page_size = pageSize($app);
        $page = param($app, "page");

        if($page != null && $page < 1) {
            $page = 1;
        }  

        $result["error"] = false;

        $client = Client::with('products')->find($client_id);

        $products = array();

        if($client == null) {
            $result["error"] = true;
            $result["message"] = "Client not found";
        } else {
            $temp_products = $client->products()->get()->toArray();
            $products_info = array();
            $display_products = array();

            foreach($temp_products as $prod) {
                $products_info[$prod["id"]] = $prod;
                array_push($products, $prod["id"]);
            }

            $group = TagReading::select('id', 'product_id', 'scan_date')->whereIn('product_id', $products)->orderBy('scan_date', 'desc');


            if($page != null) {
                $group = $group->take($page_size)->offset(($page - 1) * $page_size);
            }

            $group = $group->get()->toArray();

            foreach($group as $tag_info) {
                array_push($display_products, array("product"=>$products_info[$tag_info["product_id"]], "date"=>$tag_info["scan_date"], "tag_id"=>$tag_info["id"]));
            }

            $result["products"] = $display_products;
        }

        returnJson($result);
    }

    function topLocations($client_id) {
        setHeaders();
        $app = \Slim\Slim::getInstance();
        $page_size = pageSize($app);
        $page = param($app, "page");

        if($page != null && $page < 1) {
            $page = 1;
        }  

        $result["error"] = false;

        $client = Client::with('products')->find($client_id);

        $products = array();

        if($client == null) {
            $result["error"] = true;
            $result["message"] = "Client not found";
        } else {
            $temp_products = $client->products()->get()->toArray();
            $products_info = array();
            $display_products = array();

            foreach($temp_products as $prod) {
                $products_info[$prod["id"]] = $prod;
                array_push($products, $prod["id"]);
            }

            $group = TagReading::select('id', 'product_id', 'scan_date', 'latitude', 'longitude')->whereIn('product_id', $products)->orderBy('scan_date', 'desc');

            if($page != null) {
                $group = $group->take($page_size)->offset(($page - 1) * $page_size);
            }

            $group = $group->get()->toArray();

            foreach($group as $tag_info) {
                //$add = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$tag_info["latitude"].",".$tag_info["longitude"]), true);
                //$add = $add["results"][0]["formatted_address"];
                array_push($display_products, array("product"=>$products_info[$tag_info["product_id"]], "date"=>$tag_info["scan_date"], "tag_id"=>$tag_info["id"], "latitude"=>$tag_info["latitude"], "longitude"=>$tag_info["longitude"]/*, "Address"=>$add*/));
            }

            $result["products"] = $display_products;
        }

        returnJson($result);
    }

    function productReadings($product_id) {
        setHeaders();

        $app = \Slim\Slim::getInstance();
        $page_size = pageSize($app);
        $page = param($app, "page");

        if($page != null && $page < 1) {
            $page = 1;
        }  

        $result["error"] = false;

        $product = Product::with('tag_readings', 'tag_readings.android_user')->find($product_id);

        if($product == null) {
            $result["error"] = true;
            $result["message"] = "Client not found";
        } else {
            $c_readings = array();
            $readings = $product->tag_readings();

            if($page != null) {
                $readings = $readings->take($page_size)->offset(($page - 1) * $page_size);
            }

            $readings = $readings->get();
            
            foreach($readings as $read) {
                $tmp = $read->toArray();
                $tmp["user"] = $read->android_user()->select('id', 'email')->get()->toArray();
                $tmp["user"] = $tmp["user"][0];
                array_push($c_readings, $tmp);
            }

            $result["readings"] = $c_readings;
        }

        returnJson($result);
    }

    function getTagReadings() {
    	setHeaders();
    	$tagReadings = TagReading::all()->toArray();
    	returnJson($tagReadings);
    }

    function getProductTagReadings($productId) {
    	setHeaders();
    	$tagReadings = TagReading::where('product_id', '=', $productId)->get()->toArray();
    	returnJson($tagReadings);
    }

 ?>