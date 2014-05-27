<?php

	$app->get('/list_products', function() use ($app) {
		setup($app);

		$page_size = 10;

		$ret["error"] = "000";

    if(!isRequestValid($app)) {
      $ret["error"] = "001";
    } else {
    	$page = param($app, "page");
    	$category = param($app, "category");

    	if($page == null && $category == null) {
    		$ret["products"] = Product::all()->toArray();
    	} else if($page != null && $category == null) {
    		$ret["products"] = Product::take($page_size)->offset(($page - 1) * $page_size)->get()->toArray();
    	} else if($page == null && $category != null) {
    		$ret["products"] = Product::where('category_id', '=', $category)->get()->toArray();
    	} else {
    		$ret["products"] = Product::where('category_id', '=', $category)->take($page_size)->offset(($page - 1) * $page_size)->get()->toArray();
    	}
    }

		echo json_encode($ret);
	});

	$app->get('/products/:id', function ($id) use ($app) {
		$product = Product::get($id);
		returnJson($product);
	});

	$app->get('/products', function () use ($app) {
		setup($app);
		$products = Product::all();
		returnJson($products);
	});

?>