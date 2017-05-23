<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="utf-8">
   <title><?php echo $title;?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
   <!--  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet"> -->
	 <link href="<?php echo base_url();?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/pages/dashboard.css" rel="stylesheet">
    


    <link href="<?php echo base_url();?>assets/plugins/editable/css/bootstrap-editable.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/plugins/select2/select2.css" rel="stylesheet">
	  <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/img/msm.png" />


	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>
		<!--editable-->
		
<body>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand" href="<?php echo base_url();?>app">
				E-Monitoring			
			</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">
					<li class="dropdown">						
						<a href="<?php echo base_url();?>login" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-key"></i>
							Login
						</a>						
					</li>
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->
<?php $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");			
 ?>
<div class="span12">      		
	      		
	      		<div class="widget ">
	      			
	      			<div class="widget-header">
	      				<i class="icon-list"></i>
	      				<h3>Tracking Project Daerah Bulan <?php echo $BulanIndo[(int)date('m')-1]; ?></h3>
	  				</div> <!-- /widget-header -->
					
			<div class="widget-content">
			<table style='font-size:14px;' class='table table-bordered table-hover table-heading table-datatable' id='datatable-1'>
         	  <thead style="background-color: #e5e5e5;">
				<meta http-equiv="refresh" content="30">
	            <tr>
	              <th width="280px">Nama Daerah</th>
	              <th>Nama Sistem</th>
	              <th width="100px">Tanggal diterima</th>
	              <th width="100px">Jenis Data</th>
	              <th width="70px">Kapasitas</th>
	              <th width="100px">Status Backup</th>
	              <th>Keterangan</th>
	              <th width="40px"></th>
	              
	            </tr>
          	   </thead>
          	   <tbody>
				<?php
				
				$conn_string = "host=localhost port=5432 dbname=e-monitoring user=postgres password=faizpg";
				$connection = pg_connect($conn_string);
				
					 $query="select * from (
						select ('1') as nomor,a.kd_area,a.kd_area as kode,a.nm_area as area,'' as nm_sistem,'' as kd_sistem,'' as bulan,'' as tahun,
						to_timestamp(2017-03-10) as tgl_terima,'' as jenis_data,'' as kapasitas,'' as status,'' as keterangan,'' as detail
						from ms_area a join ms_wilayah_child b on a.kd_area=b.kd_area GROUP BY a.kd_area,a.nm_area

						UNION
						
						select ('2') as nomor,a.kd_area,a.kd_subarea as kode,upper(a.nm_subarea) as area,'' as nm_sistem,'' as kd_sistem,'' as bulan,'' as tahun,
						to_timestamp(2017-03-10) as tgl_terima,'' as jenis_data,'' as kapasitas,'' as status,'' as keterangan,'' as detail 
						from ms_area_sub a join ms_wilayah_child b 
						on a.kd_area=b.kd_area and a.kd_subarea=b.kd_subarea GROUP BY a.kd_subarea,a.kd_area,a.nm_subarea

						UNION 

						select ('3') as nomor,a.kd_area,a.kd_subarea as kode,b.nm_subarea as area,a.nm_sistem,a.kd_sistem,c.bulan,c.tahun,
						COALESCE(c.tgl_terima,now()) as tgl_terima,COALESCE(c.jenis_data,'-') as jenis_data,c.kapasitas,coalesce(c.status,'0') as status,c.keterangan,'' as detail 
						from ms_wilayah_child a
						JOIN ms_area_sub b on a.kd_area=b.kd_area and a.kd_subarea=b.kd_subarea
						LEFT JOIN e_monitor c on a.kd_wilayah=c.kd_wilayah and a.kd_area=c.kd_area and a.kd_subarea=c.kd_subarea and
						a.kd_sistem=c.kd_sistem

						)x ORDER BY kd_area ASC,kode ASC,nomor ASC";
				
				 $result = pg_query($connection, $query) or die('Query failed: ' . pg_last_error());
				 while ($resulte = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				
		if($resulte['nomor']=='1'){
		  $bold='<b>';
		  $spasi='';
		  $bold2='</b>';
		  $tgl= '';
		  $area="<h3 class='icon-map-marker'> ".$resulte['area'].'</h3>';
		  $bg='';
		  $cstat ="";
		} 
		if($resulte['nomor']=='2'){
		  $bold='<b>';
		  $spasi='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		  $bold2='</b>';
		  $tgl= '';
		  $area=$resulte['area'];
		  $bg='';
		  $cstat ="";
		  }
		if($resulte['nomor']=='3'){
		  $bold='';
		  $spasi='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		  $bold2='';
		  $tgl= '';//$this->TanggalIndo($resulte['tgl_terima']);
		  $area='';
		  $stat = $resulte['status'];
          $cstat ="<a href='#' class='cstat' data-type='select' data-subarea='".$resulte['kode']."' data-sistem='".$resulte['kd_sistem']."' data-value='".$resulte['status']."'></a>";
		
		  } 
					
			if($resulte['status']==0){
				$status='Belum Backup';
			}else{
				$status='Sudah Backup';
			}
			
		?>
			
			<?php if($resulte['nomor']=='3'){?>
			
					<?php if($resulte['jenis_data']=='-'){?>
						<tr>
							  <td ><?php  ?></td>
							  <td bgcolor='#FA8072' ><?php echo $resulte['nm_sistem'];?></td>
							  <td bgcolor='#FA8072' ><?php echo '-' ?></td>
							  <td bgcolor='#FA8072' ><?php echo $resulte['jenis_data'];?></td>
							  <td bgcolor='#FA8072' ><?php echo '-'?></td>
							  <td bgcolor='#FA8072' ><?php echo '-'?></td>
							  <td bgcolor='#FA8072' ><?php echo $resulte['keterangan'];?></td>
							  <td bgcolor='#FA8072' ><?php echo $resulte['detail'];?></td>
						</tr>
					<?php }else{?>
						<tr>
							  <td ><?php  ?></td>
							  <td bgcolor='#98FB98' ><?php echo $resulte['nm_sistem'];?></td>
							  <td bgcolor='#98FB98' ><?php echo $resulte['tgl_terima'];?></td>
							  <td bgcolor='#98FB98' ><?php echo $resulte['jenis_data'];?></td>
							  <td bgcolor='#98FB98' ><?php echo $resulte['kapasitas'];?> Mb</td>
							  <td bgcolor='#98FB98' ><?php echo $status;?></td>
							  <td bgcolor='#98FB98' ><?php echo $resulte['keterangan'];?></td>
							  <td bgcolor='#98FB98' ><?php echo $resulte['detail'];?></td>
						</tr>
					<?php }?>
			<?php }else{?>
				<?php if($resulte['nomor']=='1'){?>
						<tr>
							  <td bgcolor='#EEEEEE'><h3 class='icon-map-marker'> <?php echo $resulte['area'];?></h3></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['nm_sistem'];?></td>
							  <td bgcolor='#EEEEEE' ><?php  ?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['jenis_data'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['kapasitas'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['status'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['keterangan'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['detail'];?></td>
						</tr>
					<?php }else{?>
						<tr>
							  <td bgcolor='#EEEEEE'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h4 class='icon-paper-clip'>&nbsp;&nbsp;<?php echo $resulte['area'];?></h4></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['nm_sistem'];?></td>
							  <td bgcolor='#EEEEEE' ><?php  ?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['jenis_data'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['kapasitas'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['status'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['keterangan'];?></td>
							  <td bgcolor='#EEEEEE' ><?php echo $resulte['detail'];?></td>
						</tr>
					<?php }?>
			<?php }?>
		<?php }?>
			  
			   </tbody>
          	</table>
          	   <small><i class="icon-sign-blank" style='color:#98FB98;font-size:14px'></i> Data Sudah diterima QA.</small>
          	   <br>
          	   <small><i class="icon-sign-blank" style='color:#FA8072;font-size:14px'></i> Data Belum diterima QA.</small>
          	   <br>
          	   <small>* Default refresh otomatis setiap 30 detik.</small><br>
			</div>
			
	</div>
 </div>
	<script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/excanvas.min.js"></script>
    <script src="js/chart.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/base.js"></script>
    <script>
        var doughnutData = [
				{
				    value: 30,
				    color: "#F7464A"
				},
				{
				    value: 50,
				    color: "#46BFBD"
				},
				{
				    value: 100,
				    color: "#FDB45C"
				},
				{
				    value: 40,
				    color: "#949FB1"
				},
				{
				    value: 120,
				    color: "#4D5360"
				}

			];

        var myDoughnut = new Chart(document.getElementById("donut-chart").getContext("2d")).Doughnut(doughnutData);


        var lineChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    pointColor: "rgba(220,220,220,1)",
				    pointStrokeColor: "#fff",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    pointColor: "rgba(151,187,205,1)",
				    pointStrokeColor: "#fff",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }

        var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);


        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }

var myLine = new Chart(document.getElementById("bar-chart").getContext("2d")).Bar(barChartData);

var pieData = [
				{
				    value: 30,
				    color: "#F38630"
				},
				{
				    value: 50,
				    color: "#E0E4CC"
				},
				{
				    value: 100,
				    color: "#69D2E7"
				}

			];

				var myPie = new Chart(document.getElementById("pie-chart").getContext("2d")).Pie(pieData);

				var chartData = [
			{
			    value: Math.random(),
			    color: "#D97041"
			},
			{
			    value: Math.random(),
			    color: "#C7604C"
			},
			{
			    value: Math.random(),
			    color: "#21323D"
			},
			{
			    value: Math.random(),
			    color: "#9D9B7F"
			},
			{
			    value: Math.random(),
			    color: "#7D4F6D"
			},
			{
			    value: Math.random(),
			    color: "#584A5E"
			}
		];
				var myPolarArea = new Chart(document.getElementById("line-chart").getContext("2d")).PolarArea(chartData);
	</script>
  
</body>		