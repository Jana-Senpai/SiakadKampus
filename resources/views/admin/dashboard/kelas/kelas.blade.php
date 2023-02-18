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
                  <h3 class="box-title">Konfigurasi Dosen Matakuliah Setting Semester Aktif</h3>
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
                      <form id="formKelasProdi" class="form-horizontal" role="form" method="POST" action="{{ action('Kelas\KelasController@kelasprodi')}}">
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
                          <div class="form-group">
                              <div class="col-md-4 ">
                                  <button type="submit" class="btn btn-primary" id="button-reg">
                                      Pilih
                                  </button>
                              </div>
                          </div>
                      </div>
                      </form>
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>            
                        <th>Thn Semester</th>            
                        <th>Kode Makul</th>
                        <th>Nama Makul</th>
                        <th>SKS</th>
                        <th>Semester</th>
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
                        <td>{{$itemKelas->mkkurKode}}</td>
                        <td>{{$itemKelas->mkkurNama}}</td>
                        <td>{{$itemKelas->mkkurJumlahSks}}</td>
                        <td>{{$itemKelas->mkkurSemester}}</td>
                        <td>{{$itemKelas->dsnNidn}}</td>
                        <td>@if(isset($itemKelas->dsnNama)) <a class="btn bg-maroon btn-flat btn-sm" onClick="tampilModal('{{$itemKelas->klsId}}','{{$itemKelas->mkkurKode}}','{{$itemKelas->mkkurNama}}')" title="Pilih Dosen"> {{$itemKelas->dsnNama}} </a>
                        @else <a class="btn btn-success btn-flat btn-sm" onClick="tampilModal('{{$itemKelas->klsId}}','{{$itemKelas->mkkurKode}}','{{$itemKelas->mkkurNama}}')" title="Pilih Dosen"><i class="fa fa-plus"></i></a> 
                          @endif</td>                                           
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
                        <th>Kode Makul</th>
                        <th>Nama Makul</th>
                        <th>SKS</th>
                        <th>Semester</th>
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

<!-- Modal -->
          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                          <h4 class="modal-title" id="myModalLabel">Pilih Dosen</h4>
                          <label class="label label-info label-lg">Matakuliah :</label>
                          
                          <input class="form-control input-sm" placeholder=".input-sm" type="text" id="kodemk" readonly="true">
                           
                      </div>
                      <div class="modal-body">           
                        <form id="formTambahDosenMakul" class="form-horizontal" role="form" method="POST" action="{{ url('/kelas/tambah/dosen/makul') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="klsId" id="klsId" value="">
                            <div class="box-body">
                              
                              <div class="form-group">
                                <table id="dataDosen" class="table table-bordered table-hover table-stripe">
                                  <thead>
                                    <tr>
                                      <th>NIDN</th>                        
                                      <th>Nama Dosen</th>                                        
                                      <th>Pilih</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                   @foreach ($dosen as $itemDosen)
                                    <tr>
                                      <td>{{$itemDosen->dsnNidn}}</td>
                                      <td>{{$itemDosen->dsnNama}}</td>                                       
                                      <td>
                                        <input type="radio" name="dsnNidn_terpilih" value="{{$itemDosen->dsnNidn}}"> 
                                      </td>       
                                    </tr>
                                    @endforeach 
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th>NIDN</th>                        
                                      <th>Nama Dosen</th>                                        
                                      <th>Pilih</th>
                                    </tr>
                                  </tfoot>                                    
                                </table>
                                </div>
                          </div><!-- /.box-body -->
                          <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                                    <button type="submit" class="btn btn-primary" id="button-reg">
                                        Simpan
                                    </button>
                                </div>
                        </div>
                      </div><!-- /.modal-body -->
                      
                      <div class="modal-footer">                         
                        <div><br></div>
                      </div>
                      </form>
                  </div>
              </div>
          </div>
          <!--end of Modal -->    

@endsection
@section('script')


    <script src="{{ URL::asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
      $(function () {

        $('#dataKurikulum').DataTable({"pageLength": 25});

        $('#dataDosen').DataTable({

            "scrollY": "200px",            
            "scrollCollapse": true, 
            "pageLength": 50,
            
        });


        

      });

      function tampilModal(klsId,kodemk,namamk){
        
        $('input+small').text('');
        $('input').parent().removeClass('has-error');
        $('select').parent().removeClass('has-error');

        document.getElementById('klsId').value=klsId;
        document.getElementById('kodemk').value=kodemk+' - '+namamk;
       /* document.getElementById('namamk').value=namamk;*/
       
        $('#myModal').modal('show');
        
        return false;
      };

      
      $(document).on('submit', '#formTambahDosenMakul', function(e) {  
            //variabel url diambil dari meta data di header template
            var url = document.getElementsByName('base_url')[0].getAttribute('content')

            e.preventDefault();
             
            $('input+small').text('');
            $('input').parent().removeClass('has-error');           
             
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
            })
            .done(function(data) {
                //console.log(data);
                
                $('.alert-success').removeClass('hidden');
                $('#myModal').modal('hide');
                    
                window.location.href=url+'/kelas'; 
            })
            .fail(function(data) {
                console.log(data.responeJSON);
                $.each(data.responseJSON, function (key, value) {
                    alert(value);
                   /* var input = '#formTambahDosenMakul input[name=' + key + ']';
                    
                    $(input + '+small').text(value);
                    $(input).parent().addClass('has-error');*/
                });
            });
        });

    </script>

@endsection

