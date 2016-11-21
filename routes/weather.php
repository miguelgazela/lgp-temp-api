<?php
	use Illuminate\Database\Eloquent\ModelNotFoundException;

	// ROUTES -->

	$app->get('/weather', 'getWeather');

	// FUNCTIONS -->

	function getWeather() {
		setHeaders();

		$weather = file_get_contents("http://api.openweathermap.org/data/2.5/weather?id=6458924&APPID=acfc33c824b851fa7cbeafbbb0514942");
		returnJson($weather);
	}
?>