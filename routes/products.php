<?php
	use Illuminate\Database\Eloquent\ModelNotFoundException;

	// ROUTES -->

	$app->get('/products', 'getProducts');
	$app->post('/products', 'addProduct');
	$app->get('/products/:id', 'getProduct');
	$app->get('/list_products', 'getListProducts');


	// FUNCTIONS -->

	function getProducts() {
		setHeaders();
		$products = Product::all()->toArray();
		returnJson($products);
	}

	function addProduct() {
		setHeaders();
		$result = array();
		
		$app = \Slim\Slim::getInstance();
		$data = json_decode($app->request()->getBody(), true);

		try {
			$product = new Product;
			$product->name = $data['productName'];
			$product->description = $data['productDescription'];
			$product->image_path = $data['path'];
			$product->category_id = 3; // todo fix this
			$product->client_id = $data['client_id'];
			$product->save();

			$result['result'] = "success";
			$result['product_id'] = $product['id'];
		} catch (Exception $e) {
			$result['result'] = "exception";
		}
		
		returnJson($result);
	}

	function getProduct($id) {
		setHeaders();
		try {
			$product = Product::findOrFail($id)->toArray();
			returnJson($product);
		} catch(ModelNotFoundException $e) {
			returnJson(array("error" => "404"));	// what's the default value to return ?
		}
	}

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
?>