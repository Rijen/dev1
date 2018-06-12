<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{

	protected $table	 = 'components';
	protected $fillable	 = ['id', 'name'];

	public function roles()
	{
		return $this->hasMany('App\\Models\\Role');
	}

}
