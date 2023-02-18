<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Jurusan extends Model
{
	//protected $primaryKey = "idx";
	protected $table = "jurusan";
	public $timestamps = false;
	protected $primaryKey = 'jurKode';

	protected $fillable = array('jurKode','jurNama');

	public function Prodi(){
		return $this->hasMany('App\Prodi', 'foreign_key', 'prodiKodeJurusan');
	}
}
