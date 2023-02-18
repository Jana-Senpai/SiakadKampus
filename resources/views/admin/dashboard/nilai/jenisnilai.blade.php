@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Jenis Nilai</li>
           
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-6">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Jenis Nilai & Range</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="dataNilai" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th align="center">Kode Nilai</th>
                        <th align="center">Range</th>                        
                        
                      </tr>
                    </thead>
                    <tbody>                    
                      <tr align="center">
                        <td><b>A</b></td>
                        <td>80 - 100</td>
                      </tr>
                      <tr align="center">
                        <td><b>B</b></td>
                        <td>70 - 79</td>
                      </tr> 
                      <tr align="center">
                        <td><b>C</b></td>
                        <td>60 - 69</td>
                      </tr> 
                      <tr align="center">
                        <td><b>D</b></td>
                        <td>40 - 59</td>
                      </tr> 
                      <tr align="center">
                        <td><b>E</b></td>
                        <td>0 - 39</td>
                      </tr> 
                    </tbody>
                    
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

        $('#dataNilai').DataTable({
          "searching": false,
          "ordering": true,
          "bInfo": false,
          //"pageLength": false,
          "paging":false});

      });

    </script>

@endsection

