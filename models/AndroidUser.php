<?php
  /*
    Access:
      https://db.fe.up.pt/phpmyadmin/index.php

      User: ei10076
      Password: PC14GSA25
  */
  class AndroidUser extends Illuminate\Database\Eloquent\Model {
    protected $table = 'bulla_android_users';
    public $timestamps = false;

    public function tag_readings() {
      return $this->hasMany('TagReading');
    }
  }

?>