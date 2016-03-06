<?php

//the current dir
$files = glob('./images/HS_kleur_hpl_fantasie/*.*');
//Stap 1:
//Maak de bestandnamen aan vb. $arrTabItems_MatKlr['cnt']['L']=array();

//Stap2: geef alle bestandnamen, zet ze in het formaat van de asso array
//Zie hieronder:

foreach($files as $file) {
    $file = pathinfo($file);
    echo "'" . str_replace(' ', '_', $file['filename']) . "' => " . "'" . $file['filename'] . "'," . "<br />";
}

//Stap3: Pas op hiermee vervang je bestandnamen, rename en encode alles filenames met een spatie binnen een dir
//foreach($files as $file) {
//    $pathInfo = pathinfo($file);
//    //print_r($pathInfo['filename']);
//    if(strstr($pathInfo['filename'], " "))
//    {
//        echo $pathInfo['filename'] . " has whitespace<br />";
//        $currentName =  $pathInfo['dirname'] . "/" . $pathInfo['filename'] . "." . $pathInfo['extension'];
//        $newName =  $pathInfo['dirname'] . "/" . str_replace(' ', '_', $pathInfo['filename']) . "." . $pathInfo['extension'];
//
//        echo $currentName . 'will be changed to ' . $newName . '<br />';
//        rename($currentName, $newName);
//        echo '<img src="' . $newName . '" />';
//    }
//}






//rename("/tmp/tmp_file.txt", "/home/user/login/docs/my_file.txt");

//'article19906306'=>'HSL 6217TC',

//foreach(glob('./images/HS_kleur_beton/*.*') as $filename){
//    echo '<pre>'; print_r($filename); echo '</pre>';
//}

//$images = scandir("./images/HS_kleur_beton", 1);
//print_r($images);


//$item = array(
//    "material" => "L",
//    "type" => "Haaks"
//);
//
//$materials = array(
//    "L" => array(
//        "haaks" => array(
//            "title" => "Haaks",
//            "img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
//            "dimension" => "mm",
//            "thickness" => array("25", "32", "76"),
//            "thicken" => array("36", "50", "60", "76"),
//            "extra" => array(
//                "abs" => array(
//                    "title" => "Rand afwerken met ABSband",
//                    "description" => "ABSband is een band van hardmateriaal in de zelfde kleur van het blad. De voorzijde wordt hierdoor veel beter bestand tegen stoten"
//                )
//            )
//        ),
//        "waterkering" => array(
//            "title" => "Waterkering",
//            "img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg"
//        ),
//        "afgerond" => array(
//            "title" => "Afgerond",
//            "img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
//        )
//    )
//);
//$test = $item["material"];
//
//foreach($materials[$test] as $key => $value)
//{
//    echo "<p>" . $key . "</p>";
//}

?>