<?php

namespace App\Http\Controllers\Jurusan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Input;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jurusan as Jurusan;


class JurusanController extends Controller
{

	public function __construct()
  {
    //$this->middleware('auth');
   // $this->middleware('auth:dosen');
  }
      /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $data = array('jurusan' => Jurusan::all());
    
   
    return view('admin.dashboard.jurusan.jurusan',$data);
    //return view('admin.dashboard.jurusan');
  }
  
  public function tambah()
  {
    //$data = array('jurusan' => Jurusan::all());

   
    return view('admin.dashboard.jurusan.tambahjurusan');
    //return view('admin.dashboard.jurusan');
  }

  public function tambahjurusan(Request $request)
  {
    //$data = array('jurusan' => Jurusan::all());

   
        $input =$request->all();
        $pesan = array(
            'jurKode.required'    => 'Kode Jurusan dibutuhkan.',            
            'jurKode.unique'      => 'Kode Jurusan sudah digunakan.',
            'jurNama.required'    => 'Nama Jurusan dibutuhkan.',            
        );

        $aturan = array(

            'jurKode' => 'required|unique:jurusan',
            'jurNama' => 'required|max:60',
            
        );
        

        $validator = Validator::make($input,$aturan, $pesan);

        if($validator->fails()) {
            # Kembali kehalaman yang sama dengan pesan error
            return Redirect::back()->withErrors($validator)->withInput();

          # Bila validasi sukses
        }

        $jurusan = new Jurusan;
        $jurusan->jurKode = $request['jurKode']; //atau bisa $input['prodiKode']
        $jurusan->jurNama = $input['jurNama'];
        
        if (! $jurusan->save())
          App::abort(500);

        return Redirect::action('Jurusan\JurusanController@index')
                          ->with('successMessage','Data jurusan "'.$input['jurNama'].'" telah berhasil diubah.'); 
      
    //return view('admin.dashboard.jurusan');
  }

  public function hapus($id)
  {
    $jurKode = Jurusan::where('jurKode','=',$id)->first();

    if($jurKode==null)
      app::abort(404);
    $jurKode->delete();
    
    return Redirect::route('jurusan');

  }

  

}

