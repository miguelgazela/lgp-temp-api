<?php

	$app->post('/products', function() use ($app) {
		setup($app);

		$data = $app->request()->getBody();
		$res = array("result" => "worked", "data" => $data);
		returnJson($res);
	});

	$app->get('/products/:id', function ($id) use ($app) {
		$product = Product::get($id);
		returnJson($product);
	});

	$app->get('/products', function () use ($app) {
		$products = Product::all();
		returnJson($products);
	});

?>