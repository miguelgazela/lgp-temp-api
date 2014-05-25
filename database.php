<?php
    ini_set('display_errors', 'On');
    require 'vendor/autoload.php';

    use Illuminate\Database\Capsule\Manager as Capsule;  
 
    $capsule = new Capsule; 
     
    $capsule->addConnection(array(
        'driver'    => 'mysql',
        'host'      => 'db.fe.up.pt',
        'database'  => 'ei10076',
        'username'  => 'ei10076',
        'password'  => 'PC14GSA25',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ));

    // $capsule->addConnection(array(
    //     'driver'    => 'mysql',
    //     'host'      => '127.0.0.1',
    //     'database'  => 'bulla_app',
    //     'username'  => 'root',
    //     'password'  => 'BKsrUgn6',
    //     'charset'   => 'utf8',
    //     'collation' => 'utf8_unicode_ci',
    //     'prefix'    => ''
    // ));
     
    $capsule->bootEloquent();
?>
