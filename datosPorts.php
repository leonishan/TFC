<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>

<?php
		$API = new routeros_api();
		$IP = $_SESSION[ 'ip' ];
		$user = $_SESSION[ 'user' ];
		$password = $_SESSION[ 'password' ];
		//Comprobamos conexion API
		if ($API->connect($IP, $user, $password)) {

		//Comprobamos interfaces
		$Ports = $API->comm("/interface/ethernet/print");
		$numPorts = count($Ports);

		//Modelo
		$modeloCom = $API->comm("/system/routerboard/print");
		$modelo=$modeloCom[0]['model'];
		//Estado Link

		$valoresPar= json_encode(range(0, $numPorts-1));
		$valores = substr($valoresPar, 1, -1);

		//Switch
		$switches = $API->comm("/interface/ethernet/switch/print");
		$numSwitches = count($switches);

		//Ports Switch
		$portsSwitch = $API->comm("/interface/ethernet/switch/port/print");
		$numPortsSwitch = count($portsSwitch);

		//puerto Acceso, Trunk, NoSwitchport de RB
		$estadoPort = $API->comm("/interface/ethernet/switch/port/print");

		//puerto Trunk CR
		$estadoTrunkCR = $API->comm("/interface/ethernet/switch/egress-vlan-tag/print");

		//puerto Acceso CR
		$estadoAccessCR = $API->comm("/interface/ethernet/switch/ingress-vlan-translation/print");


		//CPU
		$cpuInfo = $API->comm("/system/resource/print");
		//RB o CS
		$routeroSwitch = $cpuInfo[0]['board-name'];
		//Saber iniciales Router o Switch
		$identidadRS = substr($routeroSwitch,0,2);



		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
		$API->disconnect();}	
				?>
<?php
				if(strcmp($identidadRS,"RB") == 0 ){
				echo "<table class='tablePorts'>";
				
				for ($cont = 0; $cont < $numPorts; $cont++){
						echo '<tr><td><b>'.$estadoPort[$cont]['name'].'</b></td>';
						echo "<td>";
//Detectar Si es Vlan, Trunk, No Switchport En RB
						if($identidadRS == 'RB'){
							if($estadoPort[$cont]['vlan-mode']!='disabled' and $estadoPort[$cont]['vlan-header']=='always-strip'){
								echo "Access Vlan: ".$estadoPort[$cont]['default-vlan-id'];
							}
							else if($estadoPort[$cont]['vlan-mode']!='disabled' and $estadoPort[$cont]['vlan-header']=='add-if-missing'){
								echo "Trunk";
							}
							else{
								echo "No Switchport";
							}
						}

						echo '</td></tr>';
				}
				
				echo "</table>";
				}				
			?>

<!--TABLA CR-->
			<?php
				
				if(strcmp($identidadRS,"CR") == 0 ){
					echo "Access
						<table class='tablePortsCR'>
					";
					echo '<tr><td>Ports</td>';
					echo '<td>VLAN</td></tr>';
					for ($cont = 0; $cont < count($estadoAccessCR); $cont++){
						echo '<tr><td>'.$estadoAccessCR[$cont]['ports'].'</td>';
						echo '<td>'.$estadoAccessCR[$cont]['new-customer-vid'].'</td>';
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableAccessCR$cont' value='X' class='buttonDisable'/>
							</form></td>";
			

						echo '</tr>';
					}
				
					echo "</table>";
							
					echo "Trunk
						<table class='tablePortsCR'>
					";
					echo '<tr><td>VLAN</td>';
					echo '<td>Ports</td></tr>';
					for ($cont = 0; $cont < count($estadoTrunkCR); $cont++){
						echo '<tr><td>'.$estadoTrunkCR[$cont]['vlan-id'].'</td>';
						echo '<td>'.$estadoTrunkCR[$cont]['tagged-ports'].'</td>';
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableTrunkCR$cont' value='X' class='buttonDisable'/>
							</form></td>";
			

						echo '</tr>';
					}
				
					echo "</table>";


				}
			?>
