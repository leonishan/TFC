<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	$ArrayAddresses = $_SESSION[ 'ArrayAdresses' ];
	$ArrayDHCP = $_SESSION[ 'ArrayDHCP' ];	
	$ArrayPool = $_SESSION[ 'ArrayPool' ];
	

	


	function ip_lan($ArrayDHCP,$ArrayCaps,$cont,$ArrayPool){
				for ($cont3 = 0;$cont3<count($ArrayDHCP);$cont3++){
					
					if($ArrayDHCP[$cont3]['interface']===$ArrayCaps[$cont]['name']){
						return $ArrayPool[$cont3]['ranges'];
					}
					
				}			
		}
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				$IPLAN = ip_lan($ArrayDHCP,$ArrayCaps,$cont,$ArrayPool);
				


				if (isset($_GET['ip-lan']) && $cont==0){
					echo "<table id='ip-lan2'><tr><th>IP LAN</th></tr><tr><td>$IPLAN &nbsp</td>";}
				else if(isset($_GET['ip-lan']) && $cont<$capsNum-1){
					echo "<tr><td>$IPLAN&nbsp</td></tr>";}
				else if(isset($_GET['ip-lan']) && $cont==$capsNum-1){
					echo "<tr><td>$IPLAN&nbsp</td></tr></table>";}

				
				
			
		}


?>
