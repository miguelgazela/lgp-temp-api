<?php

	class User extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bulla_users';
        public $timestamps = false;

        public function brand()
	    {
	        return $this->belongsTo('Brand', 'brand_id');
	    }
    }

?>