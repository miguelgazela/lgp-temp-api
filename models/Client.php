<?php

	class Client extends Illuminate\Database\Eloquent\Model {
    protected $table = 'bulla_clients';
    public $timestamps = false;

    public function products() {
      return $this->hashMany('Product');
    }

    public function selling_locations() {
      return $this->hasMany('SellingLocation');
    }

    public function users() {
      return $this->hasMany('User');
    }
  }

?>