<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
//use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Click as Click;
use App\View as View;
use App\Kompensasi as Kompensasi;
use App\Mangkir as Mangkir;
use App\Presensi as Presensi;
use App\Jurusan as Jurusan;
use App\Prodi as Prodi;
use App\Semester as Semester;
use App\Mahasiswa as Mahasiswa;
use App\Dosen as Dosen;


class AdminController extends Controller
{

    public function __construct(Request $request)
    {
      $this->middleware('auth');
      $this->semesterAktif($request);
    }

    public function semesterAktif(Request $request)
    {
      //cek_semester_aktif
      $semAktif = Semester::select(DB::raw('semId'))   
        ->where('semStatus','1')
        ->first();
        
      $request->session()->set('semId', $semAktif->semId); 
    }
        /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    
    public function index(Request $request){
      $level = Auth::user()->level;

      switch ($level) {
        case "1":
            return $this->dashboardLevel1(); //Admin
            break;
        case "2":            
            return $this->dashboardLevel2($request); //Dosen
            break;
        case "3":
            return $this->dashboardLevel3(); //Mahasiswa
            break;
        default:
            echo "Dashboard Larasia!";
      }
    }

    protected function dashBoardLevel1(){


      return view('admin.dashboard.index.mainadmin');
    }

    protected function dashBoardLevel2(Request $request){
      $dsnNip = Auth::user()->username;
      $dsnNidn = Dosen::where('dsnNip','=',$dsnNip)->first();
      $request->session()->set('dsnNidn', $dsnNidn['dsnNidn']); 


      $listSmtDsn = Dosen::select(DB::raw('distinct(sempSemId) as sempSemId, semKeterangan'))
        ->join('kelas','kelas.klsDsnNidn','=','dsnNidn')
        ->join('semester_prodi','semester_prodi.sempId','=','kelas.klsSempId')
        ->join('semester','semester.semId','=','sempSemId')
        ->where('dsnNip','=',$dsnNip)
        ->get();
//dd($listSmtMhs);
      
      return view('admin.dashboard.index.maindosen')
        ->with('listSmtDsn', $listSmtDsn)
        ->with('SmtDsn_terpilih','');
    }

    public function dashBoardLevel3(){
      $mhsNim = Auth::user()->username;

      $listSmtMhs = Mahasiswa::select(DB::raw('sempSemId, semKeterangan'))
        ->join('krs','krs.krsNim','=','mhsNim')
        ->join('semester_prodi','semester_prodi.sempId','=','krs.krsSempId')
        ->join('semester','semester.semId','=','sempSemId')
        ->where('mhsNim','=',$mhsNim)
        ->get();
//dd($listSmtMhs);
      return view('admin.dashboard.index.mainmhs')
      //return view('mahasiswa.index.mainmhs')
        ->with('listSmtMhs', $listSmtMhs)        
        ->with('SmtMhs_terpilih','');
    }

    public function index2()
    {

      $tahun_komp = Presensi::select(DB::raw("presensiSemId as tahun_komp"))
        ->orderBy("presensiSemId")
        ->groupBy(DB::raw("presensiSemId"))
        ->get()->toArray();
       //cari jumlah
      //$jmlTahunKomp=1;
      foreach ($tahun_komp as $itemTahun_komp);
      //Jumlah pelangggaran disemester terakhir
      $jmlKomp_last_smt = Presensi::select(DB::raw("presensiSemId as Tahun,FORMAT(sum(presensiJmlAlpha),0,'de_DE') as Jumlah"))
        ->where('presensiSemId','=',(max($itemTahun_komp)))
        ->get()->first();

      //$jmlKomp_last_smt = $jmlKomp_last_smt->jml_last;

      //echo $jmlKomp_last_smt;

      $tahun_komp = array_column($tahun_komp, 'tahun_komp');

     
      /*SELECT presensiSemId, jurusan.jurKode, sum( presensiJmlAlpha ) AS Jumlah
      FROM `mahasiswa`
      INNER JOIN presensi ON mhsNim = presensiMhsNim
      INNER JOIN program_studi ON prodiKode = mhsProdiKode
      INNER JOIN jurusan ON jurKode = prodiKodeJurusan
      WHERE jurusan.jurKode =1
      GROUP BY presensi.presensiSemId
      ORDER BY presensiSemId*/
     
      $kompensasi = array();
      
      $jurusan = Jurusan::select(DB::raw("jurNama, jurKode"))      
        ->orderBy(DB::raw("jurKode"))        
        ->get()->toArray();

      foreach ($jurusan as $dataJurusan) {
        $i = $dataJurusan['jurKode'];
        $namaJur = $dataJurusan['jurNama'];
        ${'dataTab'.$i} = Presensi::select(DB::raw("sum(presensiJmlAlpha) as Jumlah"))
        ->join('mahasiswa', 'mahasiswa.mhsNim', '=', 'presensiMhsNim')
        ->join('program_studi', 'program_studi.prodiKode', '=', 'mhsProdiKode')
        ->join('jurusan', 'jurKode', '=', 'prodiKodeJurusan')
        ->where('jurusan.jurKode', '=', $i)
        ->groupBy(DB::raw("presensiSemId"))
        ->orderBy(DB::raw("presensiSemId"))        
        ->get()->toArray();
                      
        ${'dataJur'.$i}['name'] = $namaJur;
        foreach (${'dataTab'.$i} as ${'data'.$i}) {
          //echo ${'data'.$i}['Jumlah'].",";
          ${'dataJur'.$i}['data'][]=${'data'.$i}['Jumlah'];
        }
        //echo "putaran-$i<br>";
        array_push($kompensasi,${'dataJur'.$i});
        
      }

      //kompensasi untuk chart pie jurusan
      $kompensasi_piejurusan = array();
      
      $dataTabPieJurusan = Presensi::select(DB::raw("jurNama as Nama, sum(presensiJmlAlpha) as Jumlah"))
        ->join('mahasiswa', 'mahasiswa.mhsNim', '=', 'presensiMhsNim')
        ->join('program_studi', 'program_studi.prodiKode', '=', 'mhsProdiKode')
        ->join('jurusan', 'jurKode', '=', 'prodiKodeJurusan')
        //->where('jurusan.jurKode', '=', $i)
        ->groupBy(DB::raw("jurusan.jurKode"))
        ->orderBy(DB::raw("jurusan.jurKode"))        
        ->get()->toArray();
      $x=1;     
      foreach ($dataTabPieJurusan as $TabPieJurusan) {
          
          //echo $TabPieJurusan['Nama'].'-'.$TabPieJurusan['Jumlah'].",";
          $dataPieJurusan['name'] = $TabPieJurusan['Nama'];;
          $dataPieJurusan['y']=$TabPieJurusan['Jumlah'];
          if($x==sizeof($TabPieJurusan)){
            //echo "masuk-$i";
            $dataPieJurusan['sliced']= true;
            $dataPieJurusan['selected'] = true;
          }
          array_push($kompensasi_piejurusan,$dataPieJurusan);
          $x++; 
          
      }

      //print json_encode($kompensasi_piejurusan, JSON_NUMERIC_CHECK);

      //create data kompensasi per-prodi
      $kompensasi_perprodi = array();
      $prodi = Prodi::select(DB::raw("prodiNama, prodiKode"))      
        ->orderBy(DB::raw("prodiKode"))        
        ->get()->toArray();

      foreach ($prodi as $dataProdi) {
        $i = $dataProdi['prodiKode'];        
        $namaJur = $dataProdi['prodiNama'];

        ${'dataTab'.$i} = Presensi::select(DB::raw("sum(presensiJmlAlpha) as Jumlah"))
        ->join('mahasiswa', 'mahasiswa.mhsNim', '=', 'presensiMhsNim')
        ->join('program_studi', 'program_studi.prodiKode', '=', 'mhsProdiKode')
        ->join('jurusan', 'jurKode', '=', 'prodiKodeJurusan')
        ->where('program_studi.prodiKode', '=', $i)
        ->groupBy(DB::raw("prodiKode"))
        ->orderBy(DB::raw("presensiSemId"))        
        ->get()->toArray();
                      
        ${'dataProdi'.$i}['name'] = $namaJur;
        foreach (${'dataTab'.$i} as ${'data'.$i}) {
          //echo ${'data'.$i}['Jumlah'].",";
          ${'dataProdi'.$i}['data'][]=${'data'.$i}['Jumlah'];
        }
        //echo "putaran--$i";
        array_push($kompensasi_perprodi,${'dataProdi'.$i});
      }
 
 
      //print json_encode($kompensasi_perprodi, JSON_NUMERIC_CHECK);


      //Create Data Mangkir
      
//      echo date("m");
      if(date("m") == 1){$tahunIni = date("Y")-1;$bulanIni = 12;}
      else{$tahunIni = date("Y");$bulanIni = date("m")-1;}

/*      echo $tahunIni;
      echo $bulanIni;*/

      $mangkir_blnlalu = Mangkir::select(DB::raw("sum( mkrJml ) AS JmlBlnLalu"))        
        ->where('mkrTahun', '=', $tahunIni) 
        ->where('mkrBln', '=',$bulanIni)
        ->groupBy(DB::raw("mkrTahun"))
        ->get()->first();

     
      //$mangkir_blnlalu = array_column($mangkir_blnlalu, 'mangkir_blnlalu');

      $tahun_mangkir = Mangkir::select(DB::raw("mkrTahun as tahun_mangkir"))
        ->orderBy("mkrTahun")
        ->groupBy(DB::raw("mkrTahun"))
        ->get()->toArray();
      $tahun_mangkir = array_column($tahun_mangkir, 'tahun_mangkir');

      //print_r($tahun_mangkir);
      $mangkir = array();
      //yang ini kenapa statis??? haha..iseng doang sih...
      for($i=1;$i<=5;$i++){
        if($i==1){
          $kelompok = 'A';
          $namaKel = 'STAF BAUK, BAK, PERPUSTAKAAN, UPT DAN PENGEMUDI';  
        }elseif($i==2){
          $kelompok = 'B';
          $namaKel = 'STAF LABORAN, TEKNIK, PBM DAN PLP';  
        }elseif($i==3){
          $kelompok = 'C';
          $namaKel = 'PRAMU KANTOR, TAMAN DAN PETUGAS PARKIR';  
        }elseif($i==4){
          $kelompok = 'D';
          $namaKel = 'SATUAN PENGAMAN';  
        }elseif($i==5){
          $kelompok = 'E';
          $namaKel = 'TENAGA KONTRAK';  
        }
        

        ${'dataTab'.$i} = Mangkir::select(DB::raw("mkrTahun as Tahun,pegKelompok as Kelompok, sum(spk_mangkir.mkrJml) as Jumlah"))
        ->join('spk_pegawai', 'spk_pegawai.pegKode', '=', 'spk_mangkir.mkrKodePegawai')
        ->where('spk_pegawai.pegKelompok', '=', $kelompok)
        ->groupBy(DB::raw("mkrTahun"))
        ->orderBy(DB::raw("spk_mangkir.mkrTahun, spk_pegawai.pegKelompok"))        
        ->get()->toArray();
        

        ${'dataKel'.$i}['name'] = $namaKel;
        foreach (${'dataTab'.$i} as ${'data'.$i}) {
         /* echo ${'data'.$i}['Tahun'].",";
          echo ${'data'.$i}['Kelompok'].",";
          echo ${'data'.$i}['Jumlah'].",";*/
          ${'dataKel'.$i}['data'][]=${'data'.$i}['Jumlah'];
        }
      
        array_push($mangkir,${'dataKel'.$i});
     /* array_push($kompensasi,$dataJur2);
      array_push($kompensasi,$dataJur3);
      array_push($kompensasi,$dataJur4);
      array_push($kompensasi,$dataJur5);*/
      }
      //print json_encode($mangkir, JSON_NUMERIC_CHECK);
     /* foreach ($data_pie as $item_pie) {       
         
          //echo $item_pie->alamat." Order by ".$item_pie->view."<br/>";
          $dataM[] = array_push("{name: '".$item_pie->alamat."', y: ".$item_pie->view."}");
        
      }*/

      //echo $dataM;
      //print_r($data_pie);
      //print_r(json_encode($data_pie,JSON_NUMERIC_CHECK));
      //$data_pie = array_column($dataM, 'data_pie');

      return view('admin.dashboard.main')
            /*->with('tahun',json_encode($tahun,JSON_UNESCAPED_UNICODE))
            ->with('viewer',json_encode($viewer,JSON_NUMERIC_CHECK))
            ->with('click',json_encode($click,JSON_NUMERIC_CHECK))
            ->with('datapie',json_encode($data_pie,JSON_NUMERIC_CHECK))*/
            ->with('jmlKomp_last_smt',$jmlKomp_last_smt)
            ->with('tahun_komp',json_encode($tahun_komp,JSON_UNESCAPED_UNICODE))
            ->with('kompensasi',json_encode($kompensasi,JSON_NUMERIC_CHECK))
            ->with('kompensasi_perprodi',json_encode($kompensasi_perprodi,JSON_NUMERIC_CHECK))
            ->with('kompensasi_piejurusan',json_encode($kompensasi_piejurusan,JSON_NUMERIC_CHECK))
            ->with('mangkir_blnlalu', $mangkir_blnlalu)
            ->with('tahun_mangkir',json_encode($tahun_mangkir,JSON_UNESCAPED_UNICODE))
            ->with('mangkir',json_encode($mangkir,JSON_NUMERIC_CHECK));
    }

    public function highchart()
    {
      $viewer = View::select(DB::raw("SUM(numberofview) as count"))
        ->orderBy("created_at")
        ->groupBy(DB::raw("year(created_at)"))
        ->get()->toArray();
      $viewer = array_column($viewer, 'count');

      $click = Click::select(DB::raw("SUM(numberofclick) as count"))
        ->orderBy("created_at")
        ->groupBy(DB::raw("year(created_at)"))
        ->get()->toArray();
      $click = array_column($click, 'count');

      return view('main')
            ->with('viewer',json_encode($viewer,JSON_NUMERIC_CHECK))
            ->with('click',json_encode($click,JSON_NUMERIC_CHECK));
    }
}



/*<?php
$con = mysql_connect("localhost","root","");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("highcharts", $con);

$sth = mysql_query("SELECT revenue FROM projections_sample");
$rows = array();
$rows['name'] = 'Revenue';
while($r = mysql_fetch_array($sth)) {
    $rows['data'][] = $r['revenue'];
}

$sth = mysql_query("SELECT overhead FROM projections_sample");
$rows1 = array();
$rows1['name'] = 'Overhead';
while($rr = mysql_fetch_assoc($sth)) {
    $rows1['data'][] = $rr['overhead'];
}

$result = array();
array_push($result,$rows);
array_push($result,$rows1);

print json_encode($result, JSON_NUMERIC_CHECK);

mysql_close($con);*/