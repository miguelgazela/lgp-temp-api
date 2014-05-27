<?php

	class TagReading extends Illuminate\Database\Eloquent\Model {
    protected $table = 'bulla_tag_readings';
    public $timestamps = false;

    public function product() {
      return $this->belongsTo('Product', 'product_id');
    }

    public function android_user() {
        return $this->belongsTo('AndroidUser', 'android_user_id');
    }
  }
?>