<?php

namespace App\Http\Controllers\Dosen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;

use Validator;
use App\Http\Controllers\Controller;
use App\Dosen as Dosen;
use App\Prodi as Prodi;
//use App\Jurusan as Jurusan;

class DosenController extends Controller
{
  
	public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $dataDosen = Dosen::select(DB::raw("dsnNidn, dsnNip, dsnNama, prodiNama, jurNama"))
        ->join('program_studi', 'program_studi.prodiKode', '=', 'dosen.dsnProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->orderBy(DB::raw("prodiKode"))        
        ->get();
        
    $data = array('dosen' => $dataDosen);   
    return view('admin.dashboard.dosen.dosen',$data);
  }

  public function detail($dsnNidn)
  {
    $dataDosen = Dosen::select(DB::raw("dsnNidn, dsnNip, dsnNama, prodiNama, jurNama"))
        ->join('program_studi', 'program_studi.prodiKode', '=', 'dosen.dsnProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->where('dsnNidn','=',$dsnNidn)
        ->orderBy(DB::raw("prodiKode"))        
        ->get();
        
    $data = array('dosen' => $dataDosen);   
    return view('admin.dashboard.dosen.detaildosen',$data);
  }

}

