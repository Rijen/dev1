<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

	const VIEW = 1; //0001
	const EDIT = 3; //0001|0010

	protected $table	 = 'roles';
	protected $fillable	 = ['name', 'component_id', 'level'];

	public function component()
	{
		return $this->belongsTo('App\\Models\\Component');
	}

	public function users()
	{
		return $this->belongsToMany('App\\Models\\User', 'user_roles');
	}

}
