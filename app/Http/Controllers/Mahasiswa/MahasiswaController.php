<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;

use Validator;
use App\Http\Controllers\Controller;
use App\Mahasiswa as Mahasiswa;
use App\Prodi as Prodi;
//use App\Jurusan as Jurusan;

class MahasiswaController extends Controller
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
    $dataMahasiswa = Mahasiswa::select(DB::raw("mahasiswa.mhsNim as Nim, mhsNama, prodiNama, jurNama, mhsAngkatan, mhsKurId, kurNama, mhsKelompok"))
        ->join('program_studi', 'program_studi.prodiKode', '=', 'mahasiswa.mhsProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->join('kurikulum','mhsKurId','=','kurikulum.kurId')
        ->orderBy(DB::raw("prodiKode"))        
        ->get();
        
    $data = array('mahasiswa' => $dataMahasiswa);   
    return view('admin.dashboard.mahasiswa.mahasiswa',$data);
  }

  public function detail($mhsNim)
  {
    $dataMahasiswa = Mahasiswa::select(DB::raw("mahasiswa.mhsNim as Nim, mhsNama, prodiNama, jurNama, mhsAngkatan, mhsKurId, kurNama, mhsKelompok"))
        ->join('program_studi', 'program_studi.prodiKode', '=', 'mahasiswa.mhsProdiKode')
        ->join('jurusan','prodiKodeJurusan','=','jurusan.jurKode')
        ->join('kurikulum','mhsKurId','=','kurikulum.kurId')
        ->where('mhsNim','=',$mhsNim)
        ->orderBy(DB::raw("prodiKode"))        
        ->get();
        
    $data = array('mahasiswa' => $dataMahasiswa);   
    return view('admin.dashboard.mahasiswa.detailmahasiswa',$data);
  }

  public function hapus($id)
  {
   
    $prodiKode = Prodi::where('prodiKode', '=', $id)->first();
    //$prodiKode = Prodi::find($id)->program_studi()->where('prodiKode', 'foo')->first();
    if ($prodiKode == null)
      app::abort(404);
    /*print_r($prodiKode->prodiKode);
    exit;*/
    $prodiKode->delete();
    //return Redirect::route('prodi');
    return Redirect::action('Prodi\ProdiController@index');
            /*->with('successMessage','Data dengan kode '.$prodiKode->prodiKode.' telah berhasil dihapus');*/
  }

  protected function validator(array $data)
  {
        $messages = [
            'prodiKode.required'    => 'Kode Program Studi dibutuhkan.',
            'prodiKode.unique'      => 'Kode Program Studi sudah digunakan.',
            'prodiNama.required'    => 'Nama Program Studi dibutuhkan.',
            'prodiJurKode.required' => 'Kita membutuhkan Kode Jurusan asal Program Studi .',
        ];
        return Validator::make($data, [
            'prodiKode' => 'required|unique:program_studi',
            'prodiNama' => 'required|max:60',
            'prodiJurKode' => 'required',
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

        $prodi = new Prodi();
        $prodi->prodiKode         = $data['prodiKode'];
        $prodi->prodiKodeJurusan  = $data['prodiJurKode'];
        $prodi->prodiNama         = $data['prodiNama'];

        //melakukan save, jika gagal (return value false) lakukan harakiri
        //error kode 500 - internel server error
        if (! $prodi->save() )
          App::abort(500);
  }
 
  public function tambahprodi(Request $request)
    {
        $validator = $this->validator($request->all());
 
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
            //return Response::json( array('errors' => $validator->errors()->toArray()),422);
        }
 
        $this->tambah($request->all());
 
        return response()->json($request->all(),200);
        //return Redirect::action('Prodi\ProdiController@index')
        //               ->with('successMessage','Data prodi "'.$request->prodiNama.'" telah berhasil ditambahkan'); 

       /* $validator = $this->validator($request->all());

        if ($validator->passes()) 
        {                
           
          if ($this->tambah($request->all())){
            if(Request::ajax()){

               return Response::json(array('success' => true));
            }
          }
          return Redirect::to('prodi')->withError("Error: an error has occurred.");
        }
        
        return Response::json( array('fail' => true,'errors' => $validator->errors()->toArray()));
        */
    }

    public function editprodi($id)
    {

        $data = Prodi::find($id);
        $jurusan = Jurusan::orderBy('jurKode')->get();

        return view('admin.dashboard.prodi.editprodi',$data)
                ->with('listjurusan', $jurusan);
    }

    public function simpanedit($id)
    {
        $input =Input::all();
        $messages = [
            'prodiKode.required'    => 'Kode Program Studi dibutuhkan.',            
            'prodiNama.required'    => 'Nama Program Studi dibutuhkan.',
            'prodiJurKode.required' => 'Kita membutuhkan Kode Jurusan asal Program Studi .',
        ];
        

        $validator = Validator::make($input, [
                          'prodiKode' => 'required',
                          'prodiNama' => 'required|max:60',
                          'prodiJurKode' => 'required',
                      ], $messages);

        if($validator->fails()) {
            # Kembali kehalaman yang sama dengan pesan error
            return Redirect::back()->withErrors($validator)->withInput();
          # Bila validasi sukses
        }

        $editProdi = Prodi::find($id);
        $editProdi->prodiKode = Input::get('prodiKode'); //atau bisa $input['prodiKode']
        $editProdi->prodiNama = $input['prodiNama'];
        $editProdi->prodiKodeJurusan =  Input::get('prodiJurKode');
        if (! $editProdi->save())
          App::abort(500);

        return Redirect::action('Prodi\ProdiController@index')
                          ->with('successMessage','Data prodi "'.Input::get('prodiNama').'" telah berhasil diubah.'); 
    }
}

