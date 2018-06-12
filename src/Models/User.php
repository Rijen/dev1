<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

	protected $table	 = 'users';
	protected $fillable	 = ['name', 'login', 'email', 'password', 'group_id', 'filial_id'];

//	public function role()
//	{
//		return $this->belongsTo('App\\Models\\Role');
//	}
//		public function priviliges()
//	{
//		return $this->hasManyThrough('App\\Models\\Privilige', 'App\\Models\\Role');
//	}

	public function initials()
	{
		return $this->family . ' ' . mb_substr($this->name, 0, 1) . '. ' . mb_substr($this->surname, 0, 1) . '. ';
	}

	public function photo()
	{

		foreach (['png', 'jpg', 'jpeg'] as $ex)
		{
			if (is_file('./img/users/' . $this->id . '.' . $ex))
			{
				return '/img/users/' . $this->id . '.' . $ex;
			}
		}
		return false;
	}

	public function group()
	{
		return $this->belongsTo('App\\Models\\Group');
	}

	public function filial()
	{
		return $this->belongsTo('App\\Models\\Filial');
	}

	public function groups()
	{
		return $this->belongsToMany('App\\Models\\Group', 'user_groups');
	}

	public function roles()
	{
		return $this->belongsToMany('App\\Models\\Role', 'user_roles');
	}

}
