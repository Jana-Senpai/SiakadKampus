<?php

namespace App\Http\Controllers\RoleDosen;

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
use App\Dosen as Dosen;
use App\Prodi as Prodi;
use App\KrsDetail as KrsDetail;
use App\User as User;
//use App\Jurusan as Jurusan;

class RoleDosenController extends Controller
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
  public function kelasSemester(Request $request)
  {
    /*select dsnNip, dsnNidn, mkkurKode, mkkurNama, mkkurSemester from dosen 
      inner join kelas on klsDsnNidn=dsnNidn
      inner join semester_prodi on sempId=klsSempId
      inner join matakuliah_kurikulum on mkkurId=klsMkKurId
      where sempSemId='20152' and klsDsnNidn=10020109*/


    $semId = $request->get('semId');
    //dd($semId);
    //registrasikan ke session
    $request->session()->set('semIdDsn', $semId);

    //$mhsNim = Auth::user()->username;
    $dsnNidn = $request->session()->get('dsnNidn');

//dd($mhsNim);

    $dataAmpuSmt = Dosen::select(DB::raw("dsnNip, dsnNidn, mkkurKode, mkkurNama, mkkurSemester, mkkurJumlahSks, klsNama, klsId"))
        ->join('kelas', 'klsDsnNidn', '=', 'dsnNidn')
        ->join('semester_prodi','sempId','=','klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')        
        ->where('sempSemId','=',$semId)
        ->where('dsnNidn','=',$dsnNidn)                
        ->get();
    
    $listSmtDsn = $this->cariSmtDsn($dsnNidn);
    //return redirect()->back()->with('dataAmpuSmt',$dataAmpuSmt);
    //populate data    
    $data = array('dataAmpuSmt' => $dataAmpuSmt,
                  'listSmtDsn' => $listSmtDsn,
                  'SmtDsn_terpilih' => $semId); 
    //dd($data);
    return view('admin.dashboard.index.maindosen',$data);
  }

  public function pesertaKelasSemester(Request $request, $klsId  )
  {
    /*select mhsNim, mhsNama, mkkurNama, mkkurKode 
      from dosen 
      inner join kelas on klsDsnNidn=dsnNidn
      inner join semester_prodi on sempId=klsSempId
      inner join matakuliah_kurikulum on mkkurId=klsMkKurId
      inner join krs_detail on krsKlsId=klsId
      inner join krs on krsdtKrsId=krsId
      inner join mahasiswa on krsNim=mhsNim
      where sempSemId='20151' and klsDsnNidn=10020109*/


    $semIdDsn = $request->session()->get('semIdDsn');
    //registrasikan ke session
    //$request->session()->set('semIdDsn', $semId);

    //$mhsNim = Auth::user()->username;
    $dsnNidn = $request->session()->get('dsnNidn');

//dd($klsNama);

    $dataPesertaSmt = Dosen::select(DB::raw("mhsNim, mhsNama, sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, klsId, klsNama, krsnaNilaiTugas,krsnaNilaiUTS,krsnaNilaiUAS,krsdtBobotNilai,krsdtKodeNilai"))
        ->join('kelas', 'klsDsnNidn','=','dsnNidn')
        ->join('semester_prodi','sempId','=','klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->join('krs_detail','krsKlsId','=','klsId')        
        ->join('krs','krsId','=','krsdtKrsId')        
        ->join('mahasiswa','mhsNim','=','krsNim')
        ->leftjoin('krs_nilai','krsdtId','=','krsnaKrsDtId')
        ->where('sempSemId','=',$semIdDsn)
        ->where('dsnNidn','=',$dsnNidn)
        ->where('klsId','=',$klsId)                
        ->get();
/*echo $semId.$dsnNidn.$klsId;    
    dd($dataPesertaSmt);*/
    $listSmtDsn = $this->cariSmtDsn($dsnNidn);
    //return redirect()->back()->with('dataAmpuSmt',$dataAmpuSmt);
    //populate data    
    $data = array('kelaspeserta' => $dataPesertaSmt,
                  'listSmtDsn' => $listSmtDsn,
                  'SmtDsn_terpilih' => $semIdDsn); 
    //dd($data);
    return view('dosen.pesertakelassemester',$data);
  }

  //Cari Semester yang pernah dilalui oleh dosen tertentu
  protected function cariSmtDsn($dsnNidn){
    $listSmtDsn = Dosen::select(DB::raw('distinct(sempSemId) as sempSemId, semKeterangan'))
        ->join('kelas','kelas.klsDsnNidn','=','dsnNidn')
        ->join('semester_prodi','semester_prodi.sempId','=','kelas.klsSempId')
        ->join('semester','semester.semId','=','sempSemId')
        ->where('dsnNidn','=',$dsnNidn)
        ->get();
  //dd($listSmtMhs);
    return $listSmtDsn;
  }

  protected function formResetPasswordDosen(){

    return view('dosen.resetpassworddosen');
  }

  protected function resetPasswordDosen(Request $request){
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
        $message = array("Validasi username tidak sesuai dengan yang ada dalam database!");
        return Redirect::back()->withErrors($message)->withInput(); 
      }
    }
    return redirect()->route('kelassemester');
  }

  protected function validator(array $data)
  {
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

  protected function cekIdUsername($dsnUsername){
    //Jika password lama akan dicek dengan yang didalam
    //
    //$passLama = bcrypt($passwordlama);

    $cekPass = User::Select(DB::raw('id'))
      ->where('username','=',$dsnUsername)
      //->where('password','=',$passLama)
      ->first();
    //echo $passLama.$mhsNim;
    //dd($cekPass);

    return $cekPass;
  }

  protected function nilaiSemesterCetak(Request $request,$klsId,$idCetak){

    //cari semId pada session
    $semIdDsn = $request->session()->get('semIdDsn');
    //dd($semIdDsn);
    if(!empty($semIdDsn)){
      $dsnNidn = $request->session()->get('dsnNidn');

  //dd($mhsNim);

      $dataNilaiSmt = Dosen::select(DB::raw("mhsNim, mhsNama, sempSemId, dsnNidn, dsnNip, dsnNama, mkkurKode, mkkurNama, mkkurJumlahSks, mkkurSemester, klsId, klsNama, krsnaNilaiTugas,krsnaNilaiUTS,krsnaNilaiUAS,krsdtBobotNilai,krsdtKodeNilai"))
        ->join('kelas', 'klsDsnNidn','=','dsnNidn')
        ->join('semester_prodi','sempId','=','klsSempId')
        ->join('matakuliah_kurikulum','mkkurId','=','klsMkKurId')
        ->join('krs_detail','krsKlsId','=','klsId')        
        ->join('krs','krsId','=','krsdtKrsId')        
        ->join('mahasiswa','mhsNim','=','krsNim')
        ->leftjoin('krs_nilai','krsdtId','=','krsnaKrsDtId')
        ->where('sempSemId','=',$semIdDsn)
        ->where('dsnNidn','=',$dsnNidn)
        ->where('klsId','=',$klsId)                
        ->get();
      //dd($dataNilaiSmt);
      //cari semester untuk dsn tertentu
      //$listSmtMhs = $this->cariSmtDsn($dsnNidn);
      // dd($listSmtMhs);
      //$nilaiIPK = $this->cariIpkIPs($mhsNim, $semId);
      //dd($nilaiIPK);
      //populate data    
      $data = array('kelaspeserta' => $dataNilaiSmt,
                    //'listSmtMhs' => $listSmtMhs,
                    'SmtMhs_terpilih' => $semIdDsn,
                    //'IPK' => $nilaiIPK['nilaiIPK'],
                    //'IPS' => $nilaiIPK['nilaiIPS'],
                    'semId' => $semIdDsn); 
      //dd($data);
      //return view('admin.dashboard.index.mainmhs',$data);
      $pdf = PDF::loadView('dosen.cetaknilaipesertakelas',$data);
      if($idCetak==1)
        return $pdf->download('Nilai '.$dataNilaiSmt[0]['mkkurNama'].'( '.$dataNilaiSmt[0]['mkkurKode'].').pdf');
      else
        return $pdf->stream('Nilai '.$dataNilaiSmt[0]['mkkurNama'].'( '.$dataNilaiSmt[0]['mkkurKode'].').pdf');
    }else{
      $messages = "Pastikan sudah memilih Tahun Akademik dan Kelas untuk mencetak.";
      session()->flash('error',isset($messages) ? $messages : '');
      return redirect()->back();
    }
  }
  
}

