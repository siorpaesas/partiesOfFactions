<?php

/*error_reporting(0);
ini_set('display_errors', 0);
*/
include($_SERVER['DOCUMENT_ROOT'].'/DataFetcher.php');


// Handle language
$_GET['lang'] = $_GET['lang'] ?? 'de';

switch ($_GET['lang']) {
    case 'fr':
        $lang = 'fr';
        break;
    case 'it':
        $lang = 'it';
        break;
    case 'de':
    default:
        $lang = 'de';
        break;
}

$dataFetcher = new DataFetcher();
$factions = $dataFetcher->getFactions($lang);

if ($factions === false) {
    echo "<p class='text-red'>Daten konnten nicht abgerufen werden.</p>";
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fraktionen je Partei</title>

    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<header class="bg-cyan-900 shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Parteien je Fraktion</h1>
        <nav class="text-white flex gap-x-6">
            <a class="<?php
            echo $lang == 'de' ? 'font-bold text-cyan-400' : '' ?>" href="<?php
            echo $_SERVER['PHP_SELF'] ?>?lang=de">DE</a>
            <a class="<?php
            echo $lang == 'fr' ? 'font-bold text-cyan-400' : '' ?>" href="<?php
            echo $_SERVER['PHP_SELF'] ?>?lang=fr">FR</a>
            <a class="<?php
            echo $lang == 'it' ? 'font-bold text-cyan-400' : '' ?>" href="<?php
            echo $_SERVER['PHP_SELF'] ?>?lang=it">IT</a>
        </nav>
    </div>
</header>
<main class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-3 gap-6">
        <?php
        foreach ($factions as $key => $faction) {
            echo '<div class="border border-cyan-900 rounded-md p-4 ">';
            echo '<h2 class="text-xl font-bold pb-2"><i class="fa fa-people-group"></i> '.$faction['name'].'</h2>';
            echo '<p class="text-sm my-1 text-gray-500"><i class="fa fa-fingerprint"></i> '.$faction['shortName'].', '
                .$faction['abbreviation'].'</p>';
            echo '<p><i class="fa fa-users-rectangle"></i> Vertretene Parteien</p>';
            echo '<ul class="pt-1 ml-3.5">';
            $parties = $dataFetcher->getPartiesOfFaction($faction['id'], $lang);
            echo implode("\n", array_map(fn($party) => '<li class="list-disc">'.$party.'</li>', $parties));
            echo '</ul>';
            echo '</div>';
        }
        ?>
    </div>
</main>

<footer class="bg-cyan-900/40 text-center py-4 mt-6">
    <p>&copy; 2025 . f l o w. All rights reserved.</p>
</footer>
</body>
</html>
