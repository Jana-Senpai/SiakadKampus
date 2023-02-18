<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SemesterProdi extends Model
{

	protected $table = "semester_prodi";
	public $timestamps = false;
	protected $primaryKey = 'sempId';

	//proteksi for autoincrement field
	protected $guarded = ['sempId'];

}
