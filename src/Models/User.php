<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $table	 = 'users';
  protected $fillable	 = ['name', 'login', 'email', 'password', 'role_id'];

  public function role() {
	return $this->belongsTo('App\\Models\\Role');
  }

  public function priviliges() {
	return $this->hasManyThrough('App\\Models\\Privilige', 'App\\Models\\Role');
  }

}
