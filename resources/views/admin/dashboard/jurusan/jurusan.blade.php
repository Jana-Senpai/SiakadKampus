@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
            <li class="active">Jurusan</li>
          </ol>
@stop
@section('content')
          <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Daftar Jurusan <a href="{{{ action('Jurusan\JurusanController@tambah') }}}" class="btn btn-success btn-flat btn-sm" data-toggle="modal" title="Tambah"><i class="fa fa-plus"></i></a></h3>
                  </div><!-- /.box-header -->
                  <div class="box-body no-padding">
                    <table class="table table-condensed">
                      <tbody><tr>
                        <th style="width: 50px; text-align: center;">Kode </th>
                        <th>Nama Resmi</th>
                        <th>Aksi</th>
                      </tr>
                      <?php foreach ($jurusan as $datajurusan):  ?>
                      <tr>
                          <td style="text-align: center;">{{ $datajurusan->jurKode}}</td>
                          <td>{{ $datajurusan->jurNama}}</td>
                          <td><a href="{{{ action('Jurusan\JurusanController@hapus',[$datajurusan->jurKode]) }}}" title="hapus" onclick="return confirm('Apakah anda yakin akan menghapus Jurusan {{{$datajurusan->jurKode .' - '.$datajurusan->jurNama }}}?')">
                            <span class="label label-danger"><i class="fa fa-trash"> Delete </i></span>
                            </a>
                          
                          </td>                        
                      </tr>
                      <?php endforeach  ?>  
                      </tbody>
                    </table>
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
          </div><!-- /.row (main row) -->
            
@endsection


