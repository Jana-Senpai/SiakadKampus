<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Kurikulum extends Model
{
	//protected $primaryKey = "idx";
	protected $table = "kurikulum";
	public $timestamps = false;
	protected $primaryKey = 'kurId';

	protected $fillable = array('kurProdiKode','kurTahun','kurNama','kurNoSKRektor');


}
