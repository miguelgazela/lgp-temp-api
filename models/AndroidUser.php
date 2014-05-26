<?php

  class AndroidUser extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bulla_android_users';
        public $timestamps = false;

        public function tag_readings() {
          return $this->hasMany('TagReading');
        }
    }

?>