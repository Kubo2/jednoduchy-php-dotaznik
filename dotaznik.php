<!doctype html>
<!--html lang="cs"-->
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Kubo2 <kelerest123@gmail.com>">
	<title>Coompiikov dotazník</title>
	<base target="_self">
	<link rel="stylesheet" href="layout.css" type="text/css">
</head>
<body>
	<h1>Stránka s dotazníkom</h1>
	<?php if(isset($_GET["nebol-odoslany"])) { ?>
	<p class="error">Dotazník nebol odoslaný. Začnite prosím tu.</p>
	<?php } ?>
	<form action="spracuj-dotaznik.php" method="post" name="dotaznik">
		<table summary="Dotazník, otázky sú mierené ako šatistika pre náš výskum.">
			<caption>Vyplňte prosím otázky v dotazníku. Ak na otázku neviete/nechcete odpovedať, vynechajte ju.</caption>
			<tbody>
				<tr>
					<th><label for="udaj1">Koľko máte rokov?</label></th>
					<td><input type="number" id="udaj1" name="udaj1" min=5 max=130></td>
				</tr>
				<tr>
					<th><label for="udaj2">Koľko máte súrodencov?</label></th>
					<td><input type="number" id="udaj2" name="udaj2" min=0></td>
				</tr>
				<tr>
					<th><label for="udaj3">Koľko litrov tekutín vypijete za deň?</label></th>
					<td><input type="number" id="udaj3" name="udaj3" min=0></td>
				</tr>
				<tr>
					<th><label for="udaj4">Koľko hodín denne presedíte za počítačom?</label></th>
					<td><input type="number" id="udaj4" name="udaj4" min=0 max=24></td>
				</tr>
				<tr>
					<th rowspan=2><label for="udaj5">Máte dojem, že ste počítačovo závislý?</label></th>
					<td><input type="radio" id="udaj5" name="udaj5" value='y'> áno</td>
				</tr>
				<tr>
					<td><input type="radio" name="udaj5" value='n' checked> nie</td>
				</tr>
				<tr>
					<th rowspan=2><label for="udaj6">Aký spôsob práce uprednosťnujete?</label></th>
					<td><input type="radio" id="udaj6" name="udaj6" value="01"> sedavý</td>
				</tr>
				<tr>
					<td><input type="radio" name="udaj6" value="02" checked> aktívny</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><input type="submit" name="dotaznik-odoslany" value="Odoslať dotazník"></td>
				</tr>
			</tfoot>
		</table>
	</form>
</body>
</html>
