<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Krs extends Model
{
	protected $table = "krs";
	protected $primaryKey = 'krsId';
	public $timestamps = false;
	protected $guarded = ['krsId'];
}
