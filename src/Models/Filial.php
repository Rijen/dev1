<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{

	protected $table	 = 'filials';
	protected $fillable	 = ['name'];

	public function users()
	{
		return $this->hasMany('App\\Models\\User');
	}


}
