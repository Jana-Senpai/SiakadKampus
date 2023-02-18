@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Semester Prodi</li>
           
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Setting Semester Prodi Aktif</h3>
                </div><!-- /.box-header -->
                <div class="box-header">
                  <div class="col-md-12" align="right"> 
                    <a href="{{{ action('Semester\SemesterController@registerSemesterProdi') }}}" title="Registrasi Semua Prodi -> Semester Prodi" onclick="return confirm('Apakah anda yakin akan meregistrasikan semua Program Studi pada Semester Prodi sesuai dengan Semester Aktif ?' )">
                                                <span class="btn btn-info"><i class="fa fa-list"> Registrasikan Semua Program Studi </i></span>
                    </a>
                  </div>
                </div>
                <div class="box-body">
                  <table id="dataSemesterProdi" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th>Kode Semester</th>
                        <th>Prodi</th>
                        <th>Jurusan</th>
                        <th>Tgl Mulai KRS</th>                        
                        <th>Tgl Selesai KRS</th>
                        <th>Mulai Input Nilai</th>
                        <th>Selesai Input Nilai</th>    
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($semesterprodi as $itemSemesterProdi):  ?>
                      <tr>
                        <td>{{$itemSemesterProdi->semId}}</td>
                        <td>@if($itemSemesterProdi->sempIsAktif==1) 
                            <a href="{{{ URL::to('kelas/'.$itemSemesterProdi->prodiKode.'/prodi') }}}">
                              <span class="label label-info"><i class="fa fa-list"> {{$itemSemesterProdi->prodiNama}} </i></span>
                              </a>
                            @else 
                              {{$itemSemesterProdi->prodiNama}}
                            @endif
                        </td>
                        <td>{{$itemSemesterProdi->jurNama}}</td>
                        <td>{{$itemSemesterProdi->sempTglMulaiKrs}}</td>
                        <td>{{$itemSemesterProdi->sempTglSelesaiKrs}}</td>                                           
                        <td>{{$itemSemesterProdi->sempTglMulaiInputNilai}}</td>
                        <td>{{$itemSemesterProdi->sempTglSelesaiInputNilai}}</td>  
                        <td>@if($itemSemesterProdi->sempIsAktif==0) 
                               
                              <span class="label label-danger"><i class="fa fa-check"> Tidak Aktif </i></span>
                              
                            @else
                               <span class="label label-success"><i class="fa fa-check-circle"> Aktif </i></span>
                              </a>
    
                            @endif
                        </td>              
                       
                        
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Kode Semester</th>
                        <th>Prodi</th>
                        <th>Jurusan</th>
                        <th>Tgl Mulai KRS</th>                        
                        <th>Tgl Selesai KRS</th>                       
                        <th>Tipe (Ganjil/Genap)</th>
                        <th>Status</th>   
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->

@endsection
@section('script')

    <script src="{{ URL::asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
      $(function () {

        $('#dataSemesterProdi').DataTable({"pageLength": 50});

      });

    </script>

@endsection

