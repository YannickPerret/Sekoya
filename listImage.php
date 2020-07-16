<?php 
	require ('../config.php');
	$dir = "../image";
	$files = scandir($dir);
	$scope = 0;
	$entry = 0;
?>
<!doctype html>
<html lang="fr">
  <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

		<title>Sekoya - Panneau d'affichage Officiel</title>
				<script>
			var btncopy = document.querySelector('.js-copy');
if(btncopy) {
    btncopy.addEventListener('click', docopy);
}

function docopy() {

    // Cible de l'élément qui doit être copié
    var target = this.dataset.target;
    var fromElement = document.querySelector(target);
    if(!fromElement) return;

    // Sélection des caractères concernés
    var range = document.createRange();
    var selection = window.getSelection();
    range.selectNode(fromElement);
    selection.removeAllRanges();
    selection.addRange(range);

    try {
        // Exécution de la commande de copie
        var result = document.execCommand('copy');
        if (result) {
            // La copie a réussi
            alert('Copié !');
        }
    }
    catch(err) {
        // Une erreur est surevnue lors de la tentative de copie
        alert(err);
    }

    // Fin de l'opération
    selection = window.getSelection();
    if (typeof selection.removeRange === 'function') {
        selection.removeRange(range);
    } else if (typeof selection.removeAllRanges === 'function') {
        selection.removeAllRanges();
    }
}
	</script>
  </head>
  <body>
	<div class="container">
	  <div class="row" >
		<h1>Liste des images disponible dans le dossier Sekoya</h1>
		<table class="table">
			  <thead>
				<tr>
				  <th scope="col">#</th>
				  <th>Image</th>
				</tr>
			  </thead>
			  <tbody>
		<?php
		foreach ($files as $file)
		{ 
			$entry += 1;
			if ($entry <= 2 )
			{
				
			}
			else
			{
				
				$scope += 1;
				
			echo'<tr>
				  <th scope="row">'.$scope.'</th>
				  <td>
					<div class="card text-center" style="width: 18rem;">
						<img class="card-img-top" src="'.$baseSite.'/image/'.$file.'" alt="Card image cap">
						<div class="card-body">
							<h5 class="card-title">'.$file.'</h5>
							<input type="text" id="tocopy" value="'.$baseSite.'/image/'.$file.'"/>
							<button id="copy" type="button" class="js-copy btn btn-primary" data-target="#tocopy"> Copier l\'image</button>
						</div>
					</div>
				  </td>
				</tr>';
			}
		}
		?>
			</tbody>
		</table>
		</div>
	</div>

	</body>
</html>