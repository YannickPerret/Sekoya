<?php require("../config.php");
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
/*
function TestVille($ville1, $ville2, $ville3){
	$notgood = null;
  
		echo "lol";
		if(!$json = file_get_contents('https://www.prevision-meteo.ch/services/json/'.$ville1))
		{
			echo $ville1;
			$notgood + = $ville1;
			return echo $ville1 . " n'exite pas";
		}
		
		elseif (!$json2 = file_get_contents('https://www.prevision-meteo.ch/services/json/'.$ville2))
		{
			echo $ville2;
			$notgood += $ville2;
			return echo $ville2 . " n'existe pas";
		}
		
		elseif(!$json3 = file_get_contents('https://www.prevision-meteo.ch/services/json/'.$ville3))
		{
			echo $ville3;
			$notgood += $ville3;
			return echo $ville3 . " n'existe pas";		
}*/



ini_set('display_errors','off');

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
			echo "../image/"+$fileName;

			if ($didUpload) {
			  echo "<h2>Le fichier \"" . basename($fileName) . "\" a bien été téléchargé</h2>";
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sekoya</title>
	<script src="//cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
</head>
<body>
    <center><h1>Modification des infos pratiques</h1></center>
    <form action="index.php?change" method="post">
        <textarea name="content" id="editor">
		<?php
            $myfile = fopen("../display_histo.txt", "r") or die("Unable to open file!");
			echo fread($myfile,filesize("../display_histo.txt"));
			fclose($myfile);
		?>
        </textarea><br>
        <center><input type="submit" value="Modifier les informations"></center>
    </form>
	
	<h3> Ajouter un document ou une images </h3>
	<form action="index.php" method="post" enctype="multipart/form-data">
        Upload un fichier PDF, XLS, CSV, PNG ou JPEG :<br><br>
        <input type="file" name="the_file" id="fileToUpload">
		<input type="hidden" name="upload">
        <input type="submit" name="submit" value="Envoyer mon fichier"> 
    </form>
	<p><a target="_blank" href="./listImage.php"> Voir les images uploads </a></p>
	<?php
		// Ouvrir et récuprer valeur dans un fichier 
		if(isset($_POST['content']))
		{	
				// ouvrir le fichier
				$monfichier = fopen('../display_histo.txt', 'r+');
				fseek($monfichier, 0); // On remet le curseur au début du fichier
				ftruncate($monfichier,0); 
				fputs($monfichier, $_POST['content']); 
				 
				fclose($monfichier);
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
					echo 'Vous avez changé les localités de la météo avec succès !';
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
	<h1> Modifier les villes affichés pour la météo</h1>
	<table>
			<form action="index.php?meteo=1" method="POST">
			<tr>
				<td>Ville Suisse affiché</td>
				<td>Ville Française affiché</td>
				<td>Ville Belge affiché</td>
			</tr>
			<tr>
				<td><input type="text" name="Ville" placeholder="'.$ville.' " ></td>
				<td><input type="text" name="Ville2" placeholder="'.$ville2.'"></td>
				<td><input type="text" name="Ville3" placeholder="'.$ville3.'"></td>
			</tr>
			<tr>
				<td><input type="submit" value="Changer la météo"></td>
			</tr>
			</form>
		</table>';
	}?>
	
	<center><h1> Modifier les news Sekoya</h1></center>
	<form action="index.php?changeNew" method="post">
        <textarea name="content" id="editorNews">
		<?php
            $myfile = fopen("../display_new.txt", "r") or die("Unable to open file!");
			echo fread($myfile,filesize("../display_new.txt"));
			fclose($myfile);
		?>
        </textarea>
		<br>
        <center><input type="submit" value="Modifier les informations"></center>
    </form>
    <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'editor' );
                CKEDITOR.replace( 'editorNews' );
            </script>
</body>
</html>