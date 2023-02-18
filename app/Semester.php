<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Semester extends Model
{
	//protected $primaryKey = "idx";
	protected $table = "semester";
	public $timestamps = false;
	protected $primaryKey = 'semId';

	//protected $fillable = array('kurProdiKode','kurTahun','kurNama','kurNoSKRektor');

	/*public function Prodi(){
		return $this->hasMany('App\Prodi', 'foreign_key', 'prodiKodeJurusan');
	}*/
}
