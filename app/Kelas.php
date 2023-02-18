<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Kelas extends Model
{
  protected $table = "kelas";
  public $timestamps = false;
  protected $primaryKey = 'klsId';

  protected $guarded = ['klsId'];
  protected $fillable = ['klsMkKurId','klsDsnNidn','klsSempId','klsNama'];

}
