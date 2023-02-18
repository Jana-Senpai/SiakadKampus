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
            <li class="active">Matakuliah</li>
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Matakuliah Kurikulum</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="dataMakulKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th>Kode Matakuliah</th>
                        <th>Nama</th>                        
                        <th>Kurikulum </th>
                        <th>SKS</th>
                        <th>Teori</th>
                        <th>Praktek</th>                    
                        <th>Prodi</th>                            
                        <th>Jurusan</th>                            
                       
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($kurikulum as $itemKurikulum):  ?>
                      <tr>
                        <td>{{$itemKurikulum->mkkurKode}}</td>
                        <td>{{$itemKurikulum->mkkurNama}}</td>
                        <td>{{$itemKurikulum->kurNama}}</td>
                        <td>{{$itemKurikulum->mkkurJumlahSks}}</td>                       
                        <td>{{$itemKurikulum->mkkurJumlahSksTeori}}</td>
                        <td>{{$itemKurikulum->mkkurJumlahSksPraktek}}</td>                
                        <td>{{$itemKurikulum->prodiNama}}</td>
                        <td>{{$itemKurikulum->jurNama}}</td>
                        
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Kode Matakuliah</th>
                        <th>Nama</th>                        
                        <th>Kurikulum </th>
                        <th>SKS</th>
                        <th>Teori</th>
                        <th>Praktek</th>                    
                        <th>Prodi</th>                            
                        <th>Jurusan</th>  
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

        $('#dataMakulKurikulum').DataTable({"pageLength": 50});

      });

    </script>

@endsection

