<html>
	<head>
		<title>Cetak Nilai {{$kelaspeserta[0]['mkkurNama']}}</title>
	</head>
	<body>
	<style type="text/css">
		table{
			width: 100%;
		}
		table, th, td {
		   /* border: 1px solid black; */
		  
		}
		th, td {
		    padding: 5px;
		    text-align: left;
		   	vertical-align: middle;
		   	border-bottom: 1px solid #ddd;
		   	font-size: 12px;
		}		
		th {
			border-top: 1px solid #ddd;
			height: 20px;
			background-color: #ddd;
			text-align : center;
		}
	</style>
					<center>
					<font size="14">REKAPITULASI NILAI<br>
					POLITEKNIK NEGERI LARASIA INDONESIA<br>
					TAHUN AKADEMIK {{substr($semId,0,4)}} / {{substr($semId,0,4)+1}} @if(substr($semId,4,1)==1 ) GASAL @else GENAP @endif</font>
					<br>
					<hr>
					<br>
					</center>

					           @if(isset($kelaspeserta))
                        
                            <table >
                                <tbody>
                                <tr>
                                  
                                  <td>KODE </td>                                  
                                  <td><span class="badge bg-white">: @if(!empty($kelaspeserta[0]['mkkurKode'])){{$kelaspeserta[0]['mkkurKode']}} @else - @endif </span></td>
                                </tr>
                                <tr>
                                  <td>NAMA</td>                                  
                                  <td><span class="badge bg-light-white">: @if(!empty($kelaspeserta[0]['mkkurNama'])){{$kelaspeserta[0]['mkkurNama']}} @else - @endif</span></td>
                                </tr>
                                <tr>
                                  <td>SKS</td>                                 
                                  <td><span class="badge bg-yellow">: @if($kelaspeserta[0]['mkkurJumlahSks'] > 0){{$kelaspeserta[0]['mkkurJumlahSks']}} @else 0 @endif</span></td>                                  
                                </tr>
                                <tr>
                                  <td>Kelas</td>                                 
                                  <td><span class="badge bg-yellow">: @if(!empty($kelaspeserta[0]['klsNama'])){{$kelaspeserta[0]['klsNama']}} @else 0 @endif</span></td>                                  
                                </tr>
                                <tr>
                                  <td>Semester</td>                                 
                                  <td><span class="badge bg-yellow">: @if($kelaspeserta[0]['mkkurSemester'] > 0){{$kelaspeserta[0]['mkkurSemester']}} @else 0 @endif</span></td>                                  
                                </tr>                      
                               
                              </tbody>
                            </table>
                           
                                       
                    @endif
                    <br>
                    <table >
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
                        <td align="center">{{$i}}</td>
                        <td align="center">{{$itemPeserta->mhsNim}}</td>
                        <td>{{$itemPeserta->mhsNama}}</td>
                        <td align="right">{{$itemPeserta->krsnaNilaiTugas}}</td>
                        <td align="right">{{$itemPeserta->krsnaNilaiUTS}}</td>
                        <td align="right">{{$itemPeserta->krsnaNilaiUAS}}</td>
                        <td align="right">{{$itemPeserta->krsdtBobotNilai}}</td>
                        <td align="center">{{$itemPeserta->krsdtKodeNilai}}</td>
                      </tr>
                      <?php $i++; endforeach  ?>
                    @endif 
                    </tbody>                  
                  </table>
                  <br>
                  <table border="0" >                    
	                  <tr>
	                    <td width="70%" align="center"></td>
	                    <td align="center">Indonesia, {{date('d-m-Y')}}</td>	                            
	                  </tr>
	                  <tr height="60px">
	                    <td width="70%" align="center"></td>
	                    <td></td>	                            
	                  </tr>              
	                  <tr>
	                    <td width="70%" align="center"></td>
	                    <td align="center"><br><br><br>@if(!empty($kelaspeserta[0]['dsnNama']))<u>{{$kelaspeserta[0]['dsnNama']}}</u> @else _________________________ @endif</td>	                            
	                  </tr>            
                  </table>
	</body>
</html>