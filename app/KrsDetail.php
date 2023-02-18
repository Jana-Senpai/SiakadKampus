<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class KrsDetail extends Model
{
	//protected $primaryKey = "idx";
	protected $table = "krs_detail";
	public $timestamps = false;
	protected $primaryKey = 'krsdtId';

	//protected $fillable = array('kurProdiKode','kurTahun','kurNama','kurNoSKRektor');

	/*public function Prodi(){
		return $this->hasMany('App\Prodi', 'foreign_key', 'prodiKodeJurusan');
	}*/
}
