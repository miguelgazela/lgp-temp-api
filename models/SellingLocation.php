<?php

	class SellingLocation extends Illuminate\Database\Eloquent\Model {
    protected $table = 'bulla_selling_locations';
    public $timestamps = false;

    public function client() {
      return $this->belongsTo('Client', 'client_id');
    }
  }

?>