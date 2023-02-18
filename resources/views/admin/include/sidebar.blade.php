      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>LARASIA</p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class=" @if(url('/jurusan') == request()->url() or url('/prodi') == request()->url() ) active @else '' @endif  treeview">
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Program Studi</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{URL::to('jurusan')}}"><i class="fa fa-circle-o"></i> Jurusan </a></li>
                <li><a href="{{URL::to('prodi')}}"><i class="fa fa-circle-o"></i> Program Studi </a></li>
               
              </ul>
            </li>
            <li class="@if(url('/kurikulum') == request()->url() or url('/kurikulum/matakuliah') == request()->url() ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Kurikulum</span> <i class="fa fa-angle-left pull-right"></i>
               
              </a>
              <ul class="treeview-menu">
                <li><a href="{{URL::to('kurikulum')}}"><i class="fa fa-circle-o"></i> Data Kurikulum </a></li>
                <li><a href="{{URL::to('kurikulum/matakuliah')}}"><i class="fa fa-circle-o"></i> Matakuliah </a></li>
              </ul>
            </li>
            <li class="@if(url('/semester') == request()->url() or url('/semester/semesterprodi') == request()->url() ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Semester</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{URL::to('semester')}}}"><i class="fa fa-circle-o"></i> Setting Semester</a></li>
                <li><a href="{{{URL::to('semester/semesterprodi')}}}"><i class="fa fa-circle-o"></i> Semester Prodi</a></li>
              </ul>
            </li>
            <li class="@if(url('/dosen') == request()->url()) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Dosen</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{URL::to('dosen')}}}"><i class="fa fa-circle-o"></i> Data Dosen</a></li>
                
                
              </ul>
            </li>
            <li class="@if(url('/mahasiswa') == request()->url()  ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Mahasiswa</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{URL::to('mahasiswa')}}}"><i class="fa fa-circle-o"></i> Data Mahasiswa</a></li>  
              </ul>
            </li>
            <li class="@if(url('/kelas/register/kelaspeserta') == request()->url() ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Rencana Studi</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{route('kelas.klspeserta.proses')}}}"><i class="fa fa-circle-o"></i> Register Kelas Peserta</a></li>
                
              </ul>
            </li>
            <li class="@if(url('/kelas/mhsregister') == request()->url() or url('/kelas/register') == request()->url() or url('/kelas') == request()->url() ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Kelas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{route('kelas.mhsregister')}}}"><i class="fa fa-circle-o"></i> Mahasiswa Register</a></li>
                <li><a href="{{{URL::to('kelas/register')}}}"><i class="fa fa-circle-o"></i> Register Kelas</a></li>
                <li><a href="{{{URL::to('kelas')}}}"><i class="fa fa-circle-o"></i> Dosen Kelas</a></li> 
                               
                <!-- <li><a href="{{{URL::to('kelas/9/prodi')}}}"><i class="fa fa-circle-o"></i> Kelas Untuk Prodi tertentu</a></li> -->
                
              
              </ul>
            </li>
            <li class="@if(url('/nilai/jenis') == request()->url() or url('/kelas/input/nilai') == request()->url() ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-file-text"></i>
                <span>Hasil Studi</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{URL::to('nilai/jenis')}}}"><i class="fa fa-circle-o"></i> Jenis Nilai</a></li>
                <li><a href="{{{route('kelas.input.nilai')}}}"><i class="fa fa-circle-o"></i> Input Nilai</a></li>               
              
              </ul>
            </li>
            <li class="@if(url('/accountmahasiswa') == request()->url() or url('/accountdosen') == request()->url() ) active @else '' @endif treeview">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>User Management</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{{URL::to('accountmahasiswa')}}}"><i class="fa fa-circle-o"></i> Akun Mahasiswa</a></li>
                <li><a href="{{{URL::to('accountdosen')}}}"><i class="fa fa-circle-o"></i> Akun Dosen</a></li> 
                       
              
              </ul>
            </li>

                  
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Modal -->
      <div class="modal fade" id="myModalq" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabelq">Modal title</h4>
            </div>
            <div class="modal-bodyq">
              ...
            </div>
            <div class="modal-footerq">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end of modal -->
