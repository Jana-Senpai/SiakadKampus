@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Herregistrasi</li>
           
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">

              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Herregistrasi Mahasiswa pada Semester Aktif</h3>
                               
                </div><!-- /.box-header -->
                
                <div class="box-body flash-message">
                  @if (session('success')) 
                  <div class="alert alert-info">
                    <strong>{{ session('success') }}</strong>
                  </div>
                  @elseif (session('error')) 
                  <div class="alert alert-error">
                    <strong>{{ session('error') }}</strong>
                  </div>
                  @endif      
                </div>
                
                <div class="box-header">                  
                      <form id="formKelasProdi" class="form-horizontal" role="form" method="POST" action="{{ action('Kelas\KelasController@kelasMahasiswaRegister')}}");>
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group">
                          <div class="col-md-12" align="right">
                         
                              <button type="submit" class="btn btn-danger" id="button-reg">
                                  REGISTRASIKAN MAHASISWA SEMESTER AKTIF
                              </button>
                          </div>
                         
                      </div>
                      </form>
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="dataHerregistrasi" class="table table-bordered table-hover">
                    <thead>
                      <tr>           
                        <th>Thn Semester</th>            
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Angkatan</th>
                        <th>Prodi</th> 
                        <th>Kelas</th>                                                                          
                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($mahasiswa as $itemKelas):  ?>
                      <tr>
                        <td>{{$itemKelas->sempSemId}}</td>                        
                        <td>{{$itemKelas->Nim}}</td>
                        <td>{{$itemKelas->mhsNama}}</td>
                        <td><font size="3"><span class="{{{ (($itemKelas->mhsAngkatan % 2)==0) ?'label label-warning' : 'label label-success' }}}">{{$itemKelas->mhsAngkatan}}</span></font>
                        </td>
                        <td>{{$itemKelas->prodiNama}}</td>                        
                        <td><font size="4"><span class="{{{($itemKelas->mhsKelompok=='A') ? 'label label-info' : 'label label-warning'}}}">{{$itemKelas->mhsKelompok}}</span></font></td>
                              
                       
                        
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                                
                        <th>Thn Semester</th>            
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Angkatan</th>
                        <th>Prodi</th> 
                        
                                                                          
                        
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

        $('#dataHerregistrasi').DataTable({"pageLength": 25});

      });

    </script>

@endsection

