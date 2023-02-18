<?php

namespace App\Http\Controllers\Kurikulum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;

use Validator;
use App\Http\Controllers\Controller;
use App\Kurikulum as Kurikulum;
use App\Prodi as Prodi;
//use App\Jurusan as Jurusan;

class KurikulumController extends Controller
{
  
	public function __construct()
  {
    $this->middleware('auth');
  }
      /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $dataKurikulum = Kurikulum::select(DB::raw("kurId, prodiNama,jurNama, kurTahun, kurNoSkRektor, kurNama"))
        ->join('program_studi', 'program_studi.prodiKode', '=', 'kurikulum.kurProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->orderBy(DB::raw("kurId, kurProdiKode"))        
        ->get();
        
    $data = array('kurikulum' => $dataKurikulum);   
    return view('admin.dashboard.kurikulum.kurikulum',$data);
  }

  public function matakuliah()
  {
    $dataKurikulum = Kurikulum::select(DB::raw("mkkurKode, prodiNama,jurNama, kurNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurJumlahSksTeori, mkkurJumlahSksPraktek"))
        ->join('matakuliah_kurikulum','mkkurKurId','=','kurikulum.kurId')
        ->join('program_studi', 'program_studi.prodiKode', '=', 'kurikulum.kurProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->orderBy(DB::raw("kurId, kurProdiKode"))        
        ->get();
        
    $data = array('kurikulum' => $dataKurikulum);   
    return view('admin.dashboard.kurikulum.matakuliah',$data);
  }

  public function detail($id)
  {
    $dataKurikulum = Kurikulum::select(DB::raw("mkkurKode, prodiNama,jurNama, kurNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurJumlahSksTeori, mkkurJumlahSksPraktek"))
        ->join('matakuliah_kurikulum','mkkurKurId','=','kurikulum.kurId')
        ->join('program_studi', 'program_studi.prodiKode', '=', 'kurikulum.kurProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->where('kurikulum.kurId','=',$id)
        ->orderBy(DB::raw("kurId, kurProdiKode"))        
        ->get();
        
    $data = array('kurikulum' => $dataKurikulum);   
    return view('admin.dashboard.kurikulum.matakuliah',$data);
  }  
  
}

