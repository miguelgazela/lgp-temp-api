<?php

	class Category extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bulla_categories';
        public $timestamps = false;

        public function products() {
	        return $this->hasMany('Product');
	    }
    }

?>