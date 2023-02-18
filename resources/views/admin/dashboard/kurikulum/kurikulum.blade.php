@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Kurikulum</li>
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Kurikulum</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th>Kode Kurikulum</th>
                        <th>Prodi</th>                    
                        <th>Jurusan</th>                            
                        <th>Tahun </th>                        
                        <th>Nama </th>
                        <th>Sk Rektor</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($kurikulum as $itemKurikulum):  ?>
                      <tr>
                        <td>{{$itemKurikulum->kurId}}</td>
                        <td>{{$itemKurikulum->prodiNama}}</td>
                        <td>{{$itemKurikulum->jurNama}}</td>
                        <td>{{$itemKurikulum->kurTahun}}</td>                       
                        <td>{{$itemKurikulum->kurNama}}</td>
                        <td>{{$itemKurikulum->kurNoSkRektor}}</td>
                        <td><a href="{{{ URL::to('kurikulum/'.$itemKurikulum->kurId.'/detail') }}}">
                              <span class="label label-info"><i class="fa fa-list"> Detail </i></span>
                              </a></td>
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Kode Kurikulum</th>
                        <th>Prodi</th>                    
                        <th>Jurusan</th>                            
                        <th>Tahun </th>                        
                        <th>Nama </th>
                        <th>Sk Rektor</th>
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

