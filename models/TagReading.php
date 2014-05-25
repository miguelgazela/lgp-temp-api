<?php

	class TagReading extends Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bulla_tag_readings';
        public $timestamps = false;

        public function product()
	    {
	        return $this->belongsTo('Product', 'product_id');
	    }

	    public function client()
	    {
	        return $this->belongsTo('Client', 'client_id');
	    }
    }

?>