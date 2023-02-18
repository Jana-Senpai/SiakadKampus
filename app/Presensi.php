<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Presensi extends Model
{
	//protected $primaryKey = "idx";
	protected $table = "presensi";
	protected $primaryKey = 'presensiId';
	public $timestamps = false;
	//protected $fillable = ['prodiKode','prodiKodeJurusan','prodiNama'];

	//protected $fillable = array('idx','nama');
	public function Mahasiswa(){
    	return $this->belongTo('App\Mahasiswa', 'foreign_key', 'mhsNim');
    }

}
