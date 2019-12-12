<?php 
	$dir = "../image";
	$files = scandir($dir);
?>

<h1>Liste des images disponible dans le dossier Sekoya</h1>

<table>
    <thead>
        <tr>
            <th>Nom de l'image</th>
			<th>Lien de l'image</th>
			<th>Aperçu de l'image</th>
        </tr>
    </thead>
	<tbody >
	<?php
	foreach ($files as $file)
	{
		echo '<tr ">
			<td style="border : 1px solid black;">'.$file.'</td>
			<td style="border : 1px solid black;"><a href="http://localhost:81/sekoya/V1/image/'.$file.'">http://localhost:81/sekoya/V1/image/'.$file.'</a></td>
			<td style="border : 1px solid black;"><img src="http://localhost:81/sekoya/V1/image/'.$file.'" height="25%" width="25%"/></td>
		</tr>';
	}
	?>
	</tbody>
</table>