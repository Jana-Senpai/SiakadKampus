<?php

namespace App\Http\Controllers\Semester;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;

use Validator;
use App\Http\Controllers\Controller;
use App\Semester as Semester;
use App\SemesterProdi as SemesterProdi;
use App\Prodi as Prodi;

class SemesterController extends Controller
{
  
	public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $dataSemester = Semester::select(DB::raw("semId, semTglMulai, semTglSelesai,semTahun, semNmSmtId, semStatus"))
        ->orderBy(DB::raw("semId"))        
        ->get();
        
    $data = array('semester' => $dataSemester);   
    return view('admin.dashboard.semester.semester',$data);
  }

  public function default_aktif(Request $request,$id)
  {    
    
    if(Semester::findOrFail($id))
    {
      DB::table('semester')
          ->update(['semStatus' => 0]);

      $editSemester = Semester::find($id);
      $editSemester->semStatus = 1;    
      if (! $editSemester->save())
        App::abort(500);

      //aktifkan di semester prodi juga
      DB::table('semester_prodi')
          ->update(['sempIsAktif' => 0]);

      SemesterProdi::where('sempSemId', $id)
                      ->update(['sempIsAktif' => 1]);
        
      $request->session()->set('semId', $id);    
    }
    
    return Redirect::action('Semester\SemesterController@index');
  }

  public function semesterprodi()
  {
    $dataSemester = SemesterProdi::select(DB::raw("semId, sempTglMulaiKrs, sempTglSelesaiKrs, sempTglMulaiInputNilai, sempTglSelesaiInputNilai, semTahun, semNmSmtId, sempIsAktif, prodiKode, prodiNama, jurNama"))   
        ->join('semester', 'semester_prodi.sempSemId', '=', 'semester.semId')
        ->join('program_studi', 'program_studi.prodiKode', '=', 'semester_prodi.semProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')     
        ->orderBy(DB::raw("semId,prodiKode"))        
        ->get();
        
    $data = array('semesterprodi' => $dataSemester);   
    return view('admin.dashboard.semester.semesterprodi',$data);
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

  public function hapus($id)
  {
   
    $semId = Semester::where('semId', '=', $id)->first();

    if ($semId == null)
      app::abort(404);

    $semId->delete();

    return Redirect::action('Semester\SemesterController@index');

  }

  protected function validator(array $data)
  {
        $messages = [
            'semId.required'          => 'Kode Semester dibutuhkan.',
            'semId.unique'            => 'Kode Semester sudah digunakan.',
            'semTglMulai.required'    => 'Tanggal mulai dibutuhkan.',
            'semTglMulai.date_format'    => 'Sesuaikan struktur tangggal yyyy-mm-dd.',
            'semTglSelesai.required'  => 'Tanggal selesai semester dibutuhkan.',
            'semTglSelesai.date_format'    => 'Sesuaikan struktur tangggal yyyy-mm-dd.',
            'semTahun.required'       => 'Tahun Semester diadakan dibutuhkan.',
            'semNmSmtId.required'     => 'Nama Semester dibutuhkan.',
        ];
        return Validator::make($data, [
            'semId'         => 'required|unique:semester',
            'semTglMulai'   => 'date_format:Y-m-d|required',
            'semTglSelesai' => 'date_format:Y-m-d|required',
            'semTahun'      => 'required',
            'semNmSmtId'    => 'required',
        ], $messages);
  }
 
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
  protected function tambah(array $data)
  {

        $semester = new Semester();
        $semester->semId          = $data['semId'];
        $semester->semTglMulai    = $data['semTglMulai'];
        $semester->semTglSelesai  = $data['semTglSelesai'];
        $semester->semTahun       = $data['semTahun'];
        $semester->semNmSmtId     = $data['semNmSmtId'];

        //melakukan save, jika gagal (return value false) lakukan harakiri
        //error kode 500 - internel server error
        if (! $semester->save() )
          App::abort(500);
  }
 
  public function tambahsemester(Request $request)
  {
        $validator = $this->validator($request->all());
 
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
           
        }
 
        $this->tambah($request->all());
 
        return response()->json($request->all(),200);
       
    }

    //Registrasikan Semua Prodi pada prodi-sem dengan acuan semester aktif
    public function registerSemesterProdi(Request $request)
    {
      //cari prodi
      $listProdi = Prodi::all();
      $semId = $request->session()->get('semId');

      //daftarkan setiap prodi pada semester prodi dengan acuan smester aktif
      foreach ($listProdi as $itemListProdi) {

        $prodiKode = $itemListProdi->prodiKode;

        $dataKurikulum = SemesterProdi::select(DB::raw("sempId"))
          ->where('semProdiKode',$prodiKode)
          ->where('sempSemId',$semId)
          ->first();

        if(!isset($dataKurikulum)){
          $SemesterProdi = new SemesterProdi;          
          $SemesterProdi->sempSemId                 = $semId;
          $SemesterProdi->sempTglMulaiKrs           = date('Y-m-d');
          $SemesterProdi->sempTglSelesaiKrs         = date('Y-m-d',strtotime("+2 weeks", time()));
          $SemesterProdi->sempTglMulaiInputNilai    = date('Y-m-d',strtotime("+16 weeks", time()));
          $SemesterProdi->sempTglSelesaiInputNilai  = date('Y-m-d',strtotime("+20 weeks", time()));
          $SemesterProdi->sempIsAktif               = 1;
          $SemesterProdi->semProdiKode              = $prodiKode;
          $SemesterProdi->save();
        }
      }

      return redirect()->route('semesterprodi');
    }


}

