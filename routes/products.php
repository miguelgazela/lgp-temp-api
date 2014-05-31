<?php
	use Illuminate\Database\Eloquent\ModelNotFoundException;

	// ROUTES -->

	$app->get('/products', 'getProducts');
	$app->get('/products/:id', 'getProduct');
	$app->post('/products', 'createProduct');
	$app->put('/products/:id', 'updateProduct');
	$app->delete('/products/:id', 'deleteProduct');
	$app->get('/list_products', 'getListProducts');


	// FUNCTIONS -->

	function getProducts() {
		setHeaders();
		$result["products"] = Product::all()->toArray();
		returnJson($result);
	}

	function createProduct() {
		setHeaders();
		$result = array();
		
		$app = \Slim\Slim::getInstance();
		$data = json_decode($app->request()->getBody(), true);
        
    $result["error"] = false;

		try {
			$product = new Product;
			$product->name = $data['name'];
			$product->small_description = $data['small_description'];
			$product->description = $data['description'];
			$product->image_path = $data['image_path'];
			$product->category_id = $data['category_id']; // todo fix this
			$product->client_id = $data['client_id'];
			$product->save();

			$result['product'] = $product->toArray();
		} catch (Exception $e) {
			$result['error'] = true;
		}
		
		returnJson($result);
	}

	function getProduct($id) {
		setHeaders();
		$result["error"] = false;
		try {
			$result["product"] = Product::findOrFail($id)->toArray();
		} catch(ModelNotFoundException $e) {	// what's the default value to return ?
			$result["error"] = true;
			$result["message"] = "Product not found";
		}
			
		returnJson($result);
	}

	function updateProduct($id) {
    setHeaders();
    $app = \Slim\Slim::getInstance();

    $data = json_decode($app->request()->getBody(), true);
    $result = array();

    $result["error"] = false;
    $product = Product::find($id);

    if($product != null) {
        $name = $data["name"];
        $image = $data["image_path"];
        $description = $data["description"];
        $small_description = $data["small_description"];
        $category_id = $data["category_id"];

        if($name != null) {
            $product->name = $name;
        }

        if($image != null) {
            $product->image_path = $image;
        }

        if($description != null) {
            $product->description = $description;
        } 

        if($small_description != null) {
            $product->small_description = $small_description;
        } 

        if($category_id != null) {
            $product->category_id = $category_id;
        } 

        $product->save();
        $result["product"] = $product->toArray();
    } else {
        $result["error"] = true;
        $result["message"] = "Product does not exist";
    }

    returnJson($result);
  }

  function deleteProduct($id) {
    setHeaders();
    
    $result["error"] = false;
    $product = Product::find($id);

    if($product != null) {
        $product->delete();
    } else {
        $result["error"] = true;
        $result["message"] = "Product does not exist";
    }

    returnJson($result);
  }

	function getListProducts() {
		setHeaders();
		
		$app = \Slim\Slim::getInstance();

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