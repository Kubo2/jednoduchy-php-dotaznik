<?php

// Bohužiaľ tento dotazník nerieši problém s niekoľkonásobým
// opakovaním zasielania formulára, ale to nie je také ťažké dorobiť
// Overovanie IP a zároveň user-agenta, alebo uložením cookie

// zjednoduší nám posielanie viacerých HTTP hlavičiek naraz
function ZasielacHlaviciek($headers) {
	if(headers_sent()) return false;
	foreach($headers as $header => $value) {
		if(substr($value, 0, 5) == "HTTP/")
			$headerString = $value;
		else {
			$header = trim($header);
			$value = trim($value);
			$headerString = "$header: $value";
		}
		header($headerString);
	}
	return true;
};

// prevedie hodnoty typu 02 apod. na relevantný text, ktorý
// môžme zapísať do datového súboru - ochrana proti zadávaniu 
// vlastných (ľubovoľných) hodnôt
function PrekladacSkratiek($abbr) {
	switch($abbr) {
		case 'y':
			$abbr = 'áno';
		break;

		case 'n':
			$abbr = 'nie';
		break;

		case '01':
			$abbr = 'sedavý';
		break;

		case '02':
			$abbr = 'aktívny';
		break;

		default: {
			$abbr = empty($abbr) ? (is_numeric($abbr) && intval($abbr) === 0 ? 0 : 'nezadal') : $abbr;
		} break;
	}

	return $abbr;
};

// formulár musí byť postnutý
if(strtoupper($_SERVER["REQUEST_METHOD"]) != "POST") {
	zasielacHlaviciek(array(
		"HTTP/1.1 405 Method Not Allowed", 
		"Allow" => "POST",
		"Content-Type" => "text/plain; charset=utf-8"
		)
	);
	echo "Tento súbor je dostupný iba po odoslaní dát POST metódou.";
	exit;
}

// ak je tento súbor postnutý, ale nie z formulára, presmerujem na formulár
if(!isset($_POST["dotaznik-odoslany"])) {
	zasielacHlaviciek(array(
		"HTTP/1.1 302 Moved Temporarily",
		"Location" => "./dotaznik.php?nebol-odoslany",
		"Content-Type" => "text/html; charset=utf-8"
		)
	);
	echo "Začnite <a href='./dotaznik.php'>na tejto adrese</a>. Nasledujte prosím odkaz.";
	exit;
}

// prejdeme si pole $_POST a hľadáme v ňom údaje
// údaje ukladáme do poľa $udaje
$udaje = array();
for($n = 1, $postSize = count($_POST); $n <= $postSize; $n++) {
	if(!isset($_POST["udaj$n"])) {
		continue; // ^^^^^^^^^^^^
	}

	$udaje[$n] = prekladacSkratiek($_POST["udaj$n"]);
}

// samotný zápis do súboru
const DATAFILE = "data-dotaznika.html"; // môžeš zmeniť, ale musíš ten súbor tiež premenovať

if(!is_file(DATAFILE)) {
	file_put_contents(DATAFILE, $data = '<table><!--...čokoľvek...--><tbody></tbody></table>');
} else {
	$data = file_get_contents(DATAFILE);
}

$end = ($end = strrpos($data, '</tbody>')) === false? strrpos($data, '</table>') : $end;
$newData = substr($data, 0, $end) . "<tr>";

foreach($udaje as $udaj) {
	$newData .= "\t\n<td>$udaj</td>";
}

$newData .= "\n</tr>" . substr($data, $end);

if(!file_put_contents(DATAFILE, $newData)) {
	zasielacHlaviciek(array(
		"HTTP/1.1 303 See Other",
		"Location" => "./dotaznik.php?" . http_build_query(array(
			"chyba-ukladania-dat" => "",
			"udaje" => $udaje
			)),
		)
	); exit;
} else {
	zasielacHlaviciek([
		'HTTP/1.1 303 See Other',
		'Location' => 'data-dotaznika.html',
	]); exit;
}
