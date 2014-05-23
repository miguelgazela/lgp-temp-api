<?php

	class Product extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bulla_products';
        public $timestamps = false;

        public function brand()
	    {
	        return $this->belongsTo('Brand', 'brand_id');
	    }

	    public function category()
	    {
	        return $this->belongsTo('Category', 'category_id');
	    }
    }

?>