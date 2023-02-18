<?php

namespace App\Http\Controllers\Nilai;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;
use Excel;
use Validator;
use Session;
use App\Http\Controllers\Controller;
use App\KrsDetail as KrsDetail;
use App\Kelas as Kelas;

class NilaiController extends Controller
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
    $dataSemester = Semester::select(DB::raw("semId, semTglMulai, semTglSelesai,semTahun, semNmSmtId, semStatus"))        
        ->orderBy(DB::raw("semId"))        
        ->get();
        
    $data = array('semester' => $dataSemester);   
    return view('admin.dashboard.semester.semester',$data);
  }

  public function jenis()
  {
    
    /*$dataSemester = Nilai::select(DB::raw("semId, semTglMulai, semTglSelesai,semTahun, semNmSmtId, semStatus"))        
        ->orderBy(DB::raw("semId"))        
        ->get();
        
    $data = array('semester' => $dataSemester);   */
    return view('admin.dashboard.nilai.jenisnilai');
  
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


  public function downloadPeserta($id, $type)

  {
    $dataMasukExport =  array(); 
    $dataMasukExport[] = array('No','NIM','Nama','Nilai Tugas','Nilai UTS','Nilai UAS','Nilai Akhir','Nilai Huruf');

    $dataExport = Kelas::select(DB::raw("mhsNim, mhsNama, sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, klsId, klsNama, krsnaNilaiTugas,krsnaNilaiUTS,krsnaNilaiUAS,krsdtBobotNilai,krsdtKodeNilai"))   
        ->join('krs_detail', 'klsId', '=', 'krs_detail.krsKlsId')
        ->leftjoin('krs_nilai','krsnaKrsDtId','=','krsdtId')
        ->join('krs', 'krsId', '=', 'krs_detail.krsdtKrsId')
        ->join('mahasiswa', 'mhsNim', '=', 'krs.krsNim')
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId') 
        ->join('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')            
        ->orderBy(DB::raw("mhsNim"))  
        ->where('krs_detail.krsKlsId','=',$id)
        ->get()->toArray();

    if(!empty($dataExport)){
      $no=1;    
      foreach ($dataExport as $itemData) {
          
          $dataMasukExport[] = [$no, $itemData['mhsNim'], $itemData['mhsNama'],$itemData['krsnaNilaiTugas'],$itemData['krsnaNilaiUTS'],$itemData['krsnaNilaiUAS'],$itemData['krsdtBobotNilai'],$itemData['krsdtKodeNilai'] ];
          $no++;
      }

      //CATATAN PENTING : BAGIAN use DARI FUNCTION dalam pembentukan sheet INI HARUS DISUPPORT OLEH DATA YANG YANG INGIN DIGUNAKAN DALAM FUNCTION
      //JIKA TIDAK AKAN DICARI(ERROR) 
      //ex: function($excel) use ($dataExport,$dataMasukExport) 
      return Excel::create('Nilai '.$dataExport[0]['mkkurNama'], function($excel) use ($dataExport,$dataMasukExport, $no) {
        // Set the spreadsheet title, creator, and description
        $excel->setTitle('Peserta');
        $excel->setCreator('SIAKAD')->setCompany('Adam');
        $excel->setDescription('Berkas Import Nilai Larasia');

        
        $excel->sheet('Nilai '.$dataExport[0]['mkkurKode'], function($sheet) use ($dataMasukExport, $no) {
            $sheet->fromArray($dataMasukExport, null, 'A1', false, false);
            $sheet->row(1, function($row) {
                // call cell manipulation methods
                $row->setBackground('#e9e9e9'); 
                $row->setFontWeight('bold');             
            });

            $sheet->cells('A1:H'.$no, function($cells) {
                $cells->setAlignment('center');
            });

            $sheet->cells('C2:C'.$no, function($cells) {
                $cells->setAlignment('left');             
            });
            
            $sheet->setBorder('A1:H'.$no, 'thin');          

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Calibri',
                    'size'      =>  11
                    //'bold'      =>  true
                )
            ));

            $sheet->setFreeze('D2');
            //autosize column
            $sheet->setAutoSize(true);
            
        });
   
      })->download($type);

      $messages = "Data peserta kelas berhasil didownload!";
    //jika data tidak ditemukan
    }else {
      $messagesError = "Kelas belum terbentuk, pastikan sudah ada Mahasiswa, Kelas dan Dosen untuk Matakuliah";
      
    }
    session()->flash('error',isset($messagesError) ? $messagesError : '');
    session()->flash('success',isset($messages) ? $messages : '');

     return back();
  }

  public function importNilai(Request $request)

  {

    if(Input::hasFile('import_file')){
      $path = Input::file('import_file')->getRealPath();
      $data = Excel::load($path, function($reader) {
              })->get();
      //print_r($data);
      //echo $request->session()->get('semId').'-'.$request->get('klsId');

      if(!empty($data) && $data->count()){
        foreach ($data as $key => $value) {
          $mhs = KrsDetail::select(DB::raw("krsdtId"))
                  ->join('krs','krsdtKrsId','=','krs.krsId')
                  ->join('semester_prodi','sempId','=','krsSempId')
                  ->where('sempSemId',$request->session()->get('semId'))
                  ->where('krsKlsId',$request->get('klsId'))
                  ->where('krsNim', $value->nim)->first();

          if(!empty($mhs)){
            echo $request->get('klsId').'-'.$request->session()->get('semId')."<-semId->". $value->nim.'-->'.$mhs->krsdtId."=".$value->nilai_tugas."-".$value->nilai_uts."-".$value->nilai_uas."<br>";
            $insert[] = ['krsnaKrsDtId' => $mhs->krsdtId, 'krsnaNilaiTugas' => $value->nilai_tugas, 'krsnaNilaiUTS' => $value->nilai_uts, 'krsnaNilaiUAS' => $value->nilai_uas];
            //print_r($insert);
            //delete nilai sebelumnya
            if(DB::table('krs_nilai')->where('krsnaKrsDtId', '=', $mhs->krsdtId)->exists()){
             DB::table('krs_nilai')->where('krsnaKrsDtId', '=', $mhs->krsdtId)->delete();
            }
            DB::table('krs_detail')
              ->where('krsdtId', $mhs->krsdtId)
              ->update(['krsdtBobotNilai' => $value->nilai_akhir,
                        'krsdtKodeNilai' => $value->nilai_huruf]);
          
          }else {
            Session::flash('error', 'Kesalahan! Cek apakah data nilai yang anda masukkan sesuai dengan kelas.');
          }
        }
        //setelah collect data tinggal diinsert        
        if(!empty($insert)){
              DB::table('krs_nilai')->insert($insert);
              Session::flash('success', 'Berhasil upload Nilai');             
              //dd('Insert Record successfully.');
        }
      }
    }

    return back();

  }

  /**DONE**/  
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

