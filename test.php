<?php

$item = array(
    "material" => "L",
    "type" => "Haaks"
);

$materials = array(
    "L" => array(
        "haaks" => array(
            "title" => "Haaks",
            "img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
            "dimension" => "mm",
            "thickness" => array("25", "32", "76"),
            "thicken" => array("36", "50", "60", "76"),
            "extra" => array(
                "abs" => array(
                    "title" => "Rand afwerken met ABSband",
                    "description" => "ABSband is een band van hardmateriaal in de zelfde kleur van het blad. De voorzijde wordt hierdoor veel beter bestand tegen stoten"
                )
            )
        ),
        "waterkering" => array(
            "title" => "Waterkering",
            "img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg"
        ),
        "afgerond" => array(
            "title" => "Afgerond",
            "img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
        )
    )
);
$test = $item["material"];

foreach($materials[$test] as $key => $value)
{
    echo "<p>" . $key . "</p>";
}

?>