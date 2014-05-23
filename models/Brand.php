<?php

	class Brand extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bulla_brands';
        public $timestamps = false;

        public function products() {
	        return $this->hasMany('Product');
	    }

	    public function users() {
	    	return $this->hasMany('User');
	    }
    }

?>