<?php
    ini_set('display_errors', 'On');
    require 'vendor/autoload.php';

    use Illuminate\Database\Capsule\Manager as Capsule;  
 
    $capsule = new Capsule; 
     
    // $capsule->addConnection(array(
    //     'driver'    => 'mysql',
    //     'host'      => 'db.fe.up.pt',
    //     'database'  => 'ei10076',
    //     'username'  => 'ei10076',
    //     'password'  => 'PC14GSA25',
    //     'charset'   => 'utf8',
    //     'collation' => 'utf8_unicode_ci',
    //     'prefix'    => ''
    // ));

    $capsule->addConnection(array(
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'database'  => 'bulla_app',
        'username'  => 'root',
        'password'  => 'BKsrUgn6',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ));
     
    $capsule->bootEloquent();

    // Connect to the Database
    // $db = new PDO('mysql:host=db.fe.up.pt;dbname=ei10076', 'ei10076', 'PC14GSA25');
    // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
?>
