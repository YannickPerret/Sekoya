 <?php
 require("config.php");

    // Récupère l'heure du serveur
	    $json = file_get_contents('https://www.prevision-meteo.ch/services/json/'.$GLOBALS['ville']);
		$json = json_decode($json);
		//var_dump($json);
		
		    $json2 = file_get_contents('https://www.prevision-meteo.ch/services/json/'.$GLOBALS['ville2']);
		$json2 = json_decode($json2);
		
		    $json3 = file_get_contents('https://www.prevision-meteo.ch/services/json/'.$GLOBALS['ville3']);
		$json3 = json_decode($json3);
    ?>

<html>
	<head>
		<title>Sekoya</title>
		<meta charset="utf-8" />
        <link rel="stylesheet" href="css/css.css" />
		<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		
	</head>
	
	<body>
	
		<div id="conteneur"> 	
			<div id="contenuFirst">
			<!--<div id="LogoSekoya">
					<img src="image/sekoya.png" alt="image de sekoya diffusion"  />
				</div>-->
				<div id="LogoSekoya">
					<img src="image/sekoya.png" alt="logo de sekoya diffusion Sa" height="100%" width="100%" />
					
				</div>
				<div id="TimeIsIt">
				
					<a href="https://time.is/Yverden" id="time_is_link" rel="nofollow" style="font-size:36px"></a>
					<span id="Yverden_z736" style="font-size:36px"></span>
					
				</div>	
				<div id="Historique">
				<?php 
				$myfile = fopen("display_histo.txt", "r") or die("Unable to open file!");
				echo fread($myfile,filesize("display_histo.txt"));
				fclose($myfile);
				?>
				</div>
				<div id="Subconteneur">
					<div id="SubNews1">
						<div class="Titre"><h1>Les dernières nouvelles</h1></div>
						<?php 
				$myfile = fopen("display_new.txt", "r") or die("Unable to open file!");
				echo fread($myfile,filesize("display_new.txt"));
				fclose($myfile);
				?>
					</div>
					
					
				</div>
			</div>
			<div id="contenuSecond">
			<table>
							<tr> <!-- Nom de la ville -->
								<td><span><?php echo $json->city_info->name . "<br>";?></span><td>
							</tr><tr>
								<td>Il fait <?php echo $json->current_condition->tmp . "<br>";?> degré<td></tr>
							<tr>
								<td><img src="<?php echo $json->current_condition->icon_big;?>"><td></tr>
							<tr>	
								<td><?php echo $json2->city_info->name . "<br>";?></td></tr>
							<tr>
								<td>Il fait <?php echo $json2->current_condition->tmp . "<br>";?> degré</td></tr>
							<tr>
								<td><img src="<?php echo $json2->current_condition->icon_big;?>"></td></tr>

							<tr>	
								<td><?php echo $json3->city_info->name . "<br>";?></td></tr>
							<tr>	
								<td>Il fait <?php echo $json3->current_condition->tmp . "<br>";?> degré</td></tr>
							<tr>	
								<td><img src="<?php echo $json3->current_condition->icon_big;?>"></td></tr>
						</table>
			</div>
		</div>
		
<script src="//widget.time.is/fr.js"></script>
						<script>
							time_is_widget.init({Yverden_z736:{template:"TIME DATE", date_format:"dayname daynum/monthnum/year"}});
						</script>
	</body>
</html>