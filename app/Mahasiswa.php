<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Mahasiswa extends Model
{
	//protected $primaryKey = "idx";
	protected $table = "mahasiswa";
	protected $primaryKey = 'mhsNim';
	public $timestamps = false;
	//protected $fillable = ['prodiKode','prodiKodeJurusan','prodiNama'];

	//protected $fillable = array('idx','nama');
	public function Prodi(){
    	return $this->belongTo('App\prodi', 'foreign_key', 'prodiKode');
    }

    public function Krs(){
    	return $this->belongTo('App\Krs', 'foreign_key', 'mhsNim');
    }


}
