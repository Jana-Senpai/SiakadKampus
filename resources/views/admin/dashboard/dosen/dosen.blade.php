@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Dosen</li>
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Dosen</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th>NIDN</th>
                        <th>NIP</th>                    
                        <th>Nama</th>                            
                        <th>Program Studi</th>                        
                        <th>Jurusan</th> 
                        <th>Aksi</th> 
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($dosen as $itemDosen):  ?>
                      <tr>
                        <td>{{$itemDosen->dsnNidn }}</td>
                        <td>{{$itemDosen->dsnNip}}</td>
                        <td>{{$itemDosen->dsnNama}}</td>
                        <td>{{$itemDosen->prodiNama}}</td> 
                        <td>{{$itemDosen->jurNama}}</td> 
                        <td><a href="{{{ URL::to('dosen/'.$itemDosen->dsnNidn.'/detail') }}}">
                              <span class="label label-info"><i class="fa fa-list"> Detail </i></span>
                              </a>
                        </td>
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>NIDN</th>
                        <th>NIP</th>                    
                        <th>Nama</th>                            
                        <th>Program Studi</th>                        
                        <th>Jurusan</th> 
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

        $('#dataKurikulum').DataTable({"pageLength": 10});

      });

    </script>

@endsection

