<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];

			

	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$FrecuenciaCanal = $ArrayCaps[$cont]['channel.frequency'];
				
				if (isset($_GET['frecuencia-canal']) && $cont==0){
					echo "<table id='frecuencia-canal2'><tr><th>Frencuencia del canal</th></tr><tr><td>$FrecuenciaCanal Hz&nbsp</td>";}
				else if(isset($_GET['frecuencia-canal']) && $cont<$capsNum-1){
					echo "<tr><td>$FrecuenciaCanal Hz&nbsp </td></tr>";}
				else if(isset($_GET['frecuencia-canal']) && $cont==$capsNum-1){
					echo "<tr><td>$FrecuenciaCanal Hz&nbsp</td></tr></table>";}


			

		}


?>
