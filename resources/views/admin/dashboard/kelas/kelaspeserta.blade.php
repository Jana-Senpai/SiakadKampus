@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Kelas Peserta</li>
           
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Setting Semester Aktif</h3>
                  
                </div><!-- /.box-header -->
                <div class="box-header">
                  <h3 class="box-title">@if( ! empty($kelaspeserta[0]['mkkurKode'])) {{$kelaspeserta[0]['mkkurKode'].' - '.$kelaspeserta[0]['mkkurNama'].' - Kelas '.$kelaspeserta[0]['klsNama'] }} @endif</h3>
                </div>
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
                <div class="box-body">
                  <form action="{{ URL::to('nilai/adminimportnilai') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <table class="table">
                    <tbody>  
                      <tr >
                        <td colspan="2" >
                        1. Gunakan Tombol "Download Peserta Kelas" untuk mendownload format file Upload.<br>
                        2. Upload file dalam ekstensi *.xlsx, *.xls, *.csv dan format sesuai dengan format file yang didownload.
                        </td>
                        
                      </tr>                     
                      <tr>
                        <td width="250px"><input class="btn " type="file" name="import_file" /></td>
                        <td align="left"><button type="submit" class="btn btn-primary">Import Nilai</button></td>     
                      </tr>                      
                    </tbody>
                    </table>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="klsId" value="{{ $kelaspeserta[0]['klsId'] }}">
                   </form>
                  
                </div>
                <div class="box-body"><a href="{{ URL::to('nilai/admindownloadpeserta/'.$kelaspeserta[0]['klsId'].'/xlsx') }}"><button class="btn btn-success">Download Peserta Kelas</button></a></div>
                


                <div class="box-body">
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>          
                        <th>NIM</th>            
                        <th>NAMA</th>                                                    
                        <th>Nilai Tugas</th>
                        <th>Nilai UTS</th>
                        <th>Nilai UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Nilai Huruf</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                    @if (! empty($kelaspeserta))
                     <?php $i=1;foreach ($kelaspeserta as $itemPeserta):  ?>
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$itemPeserta->mhsNim}}</td>
                        <td>{{$itemPeserta->mhsNama}}</td>
                        <td>{{$itemPeserta->krsnaNilaiTugas}}</td>
                        <td>{{$itemPeserta->krsnaNilaiUTS}}</td>
                        <td>{{$itemPeserta->krsnaNilaiUAS}}</td>
                        <td>{{$itemPeserta->krsdtBobotNilai}}</td>
                        <td>{{$itemPeserta->krsdtKodeNilai}}</td>
                      </tr>
                      <?php $i++; endforeach  ?>
                    @endif 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>No.</th>          
                        <th>NIM</th>            
                        <th>NAMA</th>                                                    
                        <th>Nilai Tugas</th>
                        <th>Nilai UTS</th>
                        <th>Nilai UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Nilai Huruf</th>
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

        $('#dataKurikulum').DataTable({"pageLength": 50});

      });

    </script>

@endsection

