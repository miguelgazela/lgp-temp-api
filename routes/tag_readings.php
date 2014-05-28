<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/tag_readings', 'getTagReadings');
    $app->get('/tag_readings/:productId', 'getProductTagReadings');

    // FUNCTIONS -->

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