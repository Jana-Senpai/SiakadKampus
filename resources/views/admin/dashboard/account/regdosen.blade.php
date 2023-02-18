@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Semester</li>
           
          </ol>
@stop
@section('content')
          
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Daftar Akun Dosen <a class="btn btn-success btn-flat btn-sm"  title="Tambah"><i class="fa fa-plus"></i></a></h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                  <table id="dataKurikulum" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th>NIDN</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Username</th> 
                        <th>Prodi</th>
                        <th>Jurusan</th>
                        <th>Level</th>    
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach ($listAccountDosen as $itemAccount):  ?>
                      <tr>
                        <td>{{$itemAccount->dsnNidn}}</td>
                        <td>{{$itemAccount->dsnNip}}</td>
                        <td>{{$itemAccount->dsnNama}}</td>
                        <td><span class="label label-success"><i class="fa fa-unlock-alt"> {{$itemAccount->username}} </i></span></td>
                        <td>{{$itemAccount->prodiNama}}</td> 
                        <td>{{$itemAccount->jurNama}}</td> 
                        <td>@if($itemAccount->level==1)                               
                              <span class="label label-warning"><i class="fa fa-check"> Admin </i></span>
                            @elseif($itemAccount->level==2)
                               <span class="label label-warning"><i class="fa fa-check-circle"> Dosen </i></span>
                            @elseif($itemAccount->level==3)
                              <span class="label label-warning"><i class="fa fa-check-circle"> Mahasiswa </i></span>
    
                            @endif
                        </td>
                        <td>
                            @if($itemAccount->password=="")
                            <a class="btn btn-success btn-flat btn-sm" onClick="tampilModal('{{$itemAccount->dsnNip}}','{{$itemAccount->dsnNama}}')" id="registerMahasiswa{{{$itemAccount->dsnNip}}}" title="Register"><i class="fa fa-plus"></i></a>
                            @else
                           
                            <a class="btn btn-danger btn-flat btn-sm" href="{{{ URL::to('accountmahasiswa/'.$itemAccount->id.'/hapus') }}}" title="Hapus Akun"><i class="fa fa-trash"> Unreg </i></span>
                            </a> 
                            @endif

                            
                        </td>       
                      </tr>
                      <?php endforeach  ?> 
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Username</th>                        
                        <th>Prodi</th>
                        <th>Jurusan</th>
                        <th>Level</th>    
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
                          <h4 class="modal-title" id="myModalLabel">Register Dosen - Tambah</h4>
                      </div>
                      <div class="modal-body">
           
                          <form id="formRegisterDosen" class="form-horizontal" role="form" method="POST" action="{{ url('/accountdosen/tambah') }}">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">  
                              <input type="hidden" name="level" id="level" value="">          
                              <div class="form-group">
                                  <label class="col-md-4 control-label">NIP</label>
                                  <div class="col-md-6">
                                      <input type="text" class="form-control" id="nip" name="nip" readonly="true">
                                      <small class="help-block"></small>
                                  </div>
                              </div>           
                              <div class="form-group">
                                  <label class="col-md-4 control-label">Nama</label>
                                  <div class="col-md-6">
                                      <input type="text" class="form-control" id="nama" name="nama" readonly="true">
                                      <small class="help-block"></small>
                                  </div>
                              </div>           
                              <div class="form-group">
                                  <label class="col-md-4 control-label">Username</label>
                                  <div class="col-md-6 has-error">
                                      <input type="text" class="form-control" id="username" name="username" readonly="true">
                                      <small class="help-block"></small>
                                  </div>
                              </div>   
                              <div class="form-group">
                                  <label class="col-md-4 control-label">Password</label>
                                  <div class="col-md-6 has-error">
                                      <input type="password" class="form-control" id="password" name="password">
                                      <small class="help-block"></small>
                                  </div>
                              </div>       

                              <div class="form-group">
                                  <label class="col-md-4 control-label">Konfirmasi Password</label>
                                  <div class="col-md-6 has-error">
                                      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                      <small class="help-block"></small>
                                  </div>
                              </div> 
                              <div class="form-group">
                                  <div class="col-md-6 col-md-offset-4">
                                      <button type="submit" class="btn btn-primary" id="button-reg">
                                          Simpan
                                      </button>
                                  </div>
                              </div>
                          </form>                       
           
                      </div>
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

        $('#dataKurikulum').DataTable({"pageLength": 50});

      });

      function tampilModal(nip,nama){
        //alert(level);
        $('input+small').text('');
        $('input').parent().removeClass('has-error');
        $('select').parent().removeClass('has-error');

        document.getElementById('nip').value=nip;
        document.getElementById('nama').value=nama;
        document.getElementById('username').value=nip;

        //$('#nama').val()=nama;
        $('#myModal').modal('show');
            //console.log('test');
        return false;
      };

      $(document).on('submit', '#formRegisterDosen', function(e) {  
            //variabel url diambil dari meta data di header template
            var url = document.getElementsByName('base_url')[0].getAttribute('content')
            e.preventDefault();
             
            $('input+small').text('');
            $('input').parent().removeClass('has-error');           
             
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json"
            })
            .done(function(data) {
                console.log(data);
                
                $('.alert-success').removeClass('hidden');
                $('#myModal').modal('hide');
                
                window.location.href=url+'/accountdosen'; 
            })
            .fail(function(data) {
                console.log(data.responeJSON);
                $.each(data.responseJSON, function (key, value) {
                    var input = '#formRegisterDosen input[name=' + key + ']';
                    
                    $(input + '+small').text(value);
                    $(input).parent().addClass('has-error');
                });
            });
        });

    </script>

@endsection

