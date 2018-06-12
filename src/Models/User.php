<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

	protected $table	 = 'users';
	protected $fillable	 = ['name', 'login', 'email', 'password', 'group_id'];

	public function role()
	{
		return $this->belongsTo('App\\Models\\Role');
	}

	public function group()
	{
		return $this->belongsTo('App\\Models\\Group');
	}

	public function groups()
	{
		return $this->belongsToMany('App\\Models\\Group', 'user_groups');
	}

	public function priviliges()
	{
		return $this->hasManyThrough('App\\Models\\Privilige', 'App\\Models\\Role');
	}

}
