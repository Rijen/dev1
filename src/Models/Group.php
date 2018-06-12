<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

	protected $table	 = 'groups';
	protected $fillable	 = ['name', 'descr'];

//	public function users()
//	{
//		return $this->hasMany('App\\Models\\User');
//	}

	public function users()
	{
		return $this->belongsToMany('App\\Models\\User', 'user_groups');
	}

}
