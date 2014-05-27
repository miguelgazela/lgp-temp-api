<?php

	class User extends Illuminate\Database\Eloquent\Model {
    protected $table = 'bulla_users';
    public $timestamps = false;

    public function client() {
      return $this->belongsTo('Client', 'client_id');
    }
  }

?>