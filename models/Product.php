<?php

	class Product extends Illuminate\Database\Eloquent\Model {
    protected $table = 'bulla_products';
    public $timestamps = false;

    public function client() {
      return $this->belongsTo('Client', 'client_id');
    }

    public function category() {
      return $this->belongsTo('Category', 'category_id');
    }

    public function tag_readings() {
      return $this->hasMany('TagReading');
    }
  }

?>