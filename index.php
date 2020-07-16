<?php
ini_set('display_errors','off');

class Misc {
    /**
     * Va remplacer toutes les chaines $find par $replace dans le fichier $file
     */
    public static function replaceInfile($file, $find, $replace) {
        if ($find != $replace) {
            //recupere la totalité du fichier
            $str = file_get_contents($file);
            if ($str === false) {
                return false;
            } else {
                //effectue le remplacement dans le texte
                $str = str_replace($find, $replace, $str);
                //remplace dans le fichier
                if (file_put_contents($file, $str) === false) {
                    return false;
                }
            }
        }
        return true;
    }
}

require ('../config.php');

	if(isset($_POST['upload'])){
		
		$currentDirectory = getcwd();
		$uploadDirectory = "/uploads/";

		$errors = []; // Store errors here

		$fileExtensionsAllowed = ['jpeg','jpg','png', 'pdf', 'xls', 'csv']; // These will be the only file extensions allowed 

		$fileName = $_FILES['the_file']['name'];
		$fileSize = $_FILES['the_file']['size'];
		$fileTmpName  = $_FILES['the_file']['tmp_name'];
		$fileType = $_FILES['the_file']['type'];
		$fileExtension = strtolower(end(explode('.',$fileName)));

		//$uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 
		$uploadPath = dirname($currentDirectory) .'/image/'. basename($fileName); 

		if (isset($_POST['submit'])) {

		  if (! in_array($fileExtension,$fileExtensionsAllowed)) {
			$errors[] = "Le fichier ne contient pas la bonne extension. Veuillez insérer un document PDF, XLS, CSV, JPEG ou PNG";
		  }

		  if ($fileSize > 7000000) {
			$errors[] = "Le fichier est trop volumineux, merci de contacter l'IT (7MB)";
		  }

		  if (empty($errors)) {
			$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
			//echo "../image/"+$fileName;

			if ($didUpload) {
			 $errors = "<h4>Le fichier \"" . basename($fileName) . "\" a bien été téléchargé </h4>";
			} else {
			  echo "An error occurred. Please contact the administrator.";
			}
		  } else {
			foreach ($errors as $error) {
			  echo $error . "These are the errors" . "\n";
			}
		  }

		}
	}
?>

<!doctype html>
<html lang="fr">
  <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../css/css.css">
		<link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		
		
		<title>Sekoya - Panneau d'affichage Officiel</title>
  </head>
  <body>
  
    <div class="container-fluid">
		<div class="row" >
			<div class="col-lg-4" style="max-width:20%;">
				<a href="../index.php"><img src="../image/sekoya.png" alt="logo de sekoya diffusion Sa" height="85%" width="75%"/></a>
			</div>
			<div class="col-lg-6" style="margin-left : 10%;">
				<a href="https://time.is/Yverden" id="time_is_link" rel="nofollow" style="font-size:36px"></a>
					<span id="Yverden_z736" style="font-size:36px"></span>
			</div>
			<div class="col-lg-2">
				Panneau d'administration 
			</div>
		</div>
		<div class="row">
			<div class="col-lg-9" style="border: 1px solid black;">
				<div class="card card-fluid">
                      <!-- .card-body -->
                      <div class="card-body">
                        <h3 class="card-title mb-4"> Modifier les informations principales</h3>
                        <form action="index.php?change" method="post">
							<textarea name="content" id="editor">
								<?php
								$myfile = fopen("../display_homepage.txt", "r") or die("Unable to open file!");
								echo fread($myfile,filesize("../display_homepage.txt"));
								fclose($myfile);
								?>
							</textarea><br>
							<center><input type="submit" class="btn btn-primary" value="Modifier les informations"></center>
						</form>
                      </div><!-- /.card-body -->
                    </div>
				<h3></h3>
				
			</div>

			<div class="col-lg-3" style="border: 1px solid black;">
			<?php if(isset($errors) && !empty($errors))
			{ echo'
				<div class="alert alert-success hidden" role="alert">
					'.$errors.'
				</div>';
			}?>
				<h3> Ajouter un document ou une images </h3>
				<form action="index.php" method="post" enctype="multipart/form-data">
					Upload un fichier PDF, XLS, CSV, PNG ou JPEG :<br><br>
					<input type="file" name="the_file" id="fileToUpload">
					<input type="hidden" name="upload">
					<input type="submit" name="submit" class="btn btn-primary" value="Envoyer mon fichier"> 
				</form>
				<p><a target="_blank" href="./listImage.php"> Voir les images uploads </a></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-9" style="border: 1px solid black;">
				<h3> Modifier les sous-informations </h3>
			</div>

			<div class="col-lg-3" style="border: 1px solid black;">
				<?php	// Ouvrir et récuprer valeur dans un fichier 
			if(isset($_POST['content']))
			{	
					// ouvrir le fichier
					$monfichier = fopen('../display_homepage.txt', 'r+');
					fseek($monfichier, 0); // On remet le curseur au début du fichier
					ftruncate($monfichier,0); 
					fputs($monfichier, $_POST['content']); 
					 
					fclose($monfichier);
					header("Refresh:0; url=index.php");
			}
			
			if(isset($_GET['meteo']) AND $_GET['meteo'] == "1")
			{
				// récupéré la valeur de la ville. tester si la valeur est égale à uen ville. La stocker dans le fichier qui s'appel config. 
				if(isset($_POST['Ville']) && isset($_POST['Ville2']) && isset($_POST['Ville3']))
				{
					while (empty($_POST['Ville']) || empty($_POST['Ville2']) || empty($_POST['Ville3']))
					{
						if(empty($_POST['Ville'])){
							$_POST['Ville'] = $ville;
						}
						elseif(empty($_POST['Ville2'])){
							$_POST['Ville2'] = $ville2;
						}
						elseif(empty($_POST['Ville3'])){
							$_POST['Ville3'] = $ville3;
						}
					}
					$oldCity = $ville.'#'.$ville2.'#'.$ville3.'#';
					$villeMeteo = $_POST['Ville']. '#'. $_POST['Ville2']. '#'. $_POST['Ville3']. '#';
					/*if (TestVille($ville.'#'.$ville2.'#'.$ville3.'#'))
					{*/
					if (!Misc::replaceInfile('../config.php', $oldCity, $villeMeteo)) {
					//gestion erreur 
					
						}
						else 
							{ 
						echo '<div class="alert alert-success hidden" role="alert">
							Vous avez changé les localités de la météo avec succès !
							</div>';
							header("Refresh:4; url=index.php");
						}
					}
					else
						echo 'La ville n\'existe pas';
					
				/*}
				else echo 'Au moins une ville n\'a pas été renseignée';*/
			}
			else{
				echo'
					
				
                        <h3 class="card-title mb-4">Modifier les villes affichés pour la météo</h3>
							<table>
								<form action="index.php?meteo=1" method="POST">
								
									<div class="form-group">
										<label for="inputMeteoSuisse" class="col-sm-2 col-form-label text-center">Suisse</label>
										<div class="col-sm-10">
										  <input type="text" class="form-control" id="inputMeteoSuisse" placeholder="'.$ville.' " name="Ville" >
									</div>
									</div>
									<div class="form-group">
										<label for="inputMeteoFrance" class="col-sm-2 col-form-label text-center">France</label>
										
										<div class="col-sm-10">
										  <input type="text" class="form-control" id="inputMeteoFrance" placeholder="'.$ville2.' " name="Ville2" >
										</div>
									</div>
									<div class="form-group ">
										<label for="inputMeteoBelgique" class="col-sm-2 col-form-label text-center">Belgique</label>
										
										<div class="col-sm-10">
										  <input type="password" class="form-control" id="inputMeteoBelgique" placeholder="'.$ville3.' " name="Ville3">
										</div>
									</div>
							
									<div class="form-group">
										<input type="submit" value="Changer la météo" class="btn btn-primary">
									</div>
								</form>
							</table>';
				}?>
			</div>
		</div>
	</div>
	<script src="../ckeditor/ckeditor.js"></script>
	<script src="//widget.time.is/fr.js"></script>
	<script>
		CKEDITOR.replace( 'editor' );
		//CKEDITOR.replace( 'editorNews' );
		time_is_widget.init({Yverden_z736:{template:"TIME DATE", date_format:"dayname daynum/monthnum/year"}});
	</script>
	</body>
</html>