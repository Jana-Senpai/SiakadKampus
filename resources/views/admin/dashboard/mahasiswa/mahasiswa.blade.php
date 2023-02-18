@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Mahasiswa</li>
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Master Mahasiswa</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="dataMahasiswa" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th>NIM</th>
                        <th>NAMA</th>
                        <th>Kelompok</th>
                        <th>Prodi</th>                    
                        <th>Jurusan</th>                            
                        <th>Kurikulum </th>  
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($mahasiswa as $itemMahasiswa):  ?>
                      <tr>
                        <td>{{$itemMahasiswa->Nim}}</td>
                        <td>{{$itemMahasiswa->mhsNama}}</td>
                        <td>{{$itemMahasiswa->mhsKelompok}}</td>
                        <td>{{$itemMahasiswa->prodiNama}}</td>
                        <td>{{$itemMahasiswa->jurNama}}</td>                       
                        <td>{{$itemMahasiswa->kurNama}}</td>                        
                        <td><a href="{{{ URL::to('mahasiswa/'.$itemMahasiswa->Nim.'/detail') }}}">
                              <span class="label label-info"><i class="fa fa-list"> Detail </i></span>
                              </a></td>
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>NIM</th>
                        <th>NAMA</th>
                        <th>Kelompok</th>
                        <th>Prodi</th>                    
                        <th>Jurusan</th>                            
                        <th>Kurikulum </th>  
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

        $('#dataMahasiswa').DataTable({"pageLength": 10});

      });

    </script>

@endsection

