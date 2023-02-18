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
            <div class="col-md-12">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Data Mahasiswa</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                 <?php foreach ($mahasiswa as $itemMahasiswa);  ?>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-3">
                      <p align="center">
                        <img src="{{ URL::asset('admin/dist/img/user-160-nobody.jpg') }}" alt="User Image">
                        <a class="users-list-name" href="#">{{$itemMahasiswa->mhsNama}}</a>
                        <span class="users-list-date">{{$itemMahasiswa->Nim}}</span>
                      </p>
                    </div><!-- /.col -->
                    <div class="col-md-8">
                     <table id="dataKurikulum" class="table table-bordered table-hover">                    
                      <tbody>
                      
                        <tr>
                          <td>NIM</td>
                          <td>{{$itemMahasiswa->Nim}}</td>
                        </tr>
                        <tr>
                          <td>Nama</td>  
                          <td>{{$itemMahasiswa->mhsNama}}</td>
                        </tr>
                        <tr>
                          <td>Program Studi</td> 
                          <td>{{$itemMahasiswa->prodiNama}}</td>
                        </tr>                        
                        <tr>
                          <td>Jurusan</td> 
                          <td>{{$itemMahasiswa->jurNama}}</td>                        
                        </tr>
                         <tr>
                          <td>Kelompok Kelas</td> 
                          <td>{{$itemMahasiswa->mhsKelompok}}</td>                        
                        </tr>
                      </tbody>
                      
                    </table>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
                 
              </div>
            </div>
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

