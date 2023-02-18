@extends('admin.layout.master')
@section('breadcrump')
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard Admin</li>
          </ol>
@stop
@section('content')

          <div class="callout callout-info">
            <h4>Selamat Datang Admin!</h4>
            <p>Untuk menggunakan Larasia harap diperhatikan bagian-bagian data inti yang perlu dipersiapkan sebelumnya sebelum siap digunakan. </p>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Penting !!</h3>
              <div class="box-tools pull-right">
                <button data-original-title="Collapse" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title=""><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div style="display: block;" class="box-body">

              Data penting yang perlu disiapkan antara lain :
                <ul>
                <li>
                1. Data Kurikulum</li>
                <li>
                2. Matakuliah yang ada dalam kurikulum.</li>
                <li>
                3. Data Jurusan</li>
                <li>                
                4. Data Program Studi yang ada dalam Jurusan</li>
                <li>
                5. Semester Aktif</li>
                <li>
                6. Semester Prodi Aktif</li>
                <li>
                7. Data Master Mahasiswa</li>
                <li>
                8. Data Master Dosen</li>
                <li>
                9. Proses registrasi mahasiswa pada semester aktif</li>
                <li>
                10. Proses registrasi matakuliah pada semester aktif</li>
                <li>
                11. Pembentukan kelas untuk matakuliah dan peserta matakuliah</li>
                <li>
                12. Penentuan dosen pengampu pada kelas matakuliah</li>
                <li>
                13. Proses download peserta dan input nilai matakuliah</li>
                <li>
                14. Penentuan hak akses untuk dosen</li>
                <li>
                15. Penentuan hak akses untuk mahasiswa</li>
                
                </ul>
            </div><!-- /.box-body -->
            <div style="display: block;" class="box-footer">
              nb : Dalam proses ini sangat tergantung pada urutan proses.
            </div><!-- /.box-footer-->
          </div>


             
@endsection
@section('script')



@endsection
