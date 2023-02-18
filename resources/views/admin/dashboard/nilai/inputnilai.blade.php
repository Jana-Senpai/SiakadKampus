@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Kelas Prodi</li>
           
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">

              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Setting Semester Aktif</h3>
                  <hr>                  
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
                      <form id="formKelasProdi" class="form-horizontal" role="form" method="POST" action="{{ route('kelas.input.nilai')}}");>
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group">
                          <label class="col-md-4 control-label">Pilihan Prodi</label>
                          <div class="col-md-4 ">
                              <select class="form-control" name="prodiKode">
                                  @foreach ($listProdi as $itemProdi)
                                  <option value="{{$itemProdi->prodiKode}}" @if($itemProdi->prodiKode ==$prodi_terpilih) ? ' selected="selected"' : '' @endif > {{$itemProdi->prodiNama}}</option>
                                  @endforeach
                              </select>
                              
                              <small class="help-block"></small>
                          </div>                          
                          <div class="col-md-4">
                         
                              <button type="submit" class="btn btn-flat btn-social btn-dropbox" id="button-reg">
                                    <i class="fa  fa-hand-o-left"></i> Pilih
                              </button>
                          </div>
                         
                      </div>
                      </form>
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>            
                        <th>Thn Semester</th>            
                        <th>Semester</th>
                        <th>Kode Makul</th>
                        <th>Nama Makul</th>
                        <th>SKS</th>
                        <th>NIDN</th>                        
                        <th>Nama Dosen</th>
                        <th>Kelas</th>                            
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($kelas as $itemKelas):  ?>
                      <tr>
                        <td>{{$itemKelas->sempSemId}}</td>
                        <td><font size="3"><span class="{{{ (($itemKelas->mkkurSemester % 2)==0) ?'label label-warning' : 'label label-success' }}}">{{$itemKelas->mkkurSemester}}</span></font>
                        </td>
                        <td>{{$itemKelas->mkkurKode}}</td>
                        <td>{{$itemKelas->mkkurNama}}</td>
                        <td>{{$itemKelas->mkkurJumlahSks}}</td>
                        <td>{{$itemKelas->dsnNidn}}</td>
                        <td>@if(isset($itemKelas->dsnNama)) {{$itemKelas->dsnNama}} 
                        @else <a class="btn btn-warning btn-flat btn-sm" href="{{{route('kelas')}}}" title="Register"><i class="fa fa-plus"></i></a> 
                          @endif
                        </td>                                           
                        <td><font size="4"><span class="{{{($itemKelas->klsNama=='A') ? 'label label-info' : 'label label-warning'}}}">{{$itemKelas->klsNama}}</span></font></td>
                        <td><a href="{{{ URL::to('kelas/'.$itemKelas->klsId.'/peserta') }}}">
                              <span class="label label-success"><i class="fa fa-list"> Peserta </i></span>
                            </a>                            
                        </td>              
                       
                        
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Thn Semester</th> 
                        <th>Semester</th> 
                        <th>Kode Makul</th>
                        <th>Nama Makul</th>
                        <th>SKS</th>
                        <th>NIDN</th>                        
                        <th>Nama Dosen</th>                            
                        <th>Kelas</th> 
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
    
   <script type="text/javascript">
      $(function () {

        $('#dataKurikulum').DataTable({"pageLength": 50});

      });

      
    </script>

@endsection

