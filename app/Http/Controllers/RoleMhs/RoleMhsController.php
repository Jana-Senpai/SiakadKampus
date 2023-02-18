<?php

namespace App\Http\Controllers\RoleMhs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use DB;
use Auth;
use PDF;

use Validator;
use App\Http\Controllers\Controller;
use App\Mahasiswa as Mahasiswa;
use App\Prodi as Prodi;
use App\KrsDetail as KrsDetail;
use App\User as User;
//use App\Jurusan as Jurusan;

class RoleMhsController extends Controller
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
  public function nilaiSemester(Request $request)
  {
    /*SELECT sempSemId, mkkurKode, mkkurNama, dsnNama, mhsNim, mhsNama, mhsKelompok, krsdtBobotNilai, krsdtKodeNilai FROM krs_detail
    join kelas on klsId=krsKlsId
    left join dosen on kelas.klsDsnNidn=dsnNidn
    join matakuliah_kurikulum on mkkurId=klsMkKurId
    join krs on krsId=krsdtKrsId
    join semester_prodi on sempId=klsSempId
    join mahasiswa on mhsNim=krsNim

    where sempSemId=20152 and krsNim='M.09.15.01'*/


    $semId = $request->get('semId');
    //registrasikan ke session
    $request->session()->set('semIdMhs', $semId);

    $mhsNim = Auth::user()->username;

//dd($mhsNim);

    $dataNilaiSmt = KrsDetail::select(DB::raw("sempSemId, mkkurKode, mkkurNama, mkkurJumlahSks, dsnNama, mhsNim as Nim, mhsNama, mhsKelompok, krsdtBobotNilai, krsdtKodeNilai"))
        ->join('kelas', 'klsId', '=', 'krsKlsId')
        ->leftjoin('dosen','klsDsnNidn','=','dsnNidn')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->join('krs','krsId','=','krsdtKrsId')
        ->join('semester_prodi','sempId','=','klsSempId')
        ->join('mahasiswa','mhsNim','=','krsNim')
        ->where('sempSemId','=',$semId)
        ->where('krsNim','=',$mhsNim)                
        ->get();
    //dd($dataNilaiSmt);
    //cari semester untuk nim tertentu
    $listSmtMhs = $this->cariSmtMhs($mhsNim);
    // dd($listSmtMhs);
    $nilaiIPK = $this->cariIpkIPs($mhsNim, $semId);
    //dd($nilaiIPK);
    //populate data    
    $data = array('dataNilai' => $dataNilaiSmt,
                  'listSmtMhs' => $listSmtMhs,
                  'SmtMhs_terpilih' => $semId,
                  'IPK' => $nilaiIPK['nilaiIPK'],
                  'IPS' => $nilaiIPK['nilaiIPS']); 
    //dd($data);
    return view('admin.dashboard.index.mainmhs',$data);
  }

  //Cari Semester yang pernah dilalui oleh mahasiswa tertentu
  protected function cariSmtMhs($mhsNim){
    $listSmtMhs = Mahasiswa::select(DB::raw('sempSemId, semKeterangan'))
        ->join('krs','krs.krsNim','=','mhsNim')
        ->join('semester_prodi','semester_prodi.sempId','=','krs.krsSempId')
        ->join('semester','semester.semId','=','sempSemId')
        ->where('mhsNim','=',$mhsNim)
        ->get();
  //dd($listSmtMhs);
      return $listSmtMhs;
  }

  protected function cariIpkIPs($mhsNim, $semTerpilih){
    //$nilaiIpksIPs = $smt->nilaiMin;

    $nilaiIPK = $this->cariIPK($mhsNim, $semTerpilih);
    $nilaiIPS = $this->cariIPS($mhsNim, $semTerpilih);
    $dataIpkIps = array(
        'nilaiIPK' => $nilaiIPK,
        'nilaiIPS' => $nilaiIPS);
    
    return $dataIpkIps;
  }

  protected function cariIPK($mhsNim, $semTerpilih){
    $listSmtMhs = Mahasiswa::select(DB::raw('mhsNim as Nim, mhsNama, min(sempSemId) as MinSemId, max(sempSemId) as MaxSemId'))
        ->join('krs','krs.krsNim','=','mhsNim')
        ->join('semester_prodi','semester_prodi.sempId','=','krs.krsSempId')
        ->where('mhsNim','=',$mhsNim)
        ->first();
  //dd($listSmtMhs);
    $MinSemId = $listSmtMhs->MinSemId;
    //$MaxSemId = $listSmtMhs->MaxSemId;
    $MaxSemId = $semTerpilih;

    $sksIPK = KrsDetail::select(DB::raw("sum(mkkurJumlahSKS) as jmlSks, sum(kdnlBobot*mkkurJumlahSKS) as bobotKaliSks,  ROUND((sum(kdnlBobot*mkkurJumlahSKS) / sum(mkkurJumlahSKS)),2) as IPK, mhsNama, mhsNim as Nim "))
        ->join('kelas', 'klsId', '=', 'krsKlsId')
        ->leftjoin('dosen','klsDsnNidn','=','dsnNidn')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->join('krs','krsId','=','krsdtKrsId')
        ->join('semester_prodi','sempId','=','klsSempId')
        ->join('mahasiswa','mhsNim','=','krsNim')
        ->leftjoin('kode_nilai','kdnlId','=','krsdtKodeNilai')
        ->whereBetween('sempSemId',[$MinSemId,$MaxSemId])
        ->where('mhsNim','=',$mhsNim)
        ->get();

    return $sksIPK;
  }

  protected function cariIPS($mhsNim, $semTerpilih){
    /*$listSmtMhs = Mahasiswa::select(DB::raw('min(sempSemId) as MinSemId, max(sempSemId) as MaxSemId'))
        ->join('krs','krs.krsNim','=','mhsNim')
        ->join('semester_prodi','semester_prodi.sempId','=','krs.krsSempId')
        ->where('mhsNim','=',$mhsNim)
        ->first();*/
  //dd($listSmtMhs);
    //$MinSemId = $listSmtMhs->MinSemId;
    $MinSemId = $semTerpilih;
    //$MaxSemId = $listSmtMhs->MaxSemId;
    $MaxSemId = $semTerpilih;

    $sksIPS = KrsDetail::select(DB::raw("sum(mkkurJumlahSKS) as jmlSks, sum(kdnlBobot*mkkurJumlahSKS) as bobotKaliSks,  ROUND((sum(kdnlBobot*mkkurJumlahSKS) / sum(mkkurJumlahSKS)),2) as IPS "))
        ->join('kelas', 'klsId', '=', 'krsKlsId')
        ->leftjoin('dosen','klsDsnNidn','=','dsnNidn')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->join('krs','krsId','=','krsdtKrsId')
        ->join('semester_prodi','sempId','=','klsSempId')
        ->join('mahasiswa','mhsNim','=','krsNim')
        ->leftjoin('kode_nilai','kdnlId','=','krsdtKodeNilai')
        ->whereBetween('sempSemId',[$MinSemId,$MaxSemId])
        ->where('mhsNim','=',$mhsNim)
        ->first();

    return $sksIPS;
  }

  protected function formResetPasswordMahasiswa(){

    return view('mahasiswa.resetmhspassword');
  }

  protected function resetPasswordMahasiswa(Request $request){
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      //dd($validator);
      return Redirect::back()->withErrors($validator)->withInput();
    }else{

      $cekIdUsername = $this->cekIdUsername(Auth::user()->username);

      if($cekIdUsername->count() > 0){
        //dd($cekPassLama->id);
        //Update data yang dimasukkan
        if(DB::table('users')
          ->where('username',Auth::user()->username)
          ->where('id', $cekIdUsername->id)
          ->update(['password' => bcrypt($request->password)]))
        {
          $messages = "Sukses! Perubahan password berhasil dilakukan.";
          session()->flash('success',isset($messages) ? $messages : '');
        }else{
          $messages = "Gagal! Perubahan password tidak berhasil dilakukan.";
          session()->flash('error',isset($messages) ? $messages : '');
        }
      }else{
        $message = array("Password lama tidak sama dengan yang ada dalam database!");
        return Redirect::back()->withErrors($message)->withInput(); 
      }
    }
    return redirect()->route('nilaisemester');
  }

  protected function validator(array $data)
  {
  	//dd($data['passwordlama']);
      $messages = [
          'passwordlama.required' => 'Password Lama dibutuhkan.',          
          'password.required'     => 'Password dibutuhkan.',
          'password.confirmed'    => 'Password dan Konfirmasi password tidak sama.',
          'password.min'          => 'Panjang password minimal 6 karakter',
          'passwordlama.cek_passwordlama' => 'Password lama yang dimasukkan tidak sesuai dalam database'
          
      ];
      return Validator::make($data, [
          'passwordlama'  => 'required|cek_passwordlama:' . \Auth::user()->password,
          'password'      => 'required|confirmed|min:6',          
      ], $messages);


  }

  protected function cekIdUsername($mhsNim){
    //Jika password lama akan dicek dengan yang didalam
    //
    //$passLama = bcrypt($passwordlama);

    $cekPass = User::Select(DB::raw('id'))
      ->where('username','=',$mhsNim)
      //->where('password','=',$passLama)
      ->first();
    //echo $passLama.$mhsNim;
    //dd($cekPass);

    return $cekPass;
  }

  protected function nilaiSemesterCetak(Request $request,$id){

    //cari semId pada session
    $semId = $request->session()->get('semIdMhs');
    if(!empty($semId)){
      $mhsNim = Auth::user()->username;

  //dd($mhsNim);

      $dataNilaiSmt = KrsDetail::select(DB::raw("sempSemId, mkkurKode, mkkurNama, mkkurJumlahSks, dsnNama, mhsNim as Nim, mhsNama, mhsKelompok, krsdtBobotNilai, krsdtKodeNilai"))
          ->join('kelas', 'klsId', '=', 'krsKlsId')
          ->leftjoin('dosen','klsDsnNidn','=','dsnNidn')
          ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
          ->join('krs','krsId','=','krsdtKrsId')
          ->join('semester_prodi','sempId','=','klsSempId')
          ->join('mahasiswa','mhsNim','=','krsNim')
          ->where('sempSemId','=',$semId)
          ->where('krsNim','=',$mhsNim)                
          ->get();
      //dd($dataNilaiSmt);
      //cari semester untuk nim tertentu
      $listSmtMhs = $this->cariSmtMhs($mhsNim);
      // dd($listSmtMhs);
      $nilaiIPK = $this->cariIpkIPs($mhsNim, $semId);
      //dd($nilaiIPK);
      //populate data    
      $data = array('dataNilai' => $dataNilaiSmt,
                    'listSmtMhs' => $listSmtMhs,
                    'SmtMhs_terpilih' => $semId,
                    'IPK' => $nilaiIPK['nilaiIPK'],
                    'IPS' => $nilaiIPK['nilaiIPS'],
                    'semId' => $semId); 
      //dd($data);
      //return view('admin.dashboard.index.mainmhs',$data);
      $pdf = PDF::loadView('mahasiswa.cetakkhs',$data);
      if($id==1)
        return $pdf->download('Nilai.pdf');
      else
        return $pdf->stream('Nilai.pdf');
    }else{
      $messages = "Pilih Tahun Akademik dan tekan tombol 'Pilih' untuk mencetak.";
      session()->flash('error',isset($messages) ? $messages : '');
      return redirect()->back();
    }
  }
  
}

