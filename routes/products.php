<?php
	use Illuminate\Database\Eloquent\ModelNotFoundException;

	// ROUTES -->

	$app->get('/products', 'getProducts');
	$app->get('/products/:id', 'getProduct');
	$app->get('/list_products', 'getListProducts');


	// FUNCTIONS -->

	function getListProducts() {
		setHeaders();

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
	    
	    returnJson($ret);
	}

	function getProducts() {
		$products = Product::all()->toArray();
		returnJson($products);
	}

	function getProduct($id) {
		try {
			$product = Product::findOrFail($id)->toArray();
			returnJson($product);
		} catch(ModelNotFoundException $e) {
			returnJson(array("error" => "404"));	// what's the default value to return ?
		}
	}
?>