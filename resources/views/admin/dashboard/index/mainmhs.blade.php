@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Mahasiswa</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard Mahasiswa</li>
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Semester Aktif Mahasiswa </h3>
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
                      <form id="formKelasProdi" class="form-horizontal" role="form" method="POST" action="{{ route('nilaisemester')}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group">
                          <label class="col-md-4 control-label">Tahun Akademik</label>
                          <div class="col-md-4 ">
                              <select class="form-control" name="semId">
                                  @foreach ($listSmtMhs as $itemSmtMhs)
                                  <option value="{{$itemSmtMhs->sempSemId}}" @if($itemSmtMhs->sempSemId ==$SmtMhs_terpilih) ? ' selected="selected"' : '' @endif > {{$itemSmtMhs->semKeterangan}}</option>
                                  @endforeach
                              </select>
                              
                              <small class="help-block"></small>
                          </div>
                          <div class="form-group">
                              <div class="col-md-2 ">
                                  <button type="submit" class="btn btn-flat btn-social btn-dropbox" id="button-reg">
                                    <i class="fa  fa-hand-o-left"></i> Pilih
                                  </button>
                              </div>
                          </div>
                      </div>
                      </form>
                </div><!-- /.box-header -->
                <div class="box-header">                  
                    @if(isset($IPK))
                        @foreach($IPK as $itemIPK)
                            <table class="table table-bordered ">
                                <tbody><tr>                                  
                                  <th colspan="2" align="center">Nilai IPK</th>                                  
                                  <th colspan="2" align="center">Nilai IPS</th>
                                </tr>
                                <tr>
                                  
                                  <td>IPK</td>                                  
                                  <td><span class="badge bg-red"> @if($itemIPK->IPK > 0){{$itemIPK->IPK}} @else 0 @endif </span></td>

                                  <td>IPS</td>                                  
                                  <td><span class="badge bg-light-blue">@if($IPS->IPS > 0){{$IPS->IPS}} @else 0 @endif</span></td>
                                </tr>
                                <tr>
                                  <td>JML SKS IPK</td>                                 
                                  <td><span class="badge bg-yellow">@if($itemIPK->jmlSks > 0){{$itemIPK->jmlSks}} @else 0 @endif</span></td>

                                  <td>JML SKS IPS</td>                                 
                                  <td><span class="badge bg-green">@if($IPS->jmlSks > 0){{$IPS->jmlSks}} @else 0 @endif </span></td>
                                </tr>
                               
                              </tbody>
                            </table>
                           
                        @endforeach                        
                    @endif

                    
        
                </div><!-- /.box-header -->
                <div class="box-body col-md-11" align="right" >  
                                 
                  <a href="{{route('nilaisemestercetak',['id' => 1])}}" class="btn btn-flat btn-warning" value="Download Nilai Semester"><i class="fa fa-download"></i> Download</a>
                  <a href="{{route('nilaisemestercetak',['id' => 234])}}" class="btn btn-flat btn-info" value="Cetak Nilai Semester"><i class="fa fa-print"></i> Cetak</a>
                 
                </div>
                <div class="box-body" >
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>    
                        <th>No</th>                    
                        <th>Kode </th>
                        <th width="65%">Matakuliah</th>
                        <th>SKS </th>
                        <th>Nilai</th>                        
                      </tr>
                    </thead>
                    <tbody>
                    @if (! empty($dataNilai))
                     <?php $i=1;foreach ($dataNilai as $itemNilai):  ?>
                      <tr>
                        <td>{{$i}}</td>
                        <td>{{$itemNilai->mkkurKode}}</td>
                        <td>{{$itemNilai->mkkurNama}}</td>
                        <td>{{$itemNilai->mkkurJumlahSks}}</td>
                        <td>{{$itemNilai->krsdtKodeNilai}}</td>
                       
                      </tr>
                      <?php $i++; endforeach  ?>
                    @endif 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>No</th> 
                        <th>Kode </th>
                        <th>Matakuliah</th>
                        <th>SKS</th>
                        <th>Nilai</th>  
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

        $('#dataKurikulum').DataTable({"pageLength": 25});

      });

    </script>

@endsection