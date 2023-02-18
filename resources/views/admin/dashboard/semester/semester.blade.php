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
				<h3 class="box-title">Setting Semester Aktif <a class="btn btn-success btn-flat btn-sm"
						id="tambahSemester" title="Tambah"><i class="fa fa-plus"></i></a></h3>
			</div><!-- /.box-header -->

			<div class="box-body">
				<table id="dataKurikulum" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Kode Semester</th>
							<th>Tanggal Mulai</th>
							<th>Tanggal Selesai</th>
							<th>Tahun</th>
							<th>Tipe (Ganjil/Genap)</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($semester as $itemSemester):  ?>
						<tr>
							<td>{{$itemSemester->semId}}</td>
							<td>{{$itemSemester->semTglMulai}}</td>
							<td>{{$itemSemester->semTglSelesai}}</td>
							<td>{{$itemSemester->semTahun}}</td>
							<td>{{ $itemSemester->semNmSmtId==1 ? 'Ganjil' : 'Genap' }}</td>
							<td>{{ $itemSemester->semStatus==1 ? 'Aktif' : 'Tidak Aktif' }}</td>

							<td>
								<a href="{{{ action('Semester\SemesterController@hapus',[$itemSemester->semId]) }}}"
									title="hapus"
									onclick="return confirm('Apakah anda yakin akan menghapus Semester {{{$itemSemester->semId}}} : {{{$itemSemester->semTahun.$itemSemester->semNmSmtId==1 ? 'Ganjil' : 'Genap' }}} ?')">
									<span class="label label-danger"><i class="fa fa-trash"> Delete </i></span>
								</a> |

								@if($itemSemester->semStatus==0)
								<a href="{{{ URL::to('semester/'.$itemSemester->semId.'/aktif') }}}">
									<span class="label label-warning"><i class="fa fa-check"> Aktifkan! </i></span>
								</a>
								@else
								<span class="label label-success"><i class="fa fa-check-circle"> Aktif </i></span>
								</a>

								@endif
							</td>
						</tr>
						<?php endforeach  ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Kode Semester</th>
							<th>Tanggal Mulai</th>
							<th>Tanggal Selesai</th>
							<th>Tahun</th>
							<th>Tipe (Ganjil/Genap)</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->

	</div><!-- /.col -->
</div><!-- /.row -->

<!-- Modal -->
<div class="modal fade" id="myModalSemester" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Semester - Tambah</h4>
			</div>
			<div class="modal-body">

				<form id="formSemester" class="form-horizontal" role="form" method="POST"
					action="{{ url('/semester/tambahsemester') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label class="col-md-4 control-label">Kode</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="semId">
							<small class="help-block"></small>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Tanggal Mulai </label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="semTglMulai">
							<small class="help-block"></small>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Tanggal Selesai</label>
						<div class="col-md-6 has-error">
							<input type="text" class="form-control" name="semTglSelesai">
							<small class="help-block"></small>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Tahun</label>
						<div class="col-md-6 has-error">
							<input type="text" class="form-control" name="semTahun">
							<small class="help-block"></small>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">Ganjil/Genap</label>
						<div class="col-md-6 has-error">
							<select class="form-control" name="semNmSmtId">
								<option value="1">Ganjil</option>
								<option value="2">Genap</option>
							</select>
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
<script>
	$(function() {

		$('#dataKurikulum').DataTable({
			"pageLength": 50
		});

	});

	$('#tambahSemester').click(function() {
		$('input+small').text('');
		$('input').parent().removeClass('has-error');
		$('select').parent().removeClass('has-error');

		$('#myModalSemester').modal('show');
		//console.log('test');
		return false;
	});

	$(document).on('submit', '#formSemester', function(e) {
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

				window.location.href = url + '/semester';
			})
			.fail(function(data) {
				console.log(data.responeJSON);
				$.each(data.responseJSON, function(key, value) {
					var input = '#formSemester input[name=' + key + ']';

					$(input + '+small').text(value);
					$(input).parent().addClass('has-error');
				});
			});
	});
</script>

@endsection