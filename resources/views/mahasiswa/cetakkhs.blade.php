<html>
	<head>
		<title>Cetak Nilai</title>
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
					<font size="15">KARTU HASIL STUDI <br>
					POLITEKNIK NEGERI LARASIA INDONESIA<br>
					TAHUN AKADEMIK {{substr($semId,0,4)}} / {{substr($semId,0,4)+1}} @if(substr($semId,4,1)==1 ) GASAL @else GENAP @endif</font>
					<br>
					<hr>
					<br>
					</center>

					@if(isset($IPK))
                        @foreach($IPK as $itemIPK)
                            <table >
                                <tbody>
                                <tr>
                                  
                                  <td><b>NIM</b></td>                                  
                                  <td><span class="badge bg-white">: <b> @if(!empty($itemIPK->Nim)){{$itemIPK->Nim}} @else - @endif </span></b></td>

                                  <td><b>NAMA</b></td>                                  
                                  <td><span class="badge bg-light-white">: <b>@if(!empty($itemIPK->mhsNama)){{$itemIPK->mhsNama}} @else - @endif</b></span></td>
                                </tr>
                                <tr>
                                  <td>JML SKS IPK</td>                                 
                                  <td><span class="badge bg-yellow">: @if($itemIPK->jmlSks > 0){{$itemIPK->jmlSks}} @else 0 @endif</span></td>

                                  <td>JML SKS IPS</td>                                 
                                  <td><span class="badge bg-green">: @if($IPS->jmlSks > 0){{$IPS->jmlSks}} @else 0 @endif </span></td>
                                </tr>
                                <tr>
                                  
                                  <td>IPK</td>                                  
                                  <td><span class="badge bg-red">: @if($itemIPK->IPK > 0){{$itemIPK->IPK}} @else 0 @endif </span></td>

                                  <td>IPS</td>                                  
                                  <td><span class="badge bg-light-blue">: @if($IPS->IPS > 0){{$IPS->IPS}} @else 0 @endif</span></td>
                                </tr>                                
                               
                              </tbody>
                            </table>
                           
                        @endforeach                        
                    @endif
                    <br>
                    <table >
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
                        <td align="center">{{$i}}</td>
                        <td>{{$itemNilai->mkkurKode}}</td>
                        <td>{{$itemNilai->mkkurNama}}</td>
                        <td align="center">{{$itemNilai->mkkurJumlahSks}}</td>
                        <td>{{$itemNilai->krsdtKodeNilai}}</td>
                       
                      </tr>
                      <?php $i++; endforeach  ?>
                    @endif 
                    </tbody>                    
                  </table>
                  <br>
                  <table border="0" >                    
	                  <tr>
	                    <td width="70%" align="center"></td>
	                    <td align="left">Indonesia, {{date('d-m-Y')}}</td>	                            
	                  </tr>
	                  <tr height="60px">
	                    <td width="70%" align="center"></td>
	                    <td></td>	                            
	                  </tr>              
	                  <tr>
	                    <td width="70%" align="center"></td>
	                    <td align="left"><br><br><br>_________________________</td>	                            
	                  </tr>            
                  </table>
	</body>
</html>