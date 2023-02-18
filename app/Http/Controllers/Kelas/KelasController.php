<?php

namespace App\Http\Controllers\Kelas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;

use Validator;
use App\Http\Controllers\Controller;

use App\Kelas as Kelas;
use App\SemesterProdi as SemesterProdi;
use App\Semester as Semester;
use App\KrsDetail as KrsDetail;
use App\Prodi as Prodi;
use App\Mahasiswa as Mahasiswa;
//use App\Matakuliah as Matakuliah;
use App\Kurikulum as Kurikulum;
use App\Krs as Krs;
use App\Dosen as Dosen;

//use App\Jurusan as Jurusan;

class KelasController extends Controller
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
  public function index(Request $request)
  {
    $dataKelas = Kelas::select(DB::raw("sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurSemester,klsId, klsNama"))   
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')           
        ->where('sempSemId','=',$request->session()->get('semId'))  
        ->orderBy(DB::raw("mkkurKode, mkkurSemester, klsNama  "))  
        /*->where('semester_prodi.semProdiKode','=',$id)*/
        ->get();
    $listProdi = Prodi::all();    

    $dataDosen = Dosen::select(DB::raw("dsnNidn, dsnNip, dsnNama"))                           
        ->orderBy(DB::raw("dsnNama, dsnNip")) 
        ->get();
    //$data = array('kelas' => $dataKelas);   
    return view('admin.dashboard.kelas.kelas')
            ->with('kelas', $dataKelas)
            ->with('listProdi', $listProdi)
            ->with('dosen', $dataDosen)
            ->with('prodi_terpilih', '');
  }

  public function kelasprodi(Request $request)
  {
    $input =$request->all();
    $id = $input['prodiKode'];

    //echo "Ini loh ID = ".$id;
    $dataKelas = Kelas::select(DB::raw("sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, klsId, klsNama"))   
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')              
        ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')        
        ->where('semester_prodi.semProdiKode','=',$id)
        ->where('sempSemId','=',$request->session()->get('semId')) 
        ->orderBy(DB::raw("mkkurKode, klsNama")) 
        ->get();
        
    $listProdi = Prodi::all();    
    //$data = array('kelas' => $dataKelas);   
    //
    $dataDosen = Dosen::select(DB::raw("dsnNidn, dsnNip, dsnNama"))                   
        ->where('dsnProdiKode','=',$id)        
        ->orderBy(DB::raw("dsnNama, dsnNip")) 
        ->get();

    return view('admin.dashboard.kelas.kelas')
            ->with('kelas', $dataKelas)
            ->with('listProdi', $listProdi)
            ->with('dosen', $dataDosen)
            ->with('prodi_terpilih', $id);
  
  }

  public function showMahasiswaRegister(Request $request)
  {
    $dataKelas = Mahasiswa::select(DB::raw("sempSemId,mhsNim as Nim, mhsNama, mhsAngkatan, mhsKelompok, prodiNama"))
      ->join('program_studi','prodiKode','=','mhsProdiKode')   
      ->join('krs','krsNim','=','mhsNim')
      ->join('semester_prodi','sempId','=','krsSempId')
      ->where('mhsStatusAktif','=','1')
      ->where('sempIsAktif','=','1')
      ->where('sempSemId','=',$request->session()->get('semId'))      
      ->orderBy(DB::raw("mhsAngkatan, mhsNim, mhsKelompok")) 
      ->get();
        
    $listProdi = Prodi::all();
    return view('admin.dashboard.kelas.mahasiswaregister')
                ->with('mahasiswa', $dataKelas)
                ->with('listProdi', $listProdi)
                ->with('prodi_terpilih', '');

  }

  public function kelasMahasiswaRegister(Request $request)
  {
     

    //Select semua mahasiswa yang aktif
    $SemesterMhsAktif = Mahasiswa::select(DB::raw('mhsNim as Nim,mhsAngkatan,mhsKurId, mhsProdiKode'))
      ->where('mhsStatusAktif','=','1')
      ->orderBy(DB::raw('Nim,mhsKelompok,mhsAngkatan'))
      ->get();

    //sJika ada mahasiswa aktif
    if ($SemesterMhsAktif->count()) {
      //$i=1;

      //setiap mahasiswa aktif akan dicek berdasar paket semester terakhir   
      foreach($SemesterMhsAktif as $itemSemesterMhsAktif){    

        //mecari semester prodi Id yang aktif sesuai dengan prodi mhs
        $semProdiAktif = Semester::select(DB::raw('sempId')) 
            ->join('semester_prodi','sempSemId','=','semId') 
            ->where('semProdiKode',$itemSemesterMhsAktif->mhsProdiKode) 
            ->where('sempSemId',$request->session()->get('semId'))
            ->where('sempIsAktif','1')
            ->first();
        if(isset($semProdiAktif)){ 
          try {
            $sempId = $semProdiAktif->sempId; 
          } catch (Exception $e) {
            continue;
          }
        }
        //cari paket semester terakhir untuk tiap mahasiswa aktif
        $cariPaketSemMax = Krs::Select(DB::raw('MAX(krsPaketSem) as krsPaketSem, sempSemId'))
          ->join('semester_prodi','SempId','=','krsSempId')           
          ->where('krsNim','=',$itemSemesterMhsAktif->Nim)          
          ->first();
        //dd($cariPaketSemMax);
        
          if($cariPaketSemMax->sempSemId > $request->session()->get('semId') ){
            if($cariPaketSemMax->krsPaketSem > 1){
              $semRegister = isset($cariPaketSemMax) ? ($cariPaketSemMax->krsPaketSem - 1) : 1;
            }else $semRegister = 1;
          }else{
            $semRegister = isset($cariPaketSemMax) ? ($cariPaketSemMax->krsPaketSem + 1) : 1;
          }
        
        

        //Cek Mahasiswa aktif dengan Nim paketSem maksimal pada semester aktif apakah sudah ada blm
        //kalo ada pertahankan kalo belum ada tambahkan 
        $cekMhsKrs = Mahasiswa::select(DB::raw('mhsNim as Nim, sempId'))
          ->join('krs','krsNim','=','mhsNim')
          ->join('semester_prodi','sempId','=','krsSempId')
          ->where('krsPaketSem','=',$cariPaketSemMax->krsPaketSem)
          ->where('sempSemId','=',$request->session()->get('semId'))
          ->where('krsNim','=',$itemSemesterMhsAktif->Nim)
          ->orderBy('mhsNim')
          ->first();


          
        if(!isset($cekMhsKrs)){
          //echo 'Tambah--'.$i.'. '.$sempId.'--'.$itemSemesterMhsAktif->Nim.'--'.$semRegister.'--'.date('Y-m-d')."<br>";
          //$i++;

          //Masukkan data dalam tabel KRS
          $krs = new Krs;          
          $krs->krsSempId       = $sempId;
          $krs->krsNim          = $itemSemesterMhsAktif->Nim;
          $krs->krsPaketSem     = $semRegister;
          $krs->krsTglTransaksi = date('Y-m-d');
          $krs->save();
        }/*else{
          echo 'Sama--'.$i.'. '.
          $sempId.'.--'.
          $itemSemesterMhsAktif->Nim.'--'.
          $cariPaketSemMax->krsPaketSem.'--'.date('Y-m-d')."<br>";
          $i++;


        }*/
        
         

      } //akhir foreach

      $messages = 'Registrasi kelas semester Berhasil.';
     //session()->flash('success',$messages;

      //session()->flash('success','Hey, You have a message to read');
    }//akhir jika $result ada
    //
    
    //kirim message
    session()->flash('success',isset($messages) ? $messages : '');
    session()->flash('error',isset($messagesError) ? $messagesError : '');

    return redirect()->route('kelas.mhsregister');

  }

  public function showKelasRegister(Request $request)
  {
    $dataKelas = Kelas::select(DB::raw("sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurSemester,klsId, klsNama"))   
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')             
        ->where('sempSemId','=',$request->session()->get('semId')) 
        ->orderBy(DB::raw("mkkurSemester, klsNama, mkkurKode")) 
        ->get();

    $listProdi = Prodi::all();
    return view('admin.dashboard.kelas.kelasregister')
                ->with('kelas', $dataKelas)
                ->with('listProdi', $listProdi)
                ->with('prodi_terpilih', '');

  }

  public function kelasRegister(Request $request)
  {
    $input =$request->all();
    $prodiKode = $input['prodiKode'];

    //echo $prodiKode.$request->session()->get('semId');

    //mhs angkatan & kurikulum
    $kurikulum = Mahasiswa::select(DB::raw('mhsAngkatan,mhsKurId, krsPaketSem, sempSemId, sempId'))
      ->join('krs','krsNim','=','mhsNim')
      ->join('semester_prodi','sempId','=','krsSempId')
      ->where('mhsStatusAktif','=','1')
      ->where('mhsProdiKode','=',$prodiKode)
      ->where('sempIsAktif','=','1')
      ->where('sempSemId','=',$request->session()->get('semId'))
      ->groupBy('mhsAngkatan')
      ->orderBy('mhsAngkatan')
      ->get();

    //if(!empty($kurikulum ) ){print_r( $kurikulum);}
    //setiap angkatan mahasiswa aktif akan dicek / dimasukkan ke tabel kelas sesuai semester aktif
    //apakah pernah ada yang sudah mengambil
    if ($kurikulum->count()) {
      //akan disekskusi berdasarkan angkatan  
      foreach($kurikulum as $itemKurikulum){    

        //Cari Kelompok untuk tiap angkatan      
        $cariKelompok = Mahasiswa::select(DB::raw('mhsKelompok'))            
          ->where('mhsAngkatan','=',$itemKurikulum->mhsAngkatan)          
          ->orderBy('mhsKelompok')
          ->groupBy('mhsKelompok')
          ->get();
        //dd($cariKelompok);
        foreach($cariKelompok as $itemCariKelompok){
          //echo $itemKurikulum->mhsAngkatan.'--->'.$itemKurikulum->mhsKurId;
          //akses matakuliah pada paket semester 
          $namaKelompok = $itemCariKelompok->mhsKelompok;
          $makulAktif = Kurikulum::select(DB::raw('mkKurId, mkkurKode, mkkurNama'))
            ->join('matakuliah_kurikulum','kurId','=','mkkurKurId')
            ->join('program_studi','kurProdiKode','=','prodiKode')
            ->where('kurId','=',$itemKurikulum->mhsKurId)
            ->where('kurProdiKode','=',$prodiKode)
            ->where('mkkurSemester','=',$itemKurikulum->krsPaketSem)
            ->orderBy('mkkurKode')
            ->get();
          //dd($makulAktif);
          //dicek apakah makul ini sudah ada dalam kelas pada semester ini
          foreach ($makulAktif as $itemMakulAktif) {
            $cekMakulKelas = Kelas::Select(Db::raw('klsId'))
              ->where('klsMkKurId','=',$itemMakulAktif->mkKurId)
              ->where('klsSempId','=',$itemKurikulum->sempId)
              ->where('klsNama','=',$namaKelompok)
              ->get();
            //dd($cekMakulKelas);
            if($cekMakulKelas->isEmpty()){
              //echo 'Nama kelompok '.$namaKelompok.'-'.$itemKurikulum->sempId.'-'.$itemMakulAktif->mkKurId.'-<br>';
              //$klsId = DB::table('kelas')->max('klsId')+1;
              //masukkan ke tabel Kelas.........disini
              $kelas = new Kelas;
              //$kelas->klsId      = $klsId;
              $kelas->klsMkKurId = $itemMakulAktif->mkKurId;
              $kelas->klsSempId  = $itemKurikulum->sempId;
              $kelas->klsNama    = $namaKelompok;

              $kelas->save();

              $messages = 'Sukses! Registrasi kelas semester Berhasil.';
            }else{
              $messagesError = 'Whops! Terdeteksi kelas pada semester aktif dan prodi ini telah terdaftar.';
            }
          }
        }
         

      } //akhir foreach

      
     //session()->flash('success',$messages;

      //session()->flash('success','Hey, You have a message to read');
    }//akhir jika $result ada
    //
    
    //kirim message
    session()->flash('success',isset($messages) ? $messages : '');
    session()->flash('error',isset($messagesError) ? $messagesError : '');
    $dataKelas = Kelas::select(DB::raw("sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, klsId, klsNama, mkkurSemester"))   
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')        
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId') 
        ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')             
        ->where('semester_prodi.semProdiKode','=',$prodiKode)
        ->where('sempSemId','=',$request->session()->get('semId')) 
        ->orderBy(DB::raw("mkkurSemester, klsNama, mkkurKode")) 
        ->get();
        
    $listProdi = Prodi::all();    
    //$data = array('kelas' => $dataKelas);   
    return view('admin.dashboard.kelas.kelasregister')
            ->with('kelas', $dataKelas)
            ->with('listProdi', $listProdi)
            ->with('prodi_terpilih', $prodiKode);
            //->with( session()->flash('success',$messages)  );
                  
  }

  public function registerPesertaKelas(Request $request){
    //Cari data yang menggunakan
    
    $dataKrs = Krs::select(DB::raw("sempSemId, krsId, mhsKelompok, krsPaketSem, semProdiKode"))   
      ->join('semester_prodi', 'sempId', '=', 'krs.krsSempId')                
      ->join('mahasiswa', 'mhsNim', '=', 'krsNim')                
      ->where('sempSemId','=',$request->session()->get('semId'))                   
      ->orderBy(DB::raw("krsId")) 
      ->get();
    foreach ($dataKrs as $itemDataKrs) {
      $dataKelas = Kelas::select(DB::raw("sempSemId, klsId, semProdiKode, klsNama"))   
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId') 
        ->join('matakuliah_kurikulum','mkKurId','=','klsMkKurId')
        ->where('sempSemId','=',$request->session()->get('semId'))
        ->where('mkkurSemester','=',$itemDataKrs->krsPaketSem) // data kelas disesuaikan dengan semester yang diambil mhs
        ->where('klsNama','=',$itemDataKrs->mhsKelompok) //karena dosen tiap kelas/kelompok dimungkinkan berbeda 
        ->where('semProdiKode','=',$itemDataKrs->semProdiKode)    
        ->orderBy(DB::raw("klsId")) 
        ->get();
      foreach ($dataKelas as $itemDataKelas) {
     
        //echo "SemId--> ".$itemDataKrs->sempSemId." krsId--> ".$itemDataKrs->krsId." klsId--> ".$itemDataKelas->klsId."<br>";
        //cek apakah sdh ada antara kombinasi krsId dan klsId dalam krsDetail ?
        $cekKrsDetail=  KrsDetail::select(DB::raw("krsdtId"))                        
          ->where('krsdtKrsId','=',$itemDataKrs->krsId) 
          ->where('krsKlsId','=',$itemDataKelas->klsId) 
          ->first();

        if(empty($cekKrsDetail)){
          $KrsDetail = new KrsDetail;
          $KrsDetail->krsdtKrsId  = $itemDataKrs->krsId; //KRS
          $KrsDetail->krsKlsId  = $itemDataKelas->klsId; //KELAS
          
          $KrsDetail->save();

          $messages = "Sukses. Registrasi Peserta Kelas Berhasil.";
        }else{
          $messagesError = "Terdeteksi!! Registrasi Peserta Kelas Sudah ada.";
        }
      } //akhir foreach dataKelas

    } //akhir foreach dataKrs

    session()->flash('success',isset($messages) ? $messages : '');
    session()->flash('error',isset($messagesError) ? $messagesError : '');

    return redirect()->route('kelas');
  }

  public function peserta($id)
  {
    $dataKelas = Kelas::select(DB::raw("mhsNim, mhsNama, sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, klsId, klsNama, krsnaNilaiTugas,krsnaNilaiUTS,krsnaNilaiUAS,krsdtBobotNilai,krsdtKodeNilai"))   
        ->join('krs_detail', 'klsId', '=', 'krs_detail.krsKlsId')
        ->leftjoin('krs_nilai','krsnaKrsDtId','=','krsdtId')
        ->join('krs', 'krsId', '=', 'krs_detail.krsdtKrsId')
        ->join('mahasiswa', 'mhsNim', '=', 'krs.krsNim')
        ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')
             
        ->where('krs_detail.krsKlsId','=', $id)
        ->orderBy(DB::raw("mhsNim"))          
        ->get();
    
    //print_r($dataKelas);
    $data = array('kelaspeserta' => $dataKelas);   
    return view('admin.dashboard.kelas.kelaspeserta',$data);
  }

  public function tambahDosenMatakuliah(Request $request){
    $messages = [
            'klsId.required'    => 'Kelas Id dibutuhkan.',            
            'dsnNidn_terpilih.required'    => 'Belum ada dosen yang terpilih.',
        ];      

    $validator = Validator::make($request->all(), [
            'klsId' => 'required',
            'dsnNidn_terpilih' => 'required',
            
        ], $messages);
 
    if ($validator->fails()) {
        $this->throwValidationException(
            $request, $validator
        );
        return Response::json( array('errors' => $validator->errors()->toArray()),422);
    }

    //$this->insertAccountMahasiswa($request->all());

    
    $input = $request->all();
    //$dataDosen
    $cekKlsId = Kelas::findOrFail($input['klsId']);
    if($cekKlsId){
      DB::table('kelas')
            ->where('klsId', $input['klsId'])            
            ->update(['klsDsnNidn' => $input['dsnNidn_terpilih']]);
    }
    return response()->json($request->all(),200);
    //return redirect()->back();

  }

  public function showKelasInputNilai(Request $request)
  {
    
    if(!empty($prodi_terpilih = $request->get('prodiKode'))){
      $prodi_terpilih = $request->get('prodiKode');  
    
      $dataKelas = Kelas::select(DB::raw("sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurSemester,klsId, klsNama"))   
          ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
          ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
          ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')             
          ->where('sempSemId','=',$request->session()->get('semId'))  
          ->where('semProdiKode','=',$prodi_terpilih)       
          ->orderBy(DB::raw("mkkurSemester, klsNama, mkkurKode")) 
          ->get();
    }else {

      $prodi_terpilih = '';

      $dataKelas = Kelas::select(DB::raw("sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurSemester,klsId, klsNama"))   
          ->join('semester_prodi', 'sempId', '=', 'kelas.klsSempId')
          ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
          ->leftjoin('dosen', 'dosen.dsnNidn', '=', 'kelas.klsDsnNidn')             
          ->where('sempSemId','=',$request->session()->get('semId'))         
          ->orderBy(DB::raw("mkkurSemester, klsNama, mkkurKode")) 
          ->get();
    }

    $listProdi = Prodi::all();
    return view('admin.dashboard.nilai.inputnilai')
                ->with('kelas', $dataKelas)
                ->with('listProdi', $listProdi)
                ->with('prodi_terpilih', $prodi_terpilih);

  }

}

