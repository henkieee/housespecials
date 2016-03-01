<?php
//Offerte aanvragen, link in housespecials, layout gebaseerd op contact page
//http://www.mijnwebwinkel.nl/winkel/housespecials/c-1266048/offerte-aanvragen/
$GLOBALS["own"]=array();
$GLOBALS["own"]["host"]="http://".$_SERVER["HTTP_HOST"];
$GLOBALS["own"]["uri"]=rtrim(dirname($_SERVER["PHP_SELF"]), '/\\');
$GLOBALS["own"]["baseurl"]=$GLOBALS["own"]["host"].$GLOBALS["own"]["uri"]."/";
//echo "GLOBALS:<br />";print_r($GLOBALS);echo"<br />";

//read application ini settings
$ApplInit=@parse_ini_file("./offerte.ini", false);
if ($ApplInit===FALSE || $ApplInit==array()) die("Error on reading application ini file.<br>");
if (!isset($ApplInit["email_test"])) die("Missing application ini settings.<br>");
//echo "ApplInit:<br />";print_r($ApplInit);echo"<br />";


//Error handling
error_reporting($ApplInit["appl_error_reporting"]);


//#####
//DEFINE CONTENT LISTS
$action="./offerte.php"; //submit request is deze page

//----
//define sub-title text per page
$PgTitleTxtList=array();
$PgTitleTxtList[1]="1. Kies de kleur"; //"1. Kies materiaal en kleur";
$PgTitleTxtList[2]="2. Bepaal de vorm van het keukenblad en vul de maten in";
$PgTitleTxtList[3]="3. Bepaal het type blad";
$PgTitleTxtList[4]="4. Geef de zichtbare zijden van de blad(en) aan";
$PgTitleTxtList[5]="5. Geef de zijden met een staander aan";
$PgTitleTxtList[6]="6. Geef de zijden met een plint of achterwand aan";
$PgTitleTxtList[7]="7. Overige toebehoren";
$PgTitleTxtList[8]="TODO. Berekening offerte"; //TODO
$PgTitleTxtList[9]="8. Invoer contactgegevens en verzending";
//define navigation-step-tooltip text per page
$PgNavTxtList=array();
$PgNavTxtList[1]="materiaal en kleur";
$PgNavTxtList[2]="vorm en maten";
$PgNavTxtList[3]="type werkblad";
$PgNavTxtList[4]="zichtbare zijden";
$PgNavTxtList[5]="staanders";
$PgNavTxtList[6]="achterwanden of plinten";
$PgNavTxtList[7]="overige toebehoren";
$PgNavTxtList[8]="TODO. berekening"; //TODO
$PgNavTxtList[9]="contactgegevens en verzending";

//define list of input field names per page, details see functie fnc_FldDef()
//NOTE, seperator value: ','
//NOTE, reserved words: page
$PgFldList=array(); //start index at 1 for page 1, syntax: PgFldList[1]="fld1,fld2"
$PgFldList[1]="off_materiaal,off_kleur";
$PgFldList[2]="off_model,off_blad1_lengte,off_blad1_fornuis,off_blad1_lengte2,off_blad1_breedte,off_blad2_lengte,off_blad2_fornuis,off_blad2_lengte2,off_blad2_breedte,off_blad3_lengte,off_blad3_fornuis,off_blad3_lengte2,off_blad3_breedte,off_blad4_lengte,off_blad4_fornuis,off_blad4_lengte2,off_blad4_breedte,off_blad5_lengte,off_blad5_fornuis,off_blad5_lengte2,off_blad5_breedte,off_blad6_lengte,off_blad6_fornuis,off_blad6_lengte2,off_blad6_breedte,off_blad7_lengte,off_blad7_fornuis,off_blad7_lengte2,off_blad7_breedte,off_blad8_lengte,off_blad8_fornuis,off_blad8_lengte2,off_blad8_breedte,off_blad9_lengte,off_blad9_fornuis,off_blad9_lengte2,off_blad9_breedte";
$PgFldList[3]="off_type";
$PgFldList[4]="off_rand_zichtbaar_blad1_boven,off_rand_zichtbaar_blad1_rechts,off_rand_zichtbaar_blad1_onder,off_rand_zichtbaar_blad1_links,off_rand_zichtbaar_blad2_boven,off_rand_zichtbaar_blad2_rechts,off_rand_zichtbaar_blad2_onder,off_rand_zichtbaar_blad2_links,off_rand_zichtbaar_blad3_boven,off_rand_zichtbaar_blad3_rechts,off_rand_zichtbaar_blad3_onder,off_rand_zichtbaar_blad3_links,off_rand_zichtbaar_blad4_boven,off_rand_zichtbaar_blad4_rechts,off_rand_zichtbaar_blad4_onder,off_rand_zichtbaar_blad4_links,off_rand_zichtbaar_blad5_boven,off_rand_zichtbaar_blad5_rechts,off_rand_zichtbaar_blad5_onder,off_rand_zichtbaar_blad5_links,off_rand_zichtbaar_blad6_boven,off_rand_zichtbaar_blad6_rechts,off_rand_zichtbaar_blad6_onder,off_rand_zichtbaar_blad6_links,off_rand_zichtbaar_blad7_boven,off_rand_zichtbaar_blad7_rechts,off_rand_zichtbaar_blad7_onder,off_rand_zichtbaar_blad7_links,off_rand_zichtbaar_blad8_boven,off_rand_zichtbaar_blad8_rechts,off_rand_zichtbaar_blad8_onder,off_rand_zichtbaar_blad8_links,off_rand_zichtbaar_blad9_boven,off_rand_zichtbaar_blad9_rechts,off_rand_zichtbaar_blad9_onder,off_rand_zichtbaar_blad9_links";
$PgFldList[5]="off_staander,off_staander_blad1_boven,off_staander_blad1_rechts,off_staander_blad1_onder,off_staander_blad1_links,off_staander_blad2_boven,off_staander_blad2_rechts,off_staander_blad2_onder,off_staander_blad2_links,off_staander_blad3_boven,off_staander_blad3_rechts,off_staander_blad3_onder,off_staander_blad3_links,off_staander_blad4_boven,off_staander_blad4_rechts,off_staander_blad4_onder,off_staander_blad4_links,off_staander_blad5_boven,off_staander_blad5_rechts,off_staander_blad5_onder,off_staander_blad5_links,off_staander_blad6_boven,off_staander_blad6_rechts,off_staander_blad6_onder,off_staander_blad6_links,off_staander_blad7_boven,off_staander_blad7_rechts,off_staander_blad7_onder,off_staander_blad7_links,off_staander_blad8_boven,off_staander_blad8_rechts,off_staander_blad8_onder,off_staander_blad8_links,off_staander_blad9_boven,off_staander_blad9_rechts,off_staander_blad9_onder,off_staander_blad9_links";
$PgFldList[6]="off_achterwand_plinten,off_rand_achter_blad1_boven,off_rand_achter_blad1_rechts,off_rand_achter_blad1_onder,off_rand_achter_blad1_links,off_rand_achter_blad2_boven,off_rand_achter_blad2_rechts,off_rand_achter_blad2_onder,off_rand_achter_blad2_links,off_rand_achter_blad3_boven,off_rand_achter_blad3_rechts,off_rand_achter_blad3_onder,off_rand_achter_blad3_links,off_rand_achter_blad4_boven,off_rand_achter_blad4_rechts,off_rand_achter_blad4_onder,off_rand_achter_blad4_links,off_rand_achter_blad5_boven,off_rand_achter_blad5_rechts,off_rand_achter_blad5_onder,off_rand_achter_blad5_links,off_rand_achter_blad6_boven,off_rand_achter_blad6_rechts,off_rand_achter_blad6_onder,off_rand_achter_blad6_links,off_rand_achter_blad7_boven,off_rand_achter_blad7_rechts,off_rand_achter_blad7_onder,off_rand_achter_blad7_links,off_rand_achter_blad8_boven,off_rand_achter_blad8_rechts,off_rand_achter_blad8_onder,off_rand_achter_blad8_links,off_rand_achter_blad9_boven,off_rand_achter_blad9_rechts,off_rand_achter_blad9_onder,off_rand_achter_blad9_links";
$PgFldList[7]="off_dikte,off_kookplaatuitsparing,off_spoelbakuitsparing,off_spoelbaklevering,off_spoelbaktype,off_aantalgaten,off_kraanlevering,off_kraantype,off_inmeten,off_afmonteren,off_opm"; //off_TerrazzoCoating,off_randafwerking,off_opdikken
$PgFldList[8]="";
$PgFldList[9]="off_aanhef,off_naam,off_email,off_adres,off_postcode,off_woonplaats,off_land,off_telefoon,off_bericht";



//---- material and color items
//define tab page buttons, define tab content image location, file name (.jpg) and description, define css style and its DOM id reference
$arrTabItems_MatKlr=array('btn'=>NULL, 'dir'=>array(), 'cnt'=>array(), 'css'=>array());
//- define the tab page buttons: code => description

$arrTabItems_MatKlr['btn']=array(
	'BC'=>'HS Beton Classic',
	'L'=>'HS Laminaat (HPL)',
	'Graniet' => 'Graniet'
);

//- define the image (url) base location per tab page
$arrTabItems_MatKlr['dir']['BC']=$GLOBALS["own"]["baseurl"].'images/HS_kleur_beton/';

//$arrTabItems_MatKlr['dir']['BL']=$GLOBALS["own"]["baseurl"].'images/HS_kleur_beton/';
$arrTabItems_MatKlr['dir']['L']=$GLOBALS["own"]["baseurl"].'images/HS_kleur_laminaat/';

$arrTabItems_MatKlr['dir']['Graniet']=$GLOBALS["own"]["baseurl"].'images/HS_kleur_beton/';

//$arrTabItems_MatKlr['dir']['T']=$GLOBALS["own"]["baseurl"].'images/HS_kleur_terrazzo/';
//- define the content items per tab page: image file name (without .jpg) => description
//$arrTabItems_MatKlr['cnt']['BC']=array('article19601974'=>'HSB 101 (wit) Glad', 'article19601961'=>'HSB 103 (grijs) Glad', 'article19601994'=>'HSB 104 (groen/grijs) Glad', 'article19602588'=>'HSB 106 (zwart) Glad', 'article19301458'=>'HSB 201 (wit) Houtstructuur', 'article19301459'=>'HSB 203 (grijs) Houtstructuur', 'article19301460'=>'HSB 204 (groen/grijs) Houtstructuur', 'article19301462'=>'HSB 206 (zwart) Houtstructuur', 'article19301473'=>'HSB 301 (wit) Grof', 'article19301475'=>'HSB 303 (grijs) Grof', 'article19301477'=>'HSB 304 (groen/grijs) Grof', 'article19301478'=>'HSB 306 (zwart) Grof');
$arrTabItems_MatKlr['cnt']['BC']=array(
	'article23922679'=>'HSBC 1011 (beige/grijs)',
	'article19601961'=>'HSBC 1031 (grijs)',
	'article21171933'=>'HSBC 1051 (donker grijs)',
	'article30749898'=>'HSBC 1052 (antraciet grijs)'
);

$arrTabItems_MatKlr['cnt']['Graniet']=array(
	'article23922679'=>'HSBC 1011 (beige/grijs)',
	'article19601961'=>'HSBC 1031 (grijs)',
	'article21171933'=>'HSBC 1051 (donker grijs)',
	'article30749898'=>'HSBC 1052 (antraciet grijs)'
);

//$arrTabItems_MatKlr['cnt']['BL']=array('article23850354'=>'HSBL 4031', 'article23850374'=>'HSBL 4041', 'article23850384'=>'HSBL 4051');
$arrTabItems_MatKlr['cnt']['L']=array(
	'article19906306'=>'HSL 6217TC',
	'article19906317'=>'HSL 6282TC',
	'article19906564'=>'HSL 7654CS',
	'article19906573'=>'HSL 7654MP',
	'article19899398'=>'HSL 1027TC',
	'article19899389'=>'HSL 1027CS',
	'article19899405'=>'HSL 1179TC',
	'article19906326'=>'HSL 6284TC',
	'article19899408'=>'HSL 1200CS',
	'article19906584'=>'HSL 7684TC',
	'article19899358'=>'HSL 1026CS',
	'article19899382'=>'HSL 1026TC',
	'article19906589'=>'HSL 7919TC',
	'article19899426'=>'HSL 1257TC',
	'article19899430'=>'HSL 1290 MP',
	'article19906344'=>'HSL 6352TC',
	'article19899434'=>'HSL 1358TC',
	'article19899435'=>'HSL 2277Q',
	'article19906603'=>'HSL 7920TC',
	'article19906355'=>'HSL 6401TC',
	'article19906611'=>'HSL 8194MP',
	'article19906617'=>'HSL 8340MP',
	'article19899452'=>'HSL 3394Q',
	'article19906366'=>'HSL 6423CS',
	'article19899455'=>'HSL 3405-60',
	'article19906625'=>'HSL 8341MP',
	'article19906633'=>'HSL 8342CS',
	'article19906640'=>'HSL 8342MO',
	'article19906391'=>'HSL 6423TC',
	'article19899481'=>'HSL 3455-60',
	'article19906654'=>'HSL 9510SM',
	'article19906404'=>'HSL 6424CS',
	'article19899494'=>'HSL 3462-60',
	'article19906666'=>'HSL 9610SM',
	'article19899501'=>'HSL 4087NP',
	'article19906420'=>'HSL 6424TC',
	'article19906679'=>'HSL F22-007ST22',
	'article19901306'=>'HSL 4882-90',
	'article19906691'=>'HSL F22-008ST22',
	'article19901316'=>'HSL 4890-90',
	'article19906700'=>'HSL F275ST15',
	'article19901331'=>'HSL 4896RU',
	'article19906453'=>'HSL 6456VV',
	'article19901086'=>'HSL 4206-60',
	'article19901348'=>'HSL 5152VV',
	'article19901096'=>'HSL 4272VV',
	'article19901357'=>'HSL 5320TR',
	'article19901102'=>'HSL 4314-60',
	'article19906484'=>'HSL 6533Q',
	'article19906740'=>'HSL H3306ST15',
	'article19901110'=>'HSL 4318-60',
	'article19906750'=>'HSL H3704ST15',
	'article19901381'=>'HSL 5410TR',
	'article19901128'=>'HSL 4393-60',
	'article19901389'=>'HSL 5447TR',
	'article19906515'=>'HSL 7486MP',
	'article19901149'=>'HSL 4447EM',
	'article19906525'=>'HSL 7487MP',
	'article19901413'=>'HSL 5681TR',
	'article19906283'=>'HSL 6216TC',
	'article19906540'=>'HSL 7515TC',
	'article19901166'=>'HSL 4601TR',
	'article19901423'=>'HSL 5687TR',
	'article19906291'=>'HSL 6217CS',
	'article19901174'=>'HSL 4619-60',
	'article19906551'=>'HSL 7646TC'
);

//$arrTabItems_MatKlr['cnt']['T']=array('article19105244'=>'HST 2012', 'article19105258'=>'HST 2015', 'article19105265'=>'HST 2016', 'article19602334'=>'HST 2018', 'article19301514'=>'HST 2020', 'article19301544'=>'HST 2134'); //'article19301516'=>'HST 2032', 'article19301518'=>'HST 2040', 'article19301520'=>'HST 2081', 'article19301522'=>'HST 2092', 'article19301524'=>'HST 2108', 'article19301531'=>'HST 2113', 'article19301538'=>'HST 2129', 'article19397551'=>'HST 2130'
//- define the css style and its DOM id reference, used in tab buttons and tab content container and in scripting
$arrTabItems_MatKlr['css_id']['tabBtnContainer']='tabBut_MatKlr';
$arrTabItems_MatKlr['css_id']['tabCntContainer']='tabCnt_MatKlr';

//---- shape of kitchen plates to show measure items
//define tab page buttons, define css style and its DOM id reference
$arrTabItems_Shape=array('btn'=>NULL, 'css'=>array());
//- define the tab page buttons: code => description
$arrTabItems_Shape['btn']=array('R'=>'Recht', 'L'=>'L-vorm', 'U'=>'U-vorm', 'E'=>'Eiland');
//- define the css style and its DOM id reference, used in tab buttons and tab content items and in scripting
$arrTabItems_Shape['css_id']['tabBtnContainer']='tabBut_Vorm';
$arrTabItems_Shape['css_id']['tabCntContainer']='tabCnt_Vorm';

//---- washbasin type and article items
//define tab page buttons, define tab content image location, file name (.jpg) and description, define css style and its DOM id reference
$arrTabItems_Spoelbak=array('btn'=>NULL, 'dir'=>array(), 'cnt'=>array(), 'css'=>array());
//- define the tab page buttons: code => description; !!! NOTE: code can be of type 'BC', 'BL', 'T' and 'RVS_*' !!!
$arrTabItems_Spoelbak['btn']=array(/*'BC'=>'HS Betonnen spoelbak', 'BL'=>'HS Betonnen spoelbak', 'T'=>'HS Terrazzo spoelbak', 'RVS_TEGEL'=>'HS RVS Spoelbak betegeld', 'RVS_REGINOX'=>'RVS Reginox spoelbak', 'RVS_BARONGA'=>'RVS Baronga spoelbak', 'RVS_FRANKE'=>'RVS Franke spoelbak',*/ 'RVS_LORREINE'=>'RVS Lorreine spoelbak');
//- define the image (url) base location per tab page
//$arrTabItems_Spoelbak['dir']['BC']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_beton/';
//$arrTabItems_Spoelbak['dir']['BL']=$arrTabItems_Spoelbak['dir']['BC'];
//$arrTabItems_Spoelbak['dir']['T']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_terrazzo/';
//$arrTabItems_Spoelbak['dir']['RVS_TEGEL']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_RVS_tegel/';
//$arrTabItems_Spoelbak['dir']['RVS_REGINOX']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_RVS_Reginox/';
//$arrTabItems_Spoelbak['dir']['RVS_BARONGA']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_RVS_Baronga/';
//$arrTabItems_Spoelbak['dir']['RVS_FRANKE']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_RVS_Franke/';
$arrTabItems_Spoelbak['dir']['RVS_LORREINE']=$GLOBALS["own"]["baseurl"].'images/HS_spoelbak_Lorreine/';
//- define the content items per tab page: image file name (without .jpg) => array of description & price
//$arrTabItems_Spoelbak['cnt']['BC']=array('article20357656'=> array('desc'=>'HS Betonnen spoelbak betegeld (klein)', 'price'=>429.00), 'article19434513'=>array('desc'=>'HS Betonnen spoelbak betegeld (groot)', 'price'=>699.00), 'article19101297'=>array('desc'=>'HS Betonnen spoelbak (klein)', 'price'=>379.00), 'article19434446'=>array('desc'=>'HS Betonnen spoelbak (groot)', 'price'=>679.00));
//$arrTabItems_Spoelbak['cnt']['BL']=$arrTabItems_Spoelbak['cnt']['BC'];
//$arrTabItems_Spoelbak['cnt']['T']=array('article20360251'=>array('desc'=>'HS Terrazzo spoelbak (klein)', 'price'=>759.00), 'article20360327'=>array('desc'=>'HS Terrazzo spoelbak betegeld (groot)', 'price'=>798.00), 'article20360391'=>array('desc'=>'HS Terrazzo spoelbak betegeld (klein)', 'price'=>598.00), 'article20359989'=>array('desc'=>'HS Terrazzo spoelbak (groot)', 'price'=>959.00));
//$arrTabItems_Spoelbak['cnt']['RVS_TEGEL']=array('article19101454'=>array('desc'=>'RVS Spoelbak betegeld (klein)', 'price'=>559.00), 'article19101454_groot'=>array('desc'=>'HS RVS spoelbak betegeld (groot)', 'price'=>619.00));
//$arrTabItems_Spoelbak['cnt']['RVS_REGINOX']=array('article19466401'=>array('desc'=>'Reginox Boston', 'price'=>119.00), 'article19466466'=>array('desc'=>'Reginox Chicago', 'price'=>169.00), 'article19534487'=>array('desc'=>'Reginox Denver', 'price'=>79.00), 'article19534567'=>array('desc'=>'Reginox Orlando', 'price'=>169.00), 'article19534660'=>array('desc'=>'Reginox Princess 80', 'price'=>239.00), 'article19534758'=>array('desc'=>'Reginox Queen 60', 'price'=>279.00));
//$arrTabItems_Spoelbak['cnt']['RVS_BARONGA']=array('article19534875'=>array('desc'=>'Baronga 34', 'price'=>209.00), 'article19534976'=>array('desc'=>'Baronga 3415/1534', 'price'=>299.00), 'article19535135'=>array('desc'=>'Baronga 40', 'price'=>149.00), 'article19535247'=>array('desc'=>'Baronga 46', 'price'=>329.00), 'article19535292'=>array('desc'=>'Baronga 55', 'price'=>189.00), 'article19535338'=>array('desc'=>'Baronga 80', 'price'=>319.00));
//$arrTabItems_Spoelbak['cnt']['RVS_FRANKE']=array('article19465465'=>array('desc'=>'Franke Gallassio GAX 110.450', 'price'=>189.00), 'article19465681'=>array('desc'=>'Franke Kubux KBX 110.34-T/210.34-T', 'price'=>239.00), 'article19465807'=>array('desc'=>'Franke Kubux KBX 110.45-T/210.45-T', 'price'=>244.00), 'article19465903'=>array('desc'=>'Franke Kubux KBX 110.55-T/210.55-T', 'price'=>269.00), 'article19434579'=>array('desc'=>'Franke Bellissimo BEX 210.50', 'price'=>179.00), 'article19466166'=>array('desc'=>'Franke Bellissimo BEX 260', 'price'=>299.00), 'article19466293'=>array('desc'=>'Franke CPX P 260', 'price'=>315.00), 'article19466057'=>array('desc'=>'Franke Quadrant QTX 210', 'price'=>79.00, 'nonDiscountPrice'=>99.00));
$arrTabItems_Spoelbak['cnt']['RVS_LORREINE']=array(
	'article21412761'=>array('desc'=>'Lorreine 40R', 'price'=>229.00, 'nonDiscountPrice'=>358.00),
	'article21412846'=>array('desc'=>'Lorreine 50R', 'price'=>259.00, 'nonDiscountPrice'=>379.00),
	'article21412685'=>array('desc'=>'Lorreine 34R', 'price'=>315.00, 'nonDiscountPrice'=>440.00),
	'article21412927'=>array('desc'=>'Lorreine 74R', 'price'=>329.00, 'nonDiscountPrice'=>553.00),
	'article21413116'=>array('desc'=>'Lorreine 1534R', 'price'=>409.00, 'nonDiscountPrice'=>637.00),
	'article21413272'=>array('desc'=>'Lorreine 3434R', 'price'=>479.00, 'nonDiscountPrice'=>789.00),
	'article21412452'=>array('desc'=>'Lorreine 17R', 'price'=>274.00, 'nonDiscountPrice'=>408.00),
	'article21412813'=>array('desc'=>'Lorreine 40V', 'price'=>309.00, 'nonDiscountPrice'=>472.00),
	'article21412884'=>array('desc'=>'Lorreine 50V', 'price'=>319.00, 'nonDiscountPrice'=>509.00),
	'article21412716'=>array('desc'=>'Lorreine 34V', 'price'=>329.00, 'nonDiscountPrice'=>517.00),
	'article21413050'=>array('desc'=>'Lorreine 74V', 'price'=>349.00, 'nonDiscountPrice'=>628.00),
	'article21413227'=>array('desc'=>'Lorreine 1534V', 'price'=>479.00, 'nonDiscountPrice'=>739.00),
	'article21413298'=>array('desc'=>'Lorreine 3434V', 'price'=>569.00, 'nonDiscountPrice'=>869.00),
	'article21412648'=>array('desc'=>'Lorreine 17V', 'price'=>345.00, 'nonDiscountPrice'=>545.00),
	'article33304489'=>array('desc'=>'RVS Tegelspoelbak 40 cm', 'price'=>525.00, 'nonDiscountPrice'=>683.00),
	'article33304550'=>array('desc'=>'RVS Tegelspoelbak 50 cm', 'price'=>540.00, 'nonDiscountPrice'=>699.00),
//    'article30270053'=>array('desc'=>'Lorreine RVS Inzetbak', 'price'=>99.00, 'nonDiscountPrice'=>149.00),
);
//- define the css style and its DOM id reference, used in tab buttons and tab content container and in scripting
$arrTabItems_Spoelbak['css_id']['tabBtnContainer']='tabBut_Spoelbak';
$arrTabItems_Spoelbak['css_id']['tabCntContainer']='tabCnt_Spoelbak';

//---- watertap type and article items
//define tab page buttons, define tab content image location, file name (.jpg) and description, define css style and its DOM id reference
$arrTabItems_Kraan=array('btn'=>NULL, 'dir'=>array(), 'cnt'=>array(), 'css'=>array());
//- define the tab page buttons: code => description; !!! NOTE: code can be of type 'RVS_*' !!! (no other types yet)
$arrTabItems_Kraan['btn']=array('RVS_LORREINE'=>'RVS Lorreine keukenkraan');
//- define the image (url) base location per tab page
$arrTabItems_Kraan['dir']['RVS_LORREINE']=$GLOBALS["own"]["baseurl"].'images/HS_kraan_Lorreine/';
//- define the content items per tab page: image file name (without .jpg) => array of description & price
$arrTabItems_Kraan['cnt']['RVS_LORREINE']=array('article30270805'=>array('desc'=>'Lorreine Frome', 'price'=>229.00, 'nonDiscountPrice'=>549.00), 'article30270814'=>array('desc'=>'Lorreine Medway', 'price'=>229.00, 'nonDiscountPrice'=>549.00), 'article30270820'=>array('desc'=>'Lorreine Mersey', 'price'=>229.00, 'nonDiscountPrice'=>549.00), 'article30270828'=>array('desc'=>'Lorreine Tweed', 'price'=>269.00, 'nonDiscountPrice'=>599.00), 'article30270782'=>array('desc'=>'Lorreine Lune', 'price'=>269.00, 'nonDiscountPrice'=>599.00));
//- define the css style and its DOM id reference, used in tab buttons and tab content container and in scripting
$arrTabItems_Kraan['css_id']['tabBtnContainer']='tabBut_Kraan';
$arrTabItems_Kraan['css_id']['tabCntContainer']='tabCnt_Kraan';

//#####
//email data, see page 6

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head prefix="og: http://ogp.me/ns#">
	<title>Offerte aanvraag 24u | HS-INTERIEUR</title>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta name="keywords" content="Keukenbladen van Beton, Terrazzo en Kunstof (HPL), Spoelbakken tegen de scherpste prijs!!" />
	<meta name="description" content="Als u op werkdagen via onze website een offerte aanvraagt mailen wij deze binnen 24 uur naar u toe. Klik op de afbeelding." />
	<meta name="googlebot" content="noarchive" /><meta name="robots" content="index,follow,noodp,noydir" />
	<meta content="website" property="og:type">
	<meta content="https://static.mijnwebwinkel.nl/winkel/hs-interieur/images/offerte%20aanvraag%20NIEUW.jpg" property="og:image">
	<meta content="https://static.mijnwebwinkel.nl/winkel/hs-interieur/images/HS%20eiken%20keuken%20met%20beton.jpg" property="og:image">
	<meta content="https://static.mijnwebwinkel.nl/winkel/hs-interieur/images/WIT%20200.jpg" property="og:image">
	<meta content="http://www.hs-interieur.nl/c-2730047/offerte-aanvraag-24u/" property="og:url"><!--link rel="stylesheet" type="text/css" href="http://www.hs-interieur.nl/stylesheet.css" /-->
	<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS["own"]["baseurl"] ?>gg3CAaSNVt15r1QURYhAF5WIgCZO045Wn0p3.css">
	<link href="http://static.mijnwebwinkel.nl/static/favicon_blank.ico" type="image/x-icon" rel="icon">
	<link href="http://static.mijnwebwinkel.nl/static/favicon_blank.ico" rel="shortcut icon">
	<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS["own"]["baseurl"] ?>offerte.css">
	<script src="<?php echo $GLOBALS["own"]["baseurl"] ?>offerte.js" type="text/javascript"></script>
	<noscript><style>ul.products li{ opacity: 1 !important; }</style></noscript>
	<style type="text/css">
		.fancybox-margin{margin-right:17px;}
	</style>
</head>
<body>
<div id="bg-container">

	<!--header-->
	<div class="pane paneBar paneBarTop">
		<div class="inner"></div>
		<div class="clearfix"></div>
	</div>
	<div id="header">
		<div class="inner">
			<div class="header-space"></div>
			<a href="http://www.hs-interieur.nl/">
				<div class="header-image"></div>
			</a>

			<!-- -->
			<div class="pane paneBar paneBarHeader">
				<div class="inner">
					<div class="left">
						<ul class="left navigation">
							<li><a class="no_underline" href="http://www.hs-interieur.nl/">HOME</a></li><li class="divider"><hr></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2729972/voorstellen/">VOORSTELLEN</a></li><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-2725218/betonnen-keukenbladen/">BETONNEN KEUKENBLADEN</a><ul class="dropdown"><li class="divider"><hr></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-1265882/beton-classic-kleuren/">Beton Classic kleuren</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2371152/speciale-behandeling-tegen-vlekken/">Speciale behandeling tegen vlekken.</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-1274371/veel-gestelde-vragen-en-adviezen/">Veel gestelde vragen en adviezen</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-1274380/hoe-te-bestellen/">Hoe te bestellen?</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-1274372/garantie-service/">Garantie &amp; Service</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2088508/offerte-aanvraag/">Offerte aanvraag</a></li></ul></li><li class="divider"><hr></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2730209/betonnen-keukenbladen-ikea-keukens/">BETONNEN KEUKENBLADEN &amp; IKEA KEUKENS</a></li><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-2730210/beton-meer/">BETON &amp; MEER</a><ul class="dropdown"><li class="divider"><hr></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2951922/gashaardtafels/">Gashaardtafels</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2731229/betonnen-salontafels/">Betonnen salontafels</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2731231/betonnen-bijzettafels/">Betonnen bijzettafels</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2731236/betonnen-bank/">Betonnen bank</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2731238/bijzettafel-houtenstronk/">Bijzettafel houtenstronk</a></li></ul></li><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-2738999/foto-s/">FOTO'S</a><ul class="dropdown"><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2426880/foto-s-betonnen-keukenbladen/">Foto's betonnen keukenbladen</a></li></ul></li><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-2729540/rvs-spoelbakken/">RVS SPOELBAKKEN</a><ul class="dropdown"><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2737878/onderhoud-rvs-spoelbakken/">Onderhoud RVS Spoelbakken</a></li></ul></li><li class="divider"><hr></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2729593/rvs-keukenkranen/">RVS KEUKENKRANEN</a></li><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-2179117/raam-vloerdecoratie/">RAAM &amp; VLOERDECORATIE</a><ul class="dropdown"><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-2086807/raamdecoratie/">RAAMDECORATIE</a><ul class="dropdown"><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092710/overgordijnen/">OVERGORDIJNEN</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092711/vitrage/">VITRAGE</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092713/vouwgordijnen/">VOUWGORDIJNEN</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092715/paneelgordijnen/">PANEELGORDIJNEN</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092716/rolgordijnen/">ROLGORDIJNEN</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092717/duo-rolgordijnen/">DUO ROLGORDIJNEN</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092718/plisse-gordijnen/">PLISS� GORDIJNEN</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092719/jaloezieen-horizontaal/">JALOEZIE�N HORIZONTAAL</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092720/jaloezieen-verticaal/">JALOEZIE�N VERTICAAL</a></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2092721/offerte-aanvragen-raamdecoratie/">OFFERTE AANVRAGEN RAAMDECORATIE</a></li></ul></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2086809/vloerdecoratie/">VLOERDECORATIE</a></li></ul></li><li class="divider"><hr></li><li class="divider"><hr></li><li class="has-dropdown"><a class="no_underline" href="http://www.hs-interieur.nl/c-1122128/contact/">CONTACT</a><ul class="dropdown"><li><a class="no_underline" href="http://www.hs-interieur.nl/c-1464716/algemene-voorwaarden-en-privacybeleid/">Algemene voorwaarden en privacybeleid</a></li></ul></li><li class="divider"><hr></li><li><a class="no_underline" href="http://www.hs-interieur.nl/c-2752133/facebook/">FACEBOOK</a></li><li class="divider"><hr></li><li class=" active"><a class="no_underline" href="#">OFFERTE AANVRAAG 24u</a></li>
						</ul>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="header-shadow"></div>
		</div>
		<div class="clearfix"></div>
	</div>
	<!-- header end -->

	<div id="maincontainer" class="page">
		<div id="contentwrapper">

			<!-- menu left -->
			<!-- menu left end -->

			<!-- google_ad_section_start -->
			<div class="content_block">
				<div class="content">
					<?php
					//allow all chars, prevent automatic character escape
					if (get_magic_quotes_gpc()) {
						function stripslashes_deep($value) {
							$value=is_array($value)?array_map('stripslashes_deep', $value):stripslashes($value);
							return $value;
						}
						$_POST=array_map('stripslashes_deep', $_POST);
						//$_GET=array_map('stripslashes_deep', $_GET);
						$_COOKIE=array_map('stripslashes_deep', $_COOKIE);
						//$_REQUEST=array_map('stripslashes_deep', $_REQUEST);
					}

					//check the input/content of a field
					function fnc_ChkFldInput(&$fldval, $whitelist, $minlen, $maxlen, $label, &$msg) {
						//check allowable characters
						if (strpos($whitelist, PHP_EOL, 0)!==FALSE) $whitelist.="\r\n"; //enclosed in double-quotes to interpret escape sequences for special characters
						$tmp="";
						$ValidChr=TRUE;
						$i=0;
						do {
							$chr=substr($fldval, $i, 1);
							if ($chr<="") break;
							if (strpos($whitelist, $chr, 0)===FALSE) {
								$ValidChr=FALSE;
							} else {
								$tmp.=$chr;
							}
							$i++;
						} while ($chr>"");
						$fldval=$tmp;
						if ($ValidChr==FALSE) {
							$msg.=$label." bevat ongeldige tekens, controleer opnieuw uw gegevens.<br />";
						}
						//check string length
						$len=strlen($fldval);
						if ($len>$maxlen) {
							$fldval=substr($fldval, 0, $maxlen);
							$msg.=$label." mag niet meer dan ".$maxlen." tekens bevatten, controleer opnieuw uw gegevens.<br />";
						}
						if ($len<$minlen) {
							$msg.="Voer ".strtolower($label[0]).substr($label,1)." in.<br />";
						}
						if ($msg>"") $msg='<font color="#FF0000">'.$msg.'</font>';
					} //fnc_ChkFldInput

					//define the field properties and its html output
					//	mode	=init, initialize field
					//		=ctrl, initialize and check field input
					//		=html, output html layout
					//		=email, output email layout
					function fnc_FldDef($mode, $arrTabItems, &$PgFldVal, $page, $fld, &$result, &$msg, &$ContentPg) {

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
							),
							"Graniet" => array(
								"haaks" => array(
									"title" => "Haaks",
									"img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
									"dimension" => "mm",
									"thickness" => array("20", "30"),
									"thicken" => array("40", "60", "80")
								),
								"waterkering" => array(
									"title" => "Waterkering",
									"img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
									"dimension" => "mm",
									"thickness" => array("43"),
								)
							),
							'BC'=> array(
								"haaks" => array(
									"title" => "Haaks",
									"img" => "images/HS_kleur_laminaat/bladen/haaks/VAM32 web.jpg",
									"dimension" => "mm",
									"thickness" => array("25"),
									"thicken" => array("32", "38")
								)
							)
						);

						//test
						$PgFldVal[9]["off_aanhef"] = "M";
						$PgFldVal[9]["off_naam"] = "Henk Test";
						$PgFldVal[9]["off_email"] = "henkiexxx4@gmail.com";
						$PgFldVal[9]["off_adres"] = "testsstraat 1";
						$PgFldVal[9]["off_postcode"] = "1111AA";
						$PgFldVal[9]["off_woonplaats"] = "Teststad";
						$PgFldVal[9]["off_land"] = "NL";
						$PgFldVal[9]["off_telefoon"] = "0679924590";
						$PgFldVal[9]["off_bericht"] = "Test bericht";


						$i=0;
						switch ($fld) {
							case "off_materiaal":
							case "off_kleur":
								$fld_materiaal="off_materiaal";
								$val_materiaal=$PgFldVal[$page][$fld_materiaal];
								$fld_kleur="off_kleur";
								$val_kleur=$PgFldVal[$page][$fld_kleur];
								if ($fld==$fld_materiaal) {
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										if ($mode=="ctrl") { //materiaal is required
											$valid=FALSE;
											foreach($arrTabItems['btn'] as $btnId=>$btnTxt) {
												if ($val_materiaal==$btnId) {$valid=TRUE; break;}
											}
											if ($valid==FALSE) {
												//$msg[$fld]='<font color="#FF0000">Kies het materiaal via een van de tab bladen hieronder en selecteer vervolgens een kleur.</font>';
												$msg[$fld]='<font color="#FF0000">Selecteer een kleur uit onderstaande voorbeelden.</font>';
												$val_materiaal="";
												$PgFldVal[$page][$fld_kleur]=""; //reset color
												$result=FALSE;
											}
										}
										$PgFldVal[$page][$fld]=$val_materiaal;
									}
									if ($mode=="proposal" || $mode=="email") {
										$valtxt=$arrTabItems['btn'][$val_materiaal];
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Type materiaal:</td>';
										$ContentPg[$page].='<td valign="top">'.htmlspecialchars($valtxt).'</td></tr>'.PHP_EOL;
										if ($mode=="proposal") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val_materiaal).'" />'.PHP_EOL;
									}
									if ($mode=="html") {
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val_materiaal).'" />'.PHP_EOL;
										if ($msg[$fld_materiaal]>"") {
											$ContentPg[$page].='<div class="FrmMsgDiv">'.$msg[$fld_materiaal].'</div>'.PHP_EOL; //global error msg
											$ContentPg[$page].='<div class="FrmEmptyDiv"></div>'.PHP_EOL;
										}
									}
								}
								if ($fld==$fld_kleur) {
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										if ($mode=="ctrl" && $val_materiaal>"") {
											if (!is_array($arrTabItems['cnt'][$val_materiaal])) {
												$val_kleur=""; //reset, not relevant
											} else { //color is required
												$valid=FALSE;
												foreach($arrTabItems['cnt'][$val_materiaal] as $cntId=>$cntTxt) {
													if ($val_kleur==$cntId) {$valid=TRUE; break;}
												}
												if ($valid==FALSE) { //shouldn't occur
													$msg[$fld]='<font color="#FF0000">Selecteer een kleur uit een van de tab bladen hieronder.</font>';
													$val_kleur=""; //reset
													$result=FALSE;
												}
											}
										}
										$PgFldVal[$page][$fld]=$val_kleur;
									}
									if ($mode=="proposal" || $mode=="email") {
										if ($val_kleur>"") {
											$valtxt=$arrTabItems['cnt'][$val_materiaal][$val_kleur];
										} else {
											$valtxt="n.v.t.";
										}
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kleur:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td valign="top">'.htmlspecialchars($valtxt).'<br />'.PHP_EOL;
										if ($val_kleur>"") {
											$ContentPg[$page].='	<img src="'.$arrTabItems['dir'][$val_materiaal].$val_kleur.'.jpg" class="SelectCntBlock"/><br />'.PHP_EOL;
										}
										$ContentPg[$page].='</td></tr>'.PHP_EOL;
										if ($mode=="proposal") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val_kleur).'" />'.PHP_EOL;
									}
									if ($mode=="html") {
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val_kleur).'" />'.PHP_EOL;
										if ($msg[$fld_kleur]>"") $ContentPg[$page].='<div class="FrmMsgDiv">'.$msg[$fld_kleur].'</div>'.PHP_EOL; //global error msg
										$ContentPg[$page].='<div class="FrmEmptyDiv"></div>'.PHP_EOL; //empty row
									}
								}
								break;
							case "off_model":
							case "off_blad1_lengte":
								$fld_model="off_model";
								$val_model=$PgFldVal[$page][$fld_model];
								if ($fld==$fld_model) {
									if ($mode=="init") {
										if ($val_model=="") { //choose value to open the first tab
											$array_keys=array_keys($arrTabItems['btn']);
											$val_model=$array_keys[0];
										}
									}
									$val=$val_model;
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										if ($mode=="ctrl") { //shape is required
											$valid=FALSE;
											foreach($arrTabItems['btn'] as $btnId=>$btnTxt) {
												if ($val==$btnId) {$valid=TRUE; break;}
											}
											if ($valid==FALSE) {
												$msg[$fld]='<font color="#FF0000">Kies de vorm van het keukenblad via een van de tab bladen hieronder.</font>';
												$val="";
												$result=FALSE;
											}
										}
										$PgFldVal[$page][$fld]=$val;
									}
									if ($mode=="proposal" || $mode=="email") {
										$valtxt=$arrTabItems['btn'][$val];
										$ContentPg[$page].='<div style="position:relative;display:block;">'.PHP_EOL;
										$ContentPg[$page].='<table cellpadding="4" cellspacing="0">'.PHP_EOL;
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Vorm keukenblad:</td>';
										$ContentPg[$page].='<td valign="top">'.htmlspecialchars($valtxt).'<br /></td></tr>'.PHP_EOL;
										$ContentPg[$page].='</table>'.PHP_EOL;
										$ContentPg[$page].='</div>'.PHP_EOL;
										if ($mode=="proposal") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
									}
									if ($mode=="html") {
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
										if ($msg[$fld]>"") {
											$ContentPg[$page].='<div class="FrmMsgDiv">'.$msg[$fld].'</div>'.PHP_EOL; //global error msg
											$ContentPg[$page].='<div class="FrmEmptyDivCnt"></div>'.PHP_EOL;
										}

										//build tab buttons
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabBtnContainer'].'" class="tabBtnContainer">'.PHP_EOL;
										$ContentPg[$page].='<span class="tabBtnEmpty"></span>'.PHP_EOL;
										foreach($arrTabItems['btn'] as $btnId=>$btnTxt) {
											$ContentPg[$page].='<a href="#" id="'.$arrTabItems['css_id']['tabBtnContainer'].'_'.$btnId.'" onclick="return tabBtnClick_shape(this, \''.$arrTabItems['css_id']['tabBtnContainer'].'\', \''.$fld.'\', \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="'.($btnId==$val?'tabBtnActive':'tabBtnInactive').'" name="'.$btnId.'">';
											$ContentPg[$page].='<div class="tabBtnText"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td class="tabBtnText">'.$btnTxt.'</td></tr></table></div>';
											$ContentPg[$page].='</a>'.PHP_EOL;
										}
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabBtnContainer
									}
								}

								if ($fld=="off_blad1_lengte") {
									$AnyMeasures=FALSE;
									for ($i=1; $i<10; $i++) {
										$fld_L="off_blad".$i."_lengte";
										$fld_Stove="off_blad".$i."_fornuis";
										$fld_L2="off_blad".$i."_lengte2";
										$fld_B="off_blad".$i."_breedte";
										$val_L=$PgFldVal[$page][$fld_L];
										$val_B=$PgFldVal[$page][$fld_B];

										$val_Stove=$PgFldVal[$page][$fld_Stove];
										if ($val_Stove=="") $val_Stove="0";
										if ($val_Stove=="on") $val_Stove="1";
										if ($val_Stove!="0" && $val_Stove!="1") $val_Stove="1";
										if ($mode=="init" || $mode=="ctrl") {
											$PgFldVal[$page][$fld_Stove]=$val_Stove;
										}

										$val_L2=$PgFldVal[$page][$fld_L2];
										if ($mode=="init" || $mode=="ctrl") {
											if ($val_Stove!="1" && $val_L2>"") {
												$val_L2="";
												$PgFldVal[$page][$fld_L2]=$val_L2;
											}
										}

										if ($val_model=="") {$val_L=""; $val_Stove=""; $val_L2=""; $val_B="";} //reset
										if ($mode=="init" || $mode=="ctrl") {
											$msg[$fld_L]="";
											$msg[$fld_L2]="";
											$msg[$fld_B]="";
											$whitelist="0123456789.";
											$label_L=($val_Stove!="1"?"De lengte (in cm) van blad ".$i:"De lengte (in cm) van linker gedeelte van blad ".$i);
											$label_L2="De lengte (in cm) van het rechter gedeelte van blad ".$i;
											$label_B="De breedte (in cm) van blad ".$i;
											if ($val_model>"" && $mode=="ctrl") {
												switch ($val_model) { //empty non-relevant measures, NOTE: shape and configuration of the measure form fields are hard-coded
													case "R":
														if ($i>1) {$val_L=""; $val_Stove=""; $val_L2=""; $val_B="";}
														break;
													case "L":
														if ($i>4) {$val_L=""; $val_Stove=""; $val_L2=""; $val_B="";}
														break;
													case "U":
														if ($i>3) {$val_L=""; $val_Stove=""; $val_L2=""; $val_B="";}
														break;
													case "E":
														if ($i==1 || $i==3 || $i==6 || $i==9) {$val_Stove=""; $val_L2="";}
														break;
													default:
														die("Error: onbekende definitie bij vorm '".$val_model."' en afmetingen keukenblad.<br />");
												}
												fnc_ChkFldInput($val_L, $whitelist, 0, 7, $label_L, $msg[$fld_L]);
												if ($val_Stove=="1") {
													fnc_ChkFldInput($val_L2, $whitelist, 0, 7, $label_L2, $msg[$fld_L2]);
												}
												fnc_ChkFldInput($val_B, $whitelist, 0, 5, $label_B, $msg[$fld_B]);
												if ($val_L>"" && $val_B=="") {
													$msg[$fld_B].='<font color="#FF0000">De breedte van blad '.$i.' ontbreekt.</font><br />';
												} elseif ($val_L=="" && $val_B>"") {
													$msg[$fld_L].='<font color="#FF0000">De lengte van blad '.$i.' ontbreekt.</font><br />';
												} elseif ($val_Stove=="1" && $val_L2=="" && $val_B>"") {
													$msg[$fld_L].='<font color="#FF0000">De lengte van het rechter gedeelte van blad '.$i.' ontbreekt.</font><br />';
												}
											}
											if ($msg[$fld_L]>"") {$msg[$fld_L]='<div class="FrmMsgDiv">'.$msg[$fld_L].'</div>'.PHP_EOL; $result=FALSE;}
											if ($msg[$fld_L2]>"") {$msg[$fld_L]='<div class="FrmMsgDiv">'.$msg[$fld_L2].'</div>'.PHP_EOL; $result=FALSE;}
											if ($msg[$fld_B]>"") {$msg[$fld_B]='<div class="FrmMsgDiv">'.$msg[$fld_B].'</div>'.PHP_EOL; $result=FALSE;}
											if ($val_L>"" || $val_B>"") $AnyMeasures=TRUE;
											$PgFldVal[$page][$fld_L]=$val_L;
											$PgFldVal[$page][$fld_Stove]=$val_Stove;
											$PgFldVal[$page][$fld_L2]=$val_L2;
											$PgFldVal[$page][$fld_B]=$val_B;
										}
									}
									if ($mode=="ctrl" && $result==TRUE && $AnyMeasures==FALSE) {
										$msg[$fld].='<div class="FrmMsgDiv"><font color="#FF0000">U dient van minimaal 1 blad de maten in te voeren.</font></div>'.PHP_EOL;
										$result=FALSE;
									}

									if ($mode=="proposal" || $mode=="email") {
										for ($i=1; $i<10; $i++) {
											$fld_L="off_blad".$i."_lengte";
											$fld_Stove="off_blad".$i."_fornuis";
											$fld_L2="off_blad".$i."_lengte2";
											$fld_B="off_blad".$i."_breedte";
											$val_L=$PgFldVal[$page][$fld_L];
											$val_Stove=$PgFldVal[$page][$fld_Stove];
											$val_L2=$PgFldVal[$page][$fld_L2];
											$val_B=$PgFldVal[$page][$fld_B];
											$ContentPg[$page].='<input type="hidden" name="'.$fld_L.'" value="'.htmlspecialchars($val_L).'" />'.PHP_EOL; //measure L (main or 1st part when stove present)
											$ContentPg[$page].='<input type="hidden" name="'.$fld_Stove.'" value="'.htmlspecialchars($val_Stove).'" />'.PHP_EOL; //Stove
											$ContentPg[$page].='<input type="hidden" name="'.$fld_L2.'" value="'.htmlspecialchars($val_L2).'" />'.PHP_EOL; //measure L (2nd part)
											$ContentPg[$page].='<input type="hidden" name="'.$fld_B.'" value="'.htmlspecialchars($val_B).'" />'.PHP_EOL; //measure B
										}
									}

									if ($mode=="html") {
										$ContentPg[$page].='<div class="FrmEmptyDivCnt"></div>'.PHP_EOL;
										//info and error msg on measure fields
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_msg" class="'.($val_model>""?"tabCntActive":"tabCntInactive").'" style="position:relative;">'.PHP_EOL;
										$TxtShapeSingle="Geef de lengte en breedte (in cm) van het keukenblad.";
										$TxtShapeMulti="Geef de lengte en breedte (in cm) van de keukenblad(en).";
										$TxtShapeL="<br />Kies de linker of de rechter L-opstelling.";
										$TxtShapeE="<br />Kies een van onderstaande eiland opstellingen.";
										$TxtStove="<br /><p class='attention'>Gebruik het vinkje indien het blad onderbroken wordt door een los fornuis.</p>";
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_msg_txt" class="FrmMsgDiv">'.($val_model=="R"?$TxtShapeSingle:$TxtShapeMulti).($val_model=="L"?$TxtShapeL:NULL).($val_model=="E"?$TxtShapeE:NULL).$TxtStove.'</div>'.PHP_EOL;
										if ($mode=="html") {
											$ContentPg[$page].='<script type="text/javascript">'.PHP_EOL;
											$ContentPg[$page].='var sTxtShapeSingle = "'.$TxtShapeSingle.'";'.PHP_EOL;
											$ContentPg[$page].='var sTxtShapeMulti = "'.$TxtShapeMulti.'";'.PHP_EOL;
											$ContentPg[$page].='var sTxtShapeL = "'.$TxtShapeL.'";'.PHP_EOL;
											$ContentPg[$page].='var sTxtShapeE = "'.$TxtShapeE.'";'.PHP_EOL;
											$ContentPg[$page].='var sTxtStove = "'.$TxtStove.'";'.PHP_EOL;
											$ContentPg[$page].='</script>'.PHP_EOL;
										}
										for ($i=1; $i<10; $i++) {
											$fld_L="off_blad".$i."_lengte";
											$fld_L2="off_blad".$i."_lengte2";
											$fld_B="off_blad".$i."_breedte";
											if ($msg[$fld_L]>"") $ContentPg[$page].=$msg[$fld_L].PHP_EOL;
											if ($msg[$fld_L2]>"") $ContentPg[$page].=$msg[$fld_L2].PHP_EOL;
											if ($msg[$fld_B]>"") $ContentPg[$page].=$msg[$fld_B].PHP_EOL;
										}
										$ContentPg[$page].='<div class="FrmEmptyDivCnt"></div>'.PHP_EOL;
										$ContentPg[$page].='</div>'.PHP_EOL;

										//build tab content
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'" class="tabCntScroll_shape '.($val_model>""?"tabCntActive":"tabCntInactive").'">';
										$plateXshift=10; //shift all plates to right
										$inpW=50; $inpH=18; //width and height of input field
										$inpStoveW=13; $inpStoveH=12; //width and height of checkbox field
										$inpStoveXshift=-2; $inpStoveHYshift=8; $inpStoveVYshift=2; //excentricity of checkbox widget
										for ($i=1; $i<10; $i++) {
											$display="none";
											$anyStove=true;
											switch ($i) {
												case 1:
													$plateX=40+$plateXshift; $plateY=$inpH; //Left Top absolute position of a plate block
													$plateW=160; $plateH=60; $orientation="H"; //width, height, and orientation (=Horizontal/Vertical) of a plate
													if ($val_model!="E") {$plateX=10+$plateXshift; $plateW=220; $plateH=40;}
													if ($val_model>"") $display="inline-block";
													if ($val_model=="E") $anyStove=false;
													break;
												case 2:
													$plateX=390+$plateXshift; $plateY=$inpH;
													$plateW=220; $plateH=40; $orientation="H";
													if ($val_model=="L" || $val_model=="U") {$plateX=10+$plateXshift; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
													if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
													break;
												case 3:
													$plateX=420+$plateXshift; $plateY=$inpH+$inpH+40+40;
													$plateW=160; $plateH=60; $orientation="H";
													if ($val_model=="L") {$plateX=390+$plateXshift; $plateY=$inpH; $plateW=220; $plateH=40;}
													if ($val_model=="U") {$plateX=185+$plateXshift; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
													if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
													if ($val_model=="E") $anyStove=false;
													break;
												case 4:
													$plateX=10+$plateXshift; $plateY=$inpH+300;
													$plateW=220; $plateH=40; $orientation="H";
													if ($val_model=="L") {$plateX=565+$plateXshift; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
													if ($val_model=="L" || $val_model=="E") $display="inline-block";
													break;
												case 5:
													$plateX=10+$plateXshift; $plateY=$inpH+300+$inpH+40+2;
													$plateW=45; $plateH=160; $orientation="V";
													if ($val_model=="E") $display="inline-block";
													break;
												case 6:
													$plateX=170+$plateXshift; $plateY=$inpH+300+$inpH+40+2+160-120;
													$plateW=60; $plateH=120; $orientation="V";
													if ($val_model=="E") {$display="inline-block"; $anyStove=false;}
													break;
												case 7:
													$plateX=390+$plateXshift; $plateY=$inpH+300;
													$plateW=220; $plateH=40; $orientation="H";
													if ($val_model=="E") $display="inline-block";
													break;
												case 8:
													$plateX=565+$plateXshift; $plateY=$inpH+300+$inpH+40+2;
													$plateW=45; $plateH=160; $orientation="V";
													if ($val_model=="E") $display="inline-block";
													break;
												case 9:
													$plateX=390+$plateXshift; $plateY=$inpH+300+$inpH+40+2+160-120;
													$plateW=60; $plateH=120; $orientation="V";
													if ($val_model=="E") {$display="inline-block"; $anyStove=false;}
													break;
											}
											$fld_L="off_blad".$i."_lengte";
											$fld_Stove="off_blad".$i."_fornuis";
											$fld_L2="off_blad".$i."_lengte2";
											$fld_B="off_blad".$i."_breedte";
											$val_L=$PgFldVal[$page][$fld_L];
											$val_Stove=$PgFldVal[$page][$fld_Stove];
											$val_L2=$PgFldVal[$page][$fld_L2];
											$val_B=$PgFldVal[$page][$fld_B];
											$displayStove=(($anyStove==false || $val_Stove!="1")?"none":"inline-block");
											$borderTest=NULL; //"border:1px solid red;"; //=TEST
											$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'" style="position:absolute;display:'.$display.';top:'.$plateY.'px;left:'.$plateX.'px;height:'.($inpH+2+$plateH).'px;width:'.($plateW+$inpW).'px;'.$borderTest.'">';
											if ($orientation=="H") { //horizontal
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_plate" class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:'.($inpH+2+1).'px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;'.$borderTest.'"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_plateNr"  style="text-align:left;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($i==1?'BLAD ':'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;').$i.'</td></tr></table></div>'.PHP_EOL; //plate
												$tmp=(int)(0.5*($plateW-$plateH));
												$ContentPg[$page].='<img id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mStoveImage" src="'.$GLOBALS["own"]["baseurl"].'images/stove.png" title="Fornuis" style="position:absolute;display:'.$displayStove.';top:'.(0.5*$plateH+2).'px;left:'.$tmp.'px;width:'.$plateH.'px;height:'.($plateH-4).'px;" />'.PHP_EOL; //stove image
												$tmp=(int)(0.5*($plateW-$inpStoveW));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mStove" style="position:absolute;display:'.($anyStove?'inline-block':'none').';top:'.($plateH+$inpStoveH+$inpStoveHYshift).'px;left:'.($tmp+$inpStoveXshift).'px;height:'.$inpStoveH.'px;width:'.$inpStoveW.'px;"><input type="checkbox" name="'.$fld_Stove.'" '.($val_Stove=="1"?'checked="checked" ':NULL).'onclick="ToggleStoveElem(\''.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'\');" class="checkbox" /></div>'.PHP_EOL; //stove checkbox at bottom
												$tmp=(int)(($anyStove==false?0.5:0.2)*($plateW-$inpW));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mL" style="position:absolute;display:inline-block;top:0px;left:'.$tmp.'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="text" name="'.$fld_L.'" value="'.htmlspecialchars($val_L).'" class="boxlook_content" style="width:100%;" /></div>'.PHP_EOL; //measure L
												$tmp=(int)(0.8*($plateW-$inpW));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mL2" style="position:absolute;display:'.$displayStove.';top:0px;left:'.$tmp.'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="text" name="'.$fld_L2.'" value="'.htmlspecialchars($val_L2).'" class="boxlook_content" style="width:100%;" /></div>'.PHP_EOL; //measure L2
												$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mB" style="position:absolute;display:inline-block;top:'.$tmp.'px;left:'.$plateW.'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="text" name="'.$fld_B.'" value="'.htmlspecialchars($val_B).'" class="boxlook_content" style="width:100%;" /></div>'.PHP_EOL; //measure B
											}
											if ($orientation=="V") { //vertical
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_plate" class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:0px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;'.$borderTest.'"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_plateNr" style="text-align:center;vertical-align:bottom;line-height:620%;color:#001050;font-size:12px;">'.$i.'</td></tr></table></div>'.PHP_EOL; //plate
												$tmp=(int)(0.5*($plateH-$plateW));
												$ContentPg[$page].='<img id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mStoveImage" src="'.$GLOBALS["own"]["baseurl"].'images/stove.png" title="Fornuis" style="position:absolute;display:'.$displayStove.';top:'.($tmp+2).'px;left:1px;width:'.($plateW-4).'px;height:'.($plateW-4).'px;" />'.PHP_EOL; //stove image
												$tmp=(int)(0.5*($plateH-($inpStoveH+2)));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mStove" style="position:absolute;display:'.($anyStove?'inline-block':'none').';top:'.($tmp+$inpStoveVYshift).'px;left:'.(-$inpStoveW+$inpStoveXshift).'px;height:'.$inpStoveH.'px;width:'.$inpStoveW.'px;"><input type="checkbox" name="'.$fld_Stove.'" '.($val_Stove=="1"?'checked="checked" ':NULL).'onclick="ToggleStoveElem(\''.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'\');" class="checkbox" /></div>'.PHP_EOL; //stove checkbox at left
												$tmp=(int)(($anyStove==false?0.5:0.2)*($plateH-($inpH+2)));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mL" style="position:absolute;display:inline-block;top:'.$tmp.'px;left:'.$plateW.'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="text" name="'.$fld_L.'" value="'.htmlspecialchars($val_L).'" class="boxlook_content" style="width:100%;" /></div>'.PHP_EOL; //measure L
												$tmp=(int)(0.8*($plateH-($inpH+2)));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mL2" style="position:absolute;display:'.$displayStove.';top:'.$tmp.'px;left:'.$plateW.'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="text" name="'.$fld_L2.'" value="'.htmlspecialchars($val_L2).'" class="boxlook_content" style="width:100%;" /></div>'.PHP_EOL; //measure L2
												$tmp=(int)(0.5*($plateW-$inpW));
												$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_'.$i.'_mB" style="position:absolute;display:inline-block;top:'.$plateH.'px;left:'.$tmp.'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="text" name="'.$fld_B.'" value="'.htmlspecialchars($val_B).'" class="boxlook_content" style="width:100%;" /></div>'.PHP_EOL; //measure B
											}
											$ContentPg[$page].='</div>'.PHP_EOL;
										}
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer

										//if ($mode=="email") $ContentPg[$page].='</td></tr>'.PHP_EOL;
									}
								}
							case "off_blad1_fornuis":
							case "off_blad1_lengte2":
							case "off_blad1_breedte":
							case "off_blad2_lengte":
							case "off_blad2_fornuis":
							case "off_blad2_lengte2":
							case "off_blad2_breedte":
							case "off_blad3_lengte":
							case "off_blad3_fornuis":
							case "off_blad3_lengte2":
							case "off_blad3_breedte":
							case "off_blad4_lengte":
							case "off_blad4_fornuis":
							case "off_blad4_lengte2":
							case "off_blad4_breedte":
							case "off_blad5_lengte":
							case "off_blad5_fornuis":
							case "off_blad5_lengte2":
							case "off_blad5_breedte":
							case "off_blad6_lengte":
							case "off_blad6_fornuis":
							case "off_blad6_lengte2":
							case "off_blad6_breedte":
							case "off_blad7_lengte":
							case "off_blad7_fornuis":
							case "off_blad7_lengte2":
							case "off_blad7_breedte":
							case "off_blad8_lengte":
							case "off_blad8_fornuis":
							case "off_blad8_lengte2":
							case "off_blad8_breedte":
							case "off_blad9_lengte":
							case "off_blad9_fornuis":
							case "off_blad9_lengte2":
							case "off_blad9_breedte":
								break;

							case "off_rand_zichtbaar_blad1_boven":
								$pg=2;
								$fld_model="off_model";
								$val_model=$PgFldVal[$pg][$fld_model];
								$edgename=array(1=>'boven', 2=>'rechts', 3=>'onder', 4=>'links');
								$AnyMeasures=FALSE;
								for ($i=1; $i<10; $i++) {
									$fld_L="off_blad".$i."_lengte";
									$fld_B="off_blad".$i."_breedte";
									$val_L=$PgFldVal[$pg][$fld_L];
									$val_B=$PgFldVal[$pg][$fld_B];
									if ($val_model=="") {$val_L=""; $val_B="";} //not relevant
									if ($val_L>"" && $val_B>"") $AnyMeasures=TRUE;
									for ($j=1; $j<5; $j++) {
										$fld="off_rand_zichtbaar_blad".$i."_".$edgename[$j];
										$val=$PgFldVal[$page][$fld];
										if ($val=="") $val="0";
										if ($val=="on") $val="1";
										if ($val!="0" && $val!="1") $val="1";
										if ($mode=="init" || $mode=="ctrl") {
											$PgFldVal[$page][$fld]=$val; //NOTE: within mode==html/email, the non-visable field values are reset as well
											$msg[$fld]="";
										}
									}
								}
								$fld="off_rand_zichtbaar_blad1_boven";
								if ($mode=="init" && $AnyMeasures==FALSE) {
									$msg[$fld].='<div class="FrmMsgDiv"><font color="#FF0000">U dient eerst van minimaal 1 blad de maten in te voeren.<br>Ga terug naar pagina '.$pg.'.</font></div>'.PHP_EOL;
									$result=FALSE;
								}

								if ($mode=="proposal" || $mode=="email") {
									for ($i=1; $i<10; $i++) {
										for ($j=1; $j<5; $j++) {
											$fld="off_rand_zichtbaar_blad".$i."_".$edgename[$j];
											$val=$PgFldVal[$page][$fld];
											$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
										}
									}
								}

								if ($AnyMeasures==FALSE && $mode=="html") {
									if ($msg[$fld]>"") $ContentPg[$page].=$msg[$fld].PHP_EOL;
								}
								if ($AnyMeasures==TRUE && $mode=="html") {
									$ContentPg[$page].='<div class="FrmEmptyDivCnt"></div>'.PHP_EOL;

									//build tab content
									$ContentPg[$page].='<div class="tabCntScroll_shape "tabCntActive"">';
									$inpW=13; $inpH=13; //width and height of input field
									$inpW=13; $inpH=12; //width and height of input field
									$inpXshift=-4; $inpYshift=-3; //excentricity of checkbox widget
									$inpXshift=-4; $inpYshift=-2; //excentricity of checkbox widget
									for ($i=1; $i<10; $i++) {
										$display="none";
										$DisplayEdges=$edgename; //ommit edges on plates fixed together, only when plate is displayed, only at edge on top on vertical plate may be absent
										switch ($i) {
											case 1:
												$plateX=40; $plateY=$inpH; //Left Top absolute position of a plate block
												$plateW=160; $plateH=60; $orientation="H"; //width, height, and orientation (=Horizontal/Vertical) of a plate
												if ($val_model!="E") {$plateX=10; $plateW=220; $plateH=40;}
												if ($val_model>"") $display="inline-block";
												break;
											case 2:
												$plateX=390; $plateY=$inpH;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="L" || $val_model=="U") {$plateX=10; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
												if ($val_model=="U" || $val_model=="L") $DisplayEdges[1]="";
												break;
											case 3:
												$plateX=420; $plateY=$inpH+$inpH+40+40;
												$plateW=160; $plateH=60; $orientation="H";
												if ($val_model=="L") {$plateX=390; $plateY=$inpH; $plateW=220; $plateH=40;}
												if ($val_model=="U") {$plateX=185; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
												if ($val_model=="U") $DisplayEdges[1]="";
												break;
											case 4:
												$plateX=10; $plateY=$inpH+300;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="L") {$plateX=565; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="E") $display="inline-block";
												if ($val_model=="L") $DisplayEdges[1]="";
												break;
											case 5:
												$plateX=10; $plateY=$inpH+300+$inpH+40+2;
												$plateW=45; $plateH=160; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												$DisplayEdges[1]="";
												break;
											case 6:
												$plateX=170; $plateY=$inpH+300+$inpH+40+2+160-120;
												$plateW=60; $plateH=120; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												break;
											case 7:
												$plateX=390; $plateY=$inpH+300;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="E") $display="inline-block";
												break;
											case 8:
												$plateX=565; $plateY=$inpH+300+$inpH+40+2;
												$plateW=45; $plateH=160; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												$DisplayEdges[1]="";
												break;
											case 9:
												$plateX=390; $plateY=$inpH+300+$inpH+40+2+160-120;
												$plateW=60; $plateH=120; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												break;
										}
										$PlateMeasures=FALSE;
										$fld_L="off_blad".$i."_lengte";
										$fld_B="off_blad".$i."_breedte";
										$val_L=$PgFldVal[$pg][$fld_L];
										$val_B=$PgFldVal[$pg][$fld_B];
										if ($val_model=="") {$val_L=""; $val_B="";} //not relevant
										if ($val_L>"" && $val_B>"") $PlateMeasures=TRUE;
										if ($display=="none" || $PlateMeasures!=TRUE) {
											for ($j=1; $j<5; $j++) {
												$fld="off_rand_zichtbaar_blad".$i."_".$edgename[$j];
												$val="0";
												$PgFldVal[$page][$fld]=$val;
												$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
											}
										}
										if ($display!="none") {
											$borderTest=NULL; //"border:1px solid red;"; //=TEST
											$ContentPg[$page].='<div style="position:absolute;display:'.$display.';top:'.$plateY.'px;left:'.($plateX+$inpW).'px;height:'.($inpH+2+$plateH).'px;width:'.($plateW+$inpW).'px;'.($PlateMeasures!=TRUE?'opacity:0.5;filter:Alpha(opacity=50);':NULL).'">';
											if ($orientation=="H") { //horizontal
												$ContentPg[$page].='<div class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:'.($inpH+2+1).'px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td style="text-align:center;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">'.($i==1?'BLAD ':NULL).$i.'</td></tr></table></div>'.PHP_EOL; //plate
												if ($PlateMeasures==TRUE) {
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[1]; //top
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.$inpYshift.'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[2]; //right
													$val=$PgFldVal[$page][$fld];
													$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.($plateW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;'.$borderTest.'"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													//$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.$tmp.'px;left:'.$plateW.'px;height:'.$inpH.'px;width:'.$inpW.'px;'.$borderTest.'"></div>'.PHP_EOL; //TEST shift of checkbox excentricity
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[3]; //bottom
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($plateH+$inpH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[4]; //left
													$val=$PgFldVal[$page][$fld];
													$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.(-$inpW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
												}
											}
											if ($orientation=="V") { //vertical
												$ContentPg[$page].='<div class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:0px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td style="text-align:center;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">'.$i.'</td></tr></table></div>'.PHP_EOL; //plate
												if ($PlateMeasures==TRUE) {
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[1]; //top
													if ($DisplayEdges[1]=="") {
														$val="0";
														$PgFldVal[$page][$fld]=$val;
														$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
													} else {
														$val=$PgFldVal[$page][$fld];
														$tmp=(int)(0.5*($plateW-$inpW));
														$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.(-$inpH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													}
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[2]; //right
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.($plateW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[3]; //bottom
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($plateH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_zichtbaar_blad".$i."_".$edgename[4]; //left
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.(-$inpW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
												}
											}
											$ContentPg[$page].='</div>'.PHP_EOL;
										}
									}
									$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer

									//if ($mode=="email") $ContentPg[$page].='</td></tr>'.PHP_EOL;
								}

							case "off_rand_zichtbaar_blad1_rechts":
							case "off_rand_zichtbaar_blad1_onder":
							case "off_rand_zichtbaar_blad1_links":
							case "off_rand_zichtbaar_blad2_boven":
							case "off_rand_zichtbaar_blad2_rechts":
							case "off_rand_zichtbaar_blad2_onder":
							case "off_rand_zichtbaar_blad2_links":
							case "off_rand_zichtbaar_blad3_boven":
							case "off_rand_zichtbaar_blad3_rechts":
							case "off_rand_zichtbaar_blad3_onder":
							case "off_rand_zichtbaar_blad3_links":
							case "off_rand_zichtbaar_blad4_boven":
							case "off_rand_zichtbaar_blad4_rechts":
							case "off_rand_zichtbaar_blad4_onder":
							case "off_rand_zichtbaar_blad4_links":
							case "off_rand_zichtbaar_blad5_boven":
							case "off_rand_zichtbaar_blad5_rechts":
							case "off_rand_zichtbaar_blad5_onder":
							case "off_rand_zichtbaar_blad5_links":
							case "off_rand_zichtbaar_blad6_boven":
							case "off_rand_zichtbaar_blad6_rechts":
							case "off_rand_zichtbaar_blad6_onder":
							case "off_rand_zichtbaar_blad6_links":
							case "off_rand_zichtbaar_blad7_boven":
							case "off_rand_zichtbaar_blad7_rechts":
							case "off_rand_zichtbaar_blad7_onder":
							case "off_rand_zichtbaar_blad7_links":
							case "off_rand_zichtbaar_blad8_boven":
							case "off_rand_zichtbaar_blad8_rechts":
							case "off_rand_zichtbaar_blad8_onder":
							case "off_rand_zichtbaar_blad8_links":
							case "off_rand_zichtbaar_blad9_boven":
							case "off_rand_zichtbaar_blad9_rechts":
							case "off_rand_zichtbaar_blad9_onder":
							case "off_rand_zichtbaar_blad9_links":
								break;

							case "off_staander":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="JN";
									if ($val=="") $val="N";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 1, "Zijden met staander", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									//NOTE: part of check is done also in field "off_staander_blad1_boven"
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="html") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Staanders meeleveren:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:200px">';
									$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="N" '.($val=="N"?'checked="checked" ':NULL).'class="radio" />&nbsp;Nee&nbsp;&nbsp;&nbsp;';
									$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="J" '.($val=="J"?'checked="checked" ':NULL).'class="radio" />&nbsp;Ja';
									$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/staander.png" title="staander" style="width:180px;height:160px;" /></td></tr>'.PHP_EOL;

									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].=($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								if ($mode=="proposal" || $mode=="email") {
									$valtxt="Nee"; $valimg=""; $title="";
									if ($val=="J") { $valtxt="Ja"; $valimg="staander"; $title="staander"; }
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Staanders meeleveren:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:200px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
									if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:125px;height:80px;" />';
									$ContentPg[$page].='</td></tr></table>'.PHP_EOL;
									$ContentPg[$page].='</td></tr>'.PHP_EOL;
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							case "off_staander_blad1_boven":
								$pg=2;
								$fld_model="off_model";
								$val_model=$PgFldVal[$pg][$fld_model];
								$fld_staander="off_staander";
								$val_staander=$PgFldVal[$page][$fld_staander];
								$edgename=array(1=>'boven', 2=>'rechts', 3=>'onder', 4=>'links');
								$AnyMeasures=FALSE;
								for ($i=1; $i<10; $i++) {
									$fld_L="off_blad".$i."_lengte";
									$fld_B="off_blad".$i."_breedte";
									$val_L=$PgFldVal[$pg][$fld_L];
									$val_B=$PgFldVal[$pg][$fld_B];
									if ($val_model=="") {$val_L=""; $val_B="";} //not relevant
									if ($val_L>"" && $val_B>"") $AnyMeasures=TRUE;
									for ($j=1; $j<5; $j++) {
										$fld="off_staander_blad".$i."_".$edgename[$j];
										$val=$PgFldVal[$page][$fld];
										if ($val=="") $val="0";
										if ($val=="on") $val="1";
										if ($val!="0" && $val!="1") $val="1";
										if ($mode=="init" || $mode=="ctrl") {
											$PgFldVal[$page][$fld]=$val; //NOTE: within mode==html/email, the non-visable field values are reset as well
											$msg[$fld]="";
										}
									}
								}
								$fld="off_staander_blad1_boven";
								if ($mode=="init" && $AnyMeasures==FALSE) {
									$msg[$fld].='<div class="FrmMsgDiv"><font color="#FF0000">U dient eerst van minimaal 1 blad de maten in te voeren.<br>Ga terug naar pagina '.$pg.'.</font></div>'.PHP_EOL;
									$result=FALSE;
								}

								if ($mode=="ctrl" && $AnyMeasures==TRUE) { //NOTE: check added, required value for "off_staander" when any sides are set
									$AnyEdgesSet=FALSE;
									for ($i=1; $i<10; $i++) {
										for ($j=1; $j<5; $j++) {
											$fld="off_staander_blad".$i."_".$edgename[$j];
											if ($PgFldVal[$page][$fld]==1) {
												$AnyEdgesSet=TRUE;
												break;
											}
										}
									}
									if ($AnyEdgesSet==TRUE && $val_staander=="N") {
										$msg[$fld_staander].='<div class="FrmMsgDiv"><font color="#FF0000">Onduidelijke keuze. Geef geen zijdes aan of kies Ja om de aangevinkte zijdes met een staander mee te leveren.</font></div>'.PHP_EOL;
										$result=FALSE;
									}
									if ($AnyEdgesSet==FALSE && $val_staander!="N") {
										//$val_staander=""; //n.v.t.
										//$PgFldVal[$page][$fld_staander]=$val_staander;
										$msg[$fld_staander].='<div class="FrmMsgDiv"><font color="#FF0000">Vink de zijdes aan die met een staander moeten worden uitgevoerd.</font></div>'.PHP_EOL;
										$result=FALSE;
									}
								}

								if ($mode=="proposal" || $mode=="email") {
									for ($i=1; $i<10; $i++) {
										for ($j=1; $j<5; $j++) {
											$fld="off_staander_blad".$i."_".$edgename[$j];
											$val=$PgFldVal[$page][$fld];
											$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
										}
									}
								}

								if ($AnyMeasures==FALSE && $mode=="html") {
									if ($msg[$fld]>"") $ContentPg[$page].=$msg[$fld].PHP_EOL;
								}
								if ($AnyMeasures==TRUE && $mode=="html") {
									$ContentPg[$page].='<div class="FrmEmptyDivCnt"></div>'.PHP_EOL;

									//build tab content
									$ContentPg[$page].='<div class="tabCntScroll_shape "tabCntActive"">';
									$inpW=13; $inpH=13; //width and height of input field
									$inpW=13; $inpH=12; //width and height of input field
									$inpXshift=-4; $inpYshift=-3; //excentricity of checkbox widget
									$inpXshift=-4; $inpYshift=-2; //excentricity of checkbox widget
									for ($i=1; $i<10; $i++) {
										$display="none";
										$DisplayEdges=$edgename; //ommit edges on plates fixed together, only when plate is displayed, only at edge on top on vertical plate may be absent
										switch ($i) {
											case 1:
												$plateX=40; $plateY=$inpH; //Left Top absolute position of a plate block
												$plateW=160; $plateH=60; $orientation="H"; //width, height, and orientation (=Horizontal/Vertical) of a plate
												if ($val_model!="E") {$plateX=10; $plateW=220; $plateH=40;}
												if ($val_model>"") $display="inline-block";
												break;
											case 2:
												$plateX=390; $plateY=$inpH;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="L" || $val_model=="U") {$plateX=10; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
												if ($val_model=="U" || $val_model=="L") $DisplayEdges[1]="";
												break;
											case 3:
												$plateX=420; $plateY=$inpH+$inpH+40+40;
												$plateW=160; $plateH=60; $orientation="H";
												if ($val_model=="L") {$plateX=390; $plateY=$inpH; $plateW=220; $plateH=40;}
												if ($val_model=="U") {$plateX=185; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
												if ($val_model=="U") $DisplayEdges[1]="";
												break;
											case 4:
												$plateX=10; $plateY=$inpH+300;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="L") {$plateX=565; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="E") $display="inline-block";
												if ($val_model=="L") $DisplayEdges[1]="";
												break;
											case 5:
												$plateX=10; $plateY=$inpH+300+$inpH+40+2;
												$plateW=45; $plateH=160; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												$DisplayEdges[1]="";
												break;
											case 6:
												$plateX=170; $plateY=$inpH+300+$inpH+40+2+160-120;
												$plateW=60; $plateH=120; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												break;
											case 7:
												$plateX=390; $plateY=$inpH+300;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="E") $display="inline-block";
												break;
											case 8:
												$plateX=565; $plateY=$inpH+300+$inpH+40+2;
												$plateW=45; $plateH=160; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												$DisplayEdges[1]="";
												break;
											case 9:
												$plateX=390; $plateY=$inpH+300+$inpH+40+2+160-120;
												$plateW=60; $plateH=120; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												break;
										}
										$PlateMeasures=FALSE;
										$fld_L="off_blad".$i."_lengte";
										$fld_B="off_blad".$i."_breedte";
										$val_L=$PgFldVal[$pg][$fld_L];
										$val_B=$PgFldVal[$pg][$fld_B];
										if ($val_model=="") {$val_L=""; $val_B="";} //not relevant
										if ($val_L>"" && $val_B>"") $PlateMeasures=TRUE;
										if ($display=="none" || $PlateMeasures!=TRUE) {
											for ($j=1; $j<5; $j++) {
												$fld="off_staander_blad".$i."_".$edgename[$j];
												$val="0";
												$PgFldVal[$page][$fld]=$val;
												$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
											}
										}
										if ($display!="none") {
											$borderTest=NULL; //"border:1px solid red;"; //=TEST
											$ContentPg[$page].='<div style="position:absolute;display:'.$display.';top:'.$plateY.'px;left:'.($plateX+$inpW).'px;height:'.($inpH+2+$plateH).'px;width:'.($plateW+$inpW).'px;'.($PlateMeasures!=TRUE?'opacity:0.5;filter:Alpha(opacity=50);':NULL).'">';
											if ($orientation=="H") { //horizontal
												$ContentPg[$page].='<div class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:'.($inpH+2+1).'px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td style="text-align:center;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">'.($i==1?'BLAD ':NULL).$i.'</td></tr></table></div>'.PHP_EOL; //plate
												if ($PlateMeasures==TRUE) {
													$fld="off_staander_blad".$i."_".$edgename[1]; //top
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.$inpYshift.'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_staander_blad".$i."_".$edgename[2]; //right
													$val=$PgFldVal[$page][$fld];
													$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.($plateW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;'.$borderTest.'"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													//$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.$tmp.'px;left:'.$plateW.'px;height:'.$inpH.'px;width:'.$inpW.'px;'.$borderTest.'"></div>'.PHP_EOL; //TEST shift of checkbox excentricity
													$fld="off_staander_blad".$i."_".$edgename[3]; //bottom
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($plateH+$inpH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_staander_blad".$i."_".$edgename[4]; //left
													$val=$PgFldVal[$page][$fld];
													$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.(-$inpW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
												}
											}
											if ($orientation=="V") { //vertical
												$ContentPg[$page].='<div class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:0px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td style="text-align:center;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">'.$i.'</td></tr></table></div>'.PHP_EOL; //plate
												if ($PlateMeasures==TRUE) {
													$fld="off_staander_blad".$i."_".$edgename[1]; //top
													if ($DisplayEdges[1]=="") {
														$val="0";
														$PgFldVal[$page][$fld]=$val;
														$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
													} else {
														$val=$PgFldVal[$page][$fld];
														$tmp=(int)(0.5*($plateW-$inpW));
														$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.(-$inpH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													}
													$fld="off_staander_blad".$i."_".$edgename[2]; //right
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.($plateW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_staander_blad".$i."_".$edgename[3]; //bottom
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($plateH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_staander_blad".$i."_".$edgename[4]; //left
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.(-$inpW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
												}
											}
											$ContentPg[$page].='</div>'.PHP_EOL;
										}
									}
									$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer

									//if ($mode=="email") $ContentPg[$page].='</td></tr>'.PHP_EOL;
								}

								if ($mode=="proposal" || $mode=="email") { //TODO build plate & measures & edges (visible & non-visible) in 1 table
								}
							case "off_staander_blad1_rechts":
							case "off_staander_blad1_onder":
							case "off_staander_blad1_links":
							case "off_staander_blad2_boven":
							case "off_staander_blad2_rechts":
							case "off_staander_blad2_onder":
							case "off_staander_blad2_links":
							case "off_staander_blad3_boven":
							case "off_staander_blad3_rechts":
							case "off_staander_blad3_onder":
							case "off_staander_blad3_links":
							case "off_staander_blad4_boven":
							case "off_staander_blad4_rechts":
							case "off_staander_blad4_onder":
							case "off_staander_blad4_links":
							case "off_staander_blad5_boven":
							case "off_staander_blad5_rechts":
							case "off_staander_blad5_onder":
							case "off_staander_blad5_links":
							case "off_staander_blad6_boven":
							case "off_staander_blad6_rechts":
							case "off_staander_blad6_onder":
							case "off_staander_blad6_links":
							case "off_staander_blad7_boven":
							case "off_staander_blad7_rechts":
							case "off_staander_blad7_onder":
							case "off_staander_blad7_links":
							case "off_staander_blad8_boven":
							case "off_staander_blad8_rechts":
							case "off_staander_blad8_onder":
							case "off_staander_blad8_links":
							case "off_staander_blad9_boven":
							case "off_staander_blad9_rechts":
							case "off_staander_blad9_onder":
							case "off_staander_blad9_links":
								break;

							case "off_achterwand_plinten":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="AP";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, 1, "Meeleveren achterwand of plinten", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									//NOTE: part of check is done also in field "off_rand_achter_blad1_boven"
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="html") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Plint/achterwand meeleveren:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:200px"><input type="radio" name="'.$fld.'" value="" '.($val==""?'checked="checked" ':NULL).'class="radio" />&nbsp;Geen&nbsp;plint&nbsp;of&nbsp;achterwand</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/stap6/geen-achterwand-plint.jpg" title="geen wandafwerking" style="width:230px;" /></td></tr>'.PHP_EOL;

									$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="P" '.($val=="P"?'checked="checked" ':NULL).'class="radio" />&nbsp;Plint</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/stap6/plinten.jpg" title="wandafwerking met plint" style="width:230px;" /></td></tr>'.PHP_EOL;

									$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="A" '.($val=="A"?'checked="checked" ':NULL).'class="radio" />&nbsp;Achterwand</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/stap6/achterwand.jpg" title="wandafwerking met achterwand" style="width:230px;" /></td></tr>'.PHP_EOL;

									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].=($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								if ($mode=="proposal" || $mode=="email") {
									switch ($val) {
										case "":
											$valtxt="Geen&nbsp;plint&nbsp;of&nbsp;achterwand";
											$valimg="wandafwerking_geen";
											$title="geen wandafwerking";
											break;
										case "P":
											$valtxt="Plint";
											$valimg="wandafwerking_plint";
											$title="wandafwerking met plint";
											break;
										case "A":
											$valtxt="Achterwand";
											$valimg="wandafwerking_achterwand";
											$title="wandafwerking met achterwand";
											break;
										default: //unknown/illegal value
											$valtxt="UNKNOWN";
											$valimg="";
											$title="";
									}
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Plint/achterwand meeleveren:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:200px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
									if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:125px;height:80px;" />';
									$ContentPg[$page].='</td></tr></table>'.PHP_EOL;
									$ContentPg[$page].='</td></tr>'.PHP_EOL;
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							case "off_rand_achter_blad1_boven":
								$pg=2;
								$fld_model="off_model";
								$val_model=$PgFldVal[$pg][$fld_model];
								$fld_achterwand="off_achterwand_plinten";
								$val_achterwand=$PgFldVal[$page][$fld_achterwand];
								$edgename=array(1=>'boven', 2=>'rechts', 3=>'onder', 4=>'links');
								$AnyMeasures=FALSE;
								for ($i=1; $i<10; $i++) {
									$fld_L="off_blad".$i."_lengte";
									$fld_B="off_blad".$i."_breedte";
									$val_L=$PgFldVal[$pg][$fld_L];
									$val_B=$PgFldVal[$pg][$fld_B];
									if ($val_model=="") {$val_L=""; $val_B="";} //not relevant
									if ($val_L>"" && $val_B>"") $AnyMeasures=TRUE;
									for ($j=1; $j<5; $j++) {
										$fld="off_rand_achter_blad".$i."_".$edgename[$j];
										$val=$PgFldVal[$page][$fld];
										if ($val=="") $val="0";
										if ($val=="on") $val="1";
										if ($val!="0" && $val!="1") $val="1";
										if ($mode=="init" || $mode=="ctrl") {
											$PgFldVal[$page][$fld]=$val; //NOTE: within mode==html/email, the non-visable field values are reset as well
											$msg[$fld]="";
										}
									}
								}
								$fld="off_rand_achter_blad1_boven";
								if ($mode=="init" && $AnyMeasures==FALSE) {
									$msg[$fld].='<div class="FrmMsgDiv"><font color="#FF0000">U dient eerst van minimaal 1 blad de maten in te voeren.<br>Ga terug naar pagina '.$pg.'.</font></div>'.PHP_EOL;
									$result=FALSE;
								}

								if ($mode=="ctrl" && $AnyMeasures==TRUE) { //NOTE: check added, required value for "off_achterwand_plinten" when any sides are set
									$AnyEdgesSet=FALSE;
									for ($i=1; $i<10; $i++) {
										for ($j=1; $j<5; $j++) {
											$fld="off_rand_achter_blad".$i."_".$edgename[$j];
											if ($PgFldVal[$page][$fld]==1) {
												$AnyEdgesSet=TRUE;
												break;
											}
										}
									}
									if ($AnyEdgesSet==TRUE && $val_achterwand=="") {
										$msg[$fld_achterwand].='<div class="FrmMsgDiv"><font color="#FF0000">Geef aan of de aangevinkte zijdes met een plint of achterwand moeten worden uitgevoerd.</font></div>'.PHP_EOL;
										$result=FALSE;
									}
									if ($AnyEdgesSet==FALSE && $val_achterwand!="") {
										//$val_achterwand=""; //n.v.t.
										//$PgFldVal[$page][$fld_achterwand]=$val_achterwand;
										$msg[$fld_achterwand].='<div class="FrmMsgDiv"><font color="#FF0000">Vink de zijdes aan die met een plint of achterwand moeten worden uitgevoerd.</font></div>'.PHP_EOL;
										$result=FALSE;
									}
								}

								if ($mode=="proposal" || $mode=="email") {
									for ($i=1; $i<10; $i++) {
										for ($j=1; $j<5; $j++) {
											$fld="off_rand_achter_blad".$i."_".$edgename[$j];
											$val=$PgFldVal[$page][$fld];
											$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
										}
									}
								}

								if ($AnyMeasures==FALSE && $mode=="html") {
									if ($msg[$fld]>"") $ContentPg[$page].=$msg[$fld].PHP_EOL;
								}
								if ($AnyMeasures==TRUE && $mode=="html") {
									$ContentPg[$page].='<div class="FrmEmptyDivCnt"></div>'.PHP_EOL;

									//build tab content
									$ContentPg[$page].='<div class="tabCntScroll_shape "tabCntActive"">';
									$inpW=13; $inpH=13; //width and height of input field
									$inpW=13; $inpH=12; //width and height of input field
									$inpXshift=-4; $inpYshift=-3; //excentricity of checkbox widget
									$inpXshift=-4; $inpYshift=-2; //excentricity of checkbox widget
									for ($i=1; $i<10; $i++) {
										$display="none";
										$DisplayEdges=$edgename; //ommit edges on plates fixed together, only when plate is displayed, only at edge on top on vertical plate may be absent
										switch ($i) {
											case 1:
												$plateX=40; $plateY=$inpH; //Left Top absolute position of a plate block
												$plateW=160; $plateH=60; $orientation="H"; //width, height, and orientation (=Horizontal/Vertical) of a plate
												if ($val_model!="E") {$plateX=10; $plateW=220; $plateH=40;}
												if ($val_model>"") $display="inline-block";
												break;
											case 2:
												$plateX=390; $plateY=$inpH;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="L" || $val_model=="U") {$plateX=10; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
												if ($val_model=="U" || $val_model=="L") $DisplayEdges[1]="";
												break;
											case 3:
												$plateX=420; $plateY=$inpH+$inpH+40+40;
												$plateW=160; $plateH=60; $orientation="H";
												if ($val_model=="L") {$plateX=390; $plateY=$inpH; $plateW=220; $plateH=40;}
												if ($val_model=="U") {$plateX=185; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="U" || $val_model=="E") $display="inline-block";
												if ($val_model=="U") $DisplayEdges[1]="";
												break;
											case 4:
												$plateX=10; $plateY=$inpH+300;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="L") {$plateX=565; $plateY=$inpH+$inpH+40+2; $plateW=45; $plateH=160; $orientation="V";}
												if ($val_model=="L" || $val_model=="E") $display="inline-block";
												if ($val_model=="L") $DisplayEdges[1]="";
												break;
											case 5:
												$plateX=10; $plateY=$inpH+300+$inpH+40+2;
												$plateW=45; $plateH=160; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												$DisplayEdges[1]="";
												break;
											case 6:
												$plateX=170; $plateY=$inpH+300+$inpH+40+2+160-120;
												$plateW=60; $plateH=120; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												break;
											case 7:
												$plateX=390; $plateY=$inpH+300;
												$plateW=220; $plateH=40; $orientation="H";
												if ($val_model=="E") $display="inline-block";
												break;
											case 8:
												$plateX=565; $plateY=$inpH+300+$inpH+40+2;
												$plateW=45; $plateH=160; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												$DisplayEdges[1]="";
												break;
											case 9:
												$plateX=390; $plateY=$inpH+300+$inpH+40+2+160-120;
												$plateW=60; $plateH=120; $orientation="V";
												if ($val_model=="E") $display="inline-block";
												break;
										}
										$PlateMeasures=FALSE;
										$fld_L="off_blad".$i."_lengte";
										$fld_B="off_blad".$i."_breedte";
										$val_L=$PgFldVal[$pg][$fld_L];
										$val_B=$PgFldVal[$pg][$fld_B];
										if ($val_model=="") {$val_L=""; $val_B="";} //not relevant
										if ($val_L>"" && $val_B>"") $PlateMeasures=TRUE;
										if ($display=="none" || $PlateMeasures!=TRUE) {
											for ($j=1; $j<5; $j++) {
												$fld="off_rand_achter_blad".$i."_".$edgename[$j];
												$val="0";
												$PgFldVal[$page][$fld]=$val;
												$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
											}
										}
										if ($display!="none") {
											$borderTest=NULL; //"border:1px solid red;"; //=TEST
											$ContentPg[$page].='<div style="position:absolute;display:'.$display.';top:'.$plateY.'px;left:'.($plateX+$inpW).'px;height:'.($inpH+2+$plateH).'px;width:'.($plateW+$inpW).'px;'.($PlateMeasures!=TRUE?'opacity:0.5;filter:Alpha(opacity=50);':NULL).'">';
											if ($orientation=="H") { //horizontal
												$ContentPg[$page].='<div class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:'.($inpH+2+1).'px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td style="text-align:center;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">'.($i==1?'BLAD ':NULL).$i.'</td></tr></table></div>'.PHP_EOL; //plate
												if ($PlateMeasures==TRUE) {
													$fld="off_rand_achter_blad".$i."_".$edgename[1]; //top
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.$inpYshift.'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_achter_blad".$i."_".$edgename[2]; //right
													$val=$PgFldVal[$page][$fld];
													$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.($plateW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;'.$borderTest.'"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													//$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.$tmp.'px;left:'.$plateW.'px;height:'.$inpH.'px;width:'.$inpW.'px;'.$borderTest.'"></div>'.PHP_EOL; //TEST shift of checkbox excentricity
													$fld="off_rand_achter_blad".$i."_".$edgename[3]; //bottom
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($plateH+$inpH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_achter_blad".$i."_".$edgename[4]; //left
													$val=$PgFldVal[$page][$fld];
													$tmp=$inpH+2+(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.(-$inpW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
												}
											}
											if ($orientation=="V") { //vertical
												$ContentPg[$page].='<div class="tabCntBlock_plate" style="position:absolute;display:inline-block;top:0px;left:0px;height:'.($plateH-2).'px;width:'.($plateW-2).'px;"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td style="text-align:center;vertical-align:middle;line-height:120%;color:#001050;font-size:12px;">'.$i.'</td></tr></table></div>'.PHP_EOL; //plate
												if ($PlateMeasures==TRUE) {
													$fld="off_rand_achter_blad".$i."_".$edgename[1]; //top
													if ($DisplayEdges[1]=="") {
														$val="0";
														$PgFldVal[$page][$fld]=$val;
														$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.$val.'" />'.PHP_EOL;
													} else {
														$val=$PgFldVal[$page][$fld];
														$tmp=(int)(0.5*($plateW-$inpW));
														$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.(-$inpH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													}
													$fld="off_rand_achter_blad".$i."_".$edgename[2]; //right
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.($plateW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_achter_blad".$i."_".$edgename[3]; //bottom
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateW-$inpW));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($plateH+$inpYshift).'px;left:'.($tmp+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
													$fld="off_rand_achter_blad".$i."_".$edgename[4]; //left
													$val=$PgFldVal[$page][$fld];
													$tmp=(int)(0.5*($plateH-($inpH+2)));
													$ContentPg[$page].='<div style="position:absolute;display:inline-block;top:'.($tmp+$inpYshift).'px;left:'.(-$inpW+$inpXshift).'px;height:'.$inpH.'px;width:'.$inpW.'px;"><input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).'class="checkbox" /></div>'.PHP_EOL;
												}
											}
											$ContentPg[$page].='</div>'.PHP_EOL;
										}
									}
									$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer

									//if ($mode=="email") $ContentPg[$page].='</td></tr>'.PHP_EOL;
								}

								if ($mode=="proposal" || $mode=="email") { //TODO build plate & measures & edges (visible & non-visible) in 1 table
								}
							case "off_rand_achter_blad1_rechts":
							case "off_rand_achter_blad1_onder":
							case "off_rand_achter_blad1_links":
							case "off_rand_achter_blad2_boven":
							case "off_rand_achter_blad2_rechts":
							case "off_rand_achter_blad2_onder":
							case "off_rand_achter_blad2_links":
							case "off_rand_achter_blad3_boven":
							case "off_rand_achter_blad3_rechts":
							case "off_rand_achter_blad3_onder":
							case "off_rand_achter_blad3_links":
							case "off_rand_achter_blad4_boven":
							case "off_rand_achter_blad4_rechts":
							case "off_rand_achter_blad4_onder":
							case "off_rand_achter_blad4_links":
							case "off_rand_achter_blad5_boven":
							case "off_rand_achter_blad5_rechts":
							case "off_rand_achter_blad5_onder":
							case "off_rand_achter_blad5_links":
							case "off_rand_achter_blad6_boven":
							case "off_rand_achter_blad6_rechts":
							case "off_rand_achter_blad6_onder":
							case "off_rand_achter_blad6_links":
							case "off_rand_achter_blad7_boven":
							case "off_rand_achter_blad7_rechts":
							case "off_rand_achter_blad7_onder":
							case "off_rand_achter_blad7_links":
							case "off_rand_achter_blad8_boven":
							case "off_rand_achter_blad8_rechts":
							case "off_rand_achter_blad8_onder":
							case "off_rand_achter_blad8_links":
							case "off_rand_achter_blad9_boven":
							case "off_rand_achter_blad9_rechts":
							case "off_rand_achter_blad9_onder":
							case "off_rand_achter_blad9_links":
								break;
							case "off_type":
								//
								$val=$PgFldVal[$page][$fld];
								$fld_materiaal="off_materiaal";
								$pg=1;
								$val_materiaal= $PgFldVal[$pg][$fld_materiaal];
								$msg[$fld]="";

								if($mode == "html" )
								{
									if($val_materiaal)
									{
										if(! $val)
										{
											$val = array_keys($materials[$val_materiaal])[0];
										}

										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Werkblad afwerking:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td>';

										$ContentPg[$page].= '<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
										foreach($materials[$val_materiaal] as $key => $value)
										{
											$checked = NULL;
											if($val == $key) $checked = "checked ";

											$ContentPg[$page].= '<tr><td style="text-align:left;vertical-align:middle;width:200px">';
											$ContentPg[$page].= '<input type="radio" name="'. $fld .'" value="' . $key . '" '. $checked .'class="radio" />';
											$ContentPg[$page].= '&nbsp;' . $value["title"] . '</td><td>';
											$ContentPg[$page].= '<img src="'.$GLOBALS["own"]["baseurl"] . $value["img"] . '"';
											$ContentPg[$page].= 'title="geen wandafwerking" style="width:230px;" /></td></tr>'.PHP_EOL;
										}
										$PgFldVal[$page][$fld] = $val;
									}
									else
									{
										$msg[$fld]='<div class="FrmMsgDiv"><font color="#FF0000">Kies eerst het materiaal en de kleur in stap 1.</font></div>';
									}

									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].=($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;

							case "off_dikte":
								$val=$PgFldVal[$page][$fld];
								$pg=1;
								$fld_materiaal="off_materiaal";

								$val_materiaal=$PgFldVal[$pg][$fld_materiaal];
								$pg=3;

								var_dump($PgFldVal[$pg]["off_type"]);
								$val_unit="cm";
								//$fld_opdikken="off_opdikken";
								//$val_opdikken=$PgFldVal[$pg][$fld_opdikken]; //WARNING: field value is not checked yet
								if ($mode=="init" || $mode=="ctrl")
								{
									$msg[$fld]="";
									$whitelist="0123456789";
									$minlen=1;
									$maxlen=2;
									$val_default="";
									switch ($val_materiaal)
									{
										case "BC":
											//case "BL":
											//case "T":
											$val_default="4";
											if ($val=="") $val=$val_default;
											if ($val!="4" && $val!="5" && $val!="6" && $val!="8" && $val!="10") $val="";
											break;
										case "Graniet":
											$minlen=2;
											$maxlen=2;
											$val_default="25";
											$val_unit="mm";
											if ($val=="") $val=$val_default;
											if ($val!="25" && $val!="32" && $val!="38") $val=""; //note: see special restrictions in case off_randafwerking
											break;
										case "L":
											$minlen=2;
											$maxlen=2;
											$val_default="25";
											$val_unit="mm";
											if ($val=="") $val=$val_default;
											if ($val!="25" && $val!="32" && $val!="38") $val=""; //note: see special restrictions in case off_randafwerking
											break;
										default: //unknown/illegal value
											$val="";
									}
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, $minlen, $maxlen, "De dikte", $msg[$fld]);
									if ($msg[$fld]>"")
									{
										$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;
									}
									/*if ($mode=="ctrl" && $val>"" && $val!=$val_default && $val_opdikken>"") { //rule: set default thickness on thickening plate
                                        if ($msg[$fld]=="") $msg[$fld]='<br />';
                                        $msg[$fld].='<font color="#FF0000">De standaard dikte is verplicht bij het opdikken van het werkblad.</font><br />';
                                        $val=$val_default;
                                        $result=FALSE;
                                    }*/
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal" || $mode=="email")
								{
									$valtxt="";
									$valimg="";
									$title="";
									if ($val!="") {
										$val_default="";
										$val_unit="";
										switch ($val_materiaal) {
											case "BC":
												//case "BL":
												//case "T":
												$val_default="4";
												$val_unit="cm";
												$valimg=($val==$val_default?"randafwerking_recht":"opdikken_rand");
												break;
											case "L":
												$val_default="25";
												$val_unit="mm";
												$valimg=($val==$val_default?"randafwerking_recht":"opdikken_rand");
												break;
											case "Graniet":
												$val_default="25";
												$val_unit="mm";
												$valimg=($val==$val_default?"randafwerking_recht":"opdikken_rand");
												break;
										}
										$valtxt=$val." ".$val_unit;
										if ($valimg>"") {
											$title=($val==$val_default?"standaard werkblad":"opgedikte rand werkblad");
										}
									}
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Dikte werkblad:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
									if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:80px;height:40px;" />';
									$ContentPg[$page].='</td></tr></table>'.PHP_EOL;
									$ContentPg[$page].='</td></tr>'.PHP_EOL;
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}

								if ($mode=="html") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel" style="vertical-align:middle;">Dikte werkblad:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									switch ($val_materiaal) {
										case "BC":
											//case "BL":
											//case "T":
											$val_unit="cm";
											$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
											$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="4" '.($val=="4"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;4&nbsp;'.$val_unit.'&nbsp;(standaard)&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/randafwerking_recht.jpg" title="standaard werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											$ContentPg[$page].='</td></tr>'.PHP_EOL;

											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Opgedikt naar<div class="HelpIcon" title="De bladen worden niet massief gemaakt. De randen worden alleen opgedikt"></div></td>'.PHP_EOL;
											$ContentPg[$page].='	<td>';
											$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
											$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="5" '.($val=="5"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;5&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="6" '.($val=="6"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;6&nbsp;'.$val_unit.'<br />';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="8" '.($val=="8"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;8&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="10" '.($val=="10"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;10&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/opdikken_rand.jpg" title="opgedikte rand werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											break;
										case "L":
											$val_unit="mm";
											$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
											$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="25" '.($val=="25"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;25&nbsp;'.$val_unit.'&nbsp;(standaard)&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/randafwerking_recht.jpg" title="standaard werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											$ContentPg[$page].='</td></tr>'.PHP_EOL;

											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Opgedikt naar</td>'.PHP_EOL;
											$ContentPg[$page].='	<td>';
											$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
											$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="32" '.($val=="32"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;32&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="38" '.($val=="38"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;38&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/opdikken_rand.jpg" title="opgedikte rand werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											break;
										case "Graniet":
											$val_unit="mm";
											$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
											$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="25" '.($val=="25"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;25&nbsp;'.$val_unit.'&nbsp;(standaard)&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/randafwerking_recht.jpg" title="standaard werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											$ContentPg[$page].='</td></tr>'.PHP_EOL;

											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Opgedikt naar</td>'.PHP_EOL;
											$ContentPg[$page].='	<td>';
											$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
											$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="32" '.($val=="32"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;32&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='<input type="radio" name="'.$fld.'" value="38" '.($val=="38"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;38&nbsp;'.$val_unit.'&nbsp;&nbsp;&nbsp;';
											$ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/opdikken_rand.jpg" title="opgedikte rand werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											break;
										default: //unknown/illegal value
											$msg[$fld]='<font color="#FF0000">De dikte is onbepaald, kies eerst het materiaal en de kleur in stap 1.</font>';
									}
									$ContentPg[$page].=($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL;
									if ($mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							/*case "off_randafwerking":
                                $val=$PgFldVal[$page][$fld];
                                $pg=1;
                                $fld_materiaal="off_materiaal";
                                $val_materiaal=$PgFldVal[$pg][$fld_materiaal];
                                $pg=6;
                                $fld_dikte="off_dikte";
                                $val_dikte=$PgFldVal[$pg][$fld_dikte];
                                $fld_opdikken="off_opdikken";
                                $val_opdikken=$PgFldVal[$pg][$fld_opdikken]; //WARNING: field value is not checked yet
                                if ($mode=="init" || $mode=="ctrl") {
                                    $msg[$fld]="";
                                    $whitelist="RAW";
                                    $val_default="R";
                                    if ($val=="") $val=$val_default;
                                    switch ($val_materiaal) {
                                    case "BC":
                                    //case "BL":
                                        if ($val!="R") $val="";
                                        break;
                                    //case "T":
                                    //	if ($val!="R" && $val!="W") $val="";
                                    //	break;
                                    case "L":
                                        if ($val!="R" && $val!="A" && $val!="W") $val="";
                                        break;
                                    default: //unknown/illegal value
                                        $val_default="";
                                        $val=$val_default;;
                                    }
                                    if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 1, "De randafwerking", $msg[$fld]);
                                    if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
                                    if ($mode=="ctrl" && $val_dikte!="32" && $val=="W") { //rule: breakwater brim only possible when thickness plate is 32
                                        if ($msg[$fld]=="") $msg[$fld]='<br />';
                                        $msg[$fld].='<font color="#FF0000">Een waterkering is alleen mogelijk bij een dikte van het werkblad van 32 mm.</font><br />';
                                        $val=$val_default;
                                        $result=FALSE;
                                    }
                                    if ($mode=="ctrl" && !($val_dikte=="32" || $val_dikte=="38") && $val=="A") { //rule: rounding brim only possible when thickness plate is 32 or 38
                                        if ($msg[$fld]=="") $msg[$fld]='<br />';
                                        $msg[$fld].='<font color="#FF0000">Een afgeronde rand is alleen mogelijk bij een dikte van het werkblad van 32 of 38 mm.</font><br />';
                                        $val=$val_default;
                                        $result=FALSE;
                                    }
                                    if ($mode=="ctrl" && $val>"" && $val!=$val_default && $val_opdikken>"") { //rule: set default edge on thickening plate
                                        if ($msg[$fld]=="") $msg[$fld]='<br />';
                                        $msg[$fld].='<font color="#FF0000">De standaard randafwerking is verplicht bij het opdikken van het werkblad.</font><br />';
                                        $val=$val_default;
                                        $result=FALSE;
                                    }
                                    $PgFldVal[$page][$fld]=$val;
                                }
                                if ($mode=="proposal" || $mode=="email") {
                                    switch ($val) {
                                    case "R":
                                        $valtxt="Recht&nbsp;(standaard)";
                                        $valimg="randafwerking_recht";
                                        $title="rechte rand";
                                        break;
                                    case "A":
                                        $valtxt="Afgerond";
                                        $valimg="randafwerking_afgerond";
                                        $title="afgeronde rand";
                                        break;
                                    case "W":
                                        $valtxt="Waterkering";
                                        $valimg="randafwerking_waterkering";
                                        $title="rand met waterkering";
                                        break;
                                    default: //unknown/illegal value
                                        $valtxt="UNKNOWN";
                                        $valimg="";
                                        $title="";
                                    }
                                    $ContentPg[$page].='<tr><td class="FrmTblTdLabel">Randafwerking:</td>'.PHP_EOL;
                                    $ContentPg[$page].='	<td>';
                                    $ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
                                    $ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
                                    if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:80px;height:40px;" />';
                                    $ContentPg[$page].='</td></tr></table>'.PHP_EOL;
                                    $ContentPg[$page].='</td></tr>'.PHP_EOL;
                                    $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
                                }
                                if ($mode=="html") {
                                    $ContentPg[$page].='<tr><td class="FrmTblTdLabel">Randafwerking:</td>'.PHP_EOL;
                                    $ContentPg[$page].='	<td>';
                                    $ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
                                    $ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px"><input type="radio" name="'.$fld.'" value="R" '.($val=="R"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;Recht&nbsp;(standaard)</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/randafwerking_recht.jpg" title="rechte rand" style="width:80px;height:40px;" /></td></tr>'.PHP_EOL;
                                    if ($val_materiaal=="L") {
                                        $ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
                                        $ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="A" '.($val=="A"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;Afgerond</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/randafwerking_afgerond.jpg" title="afgeronde rand" style="width:80px;height:40px;" /></td></tr>'.PHP_EOL;
                                    }
                                    //if ($val_materiaal=="T" || $val_materiaal=="L") {
                                    if( $val_materiaal=="L") {
                                        $ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
                                        $ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="W" '.($val=="W"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;Waterkering</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/randafwerking_waterkering.jpg" title="rand met waterkering" style="width:80px;height:40px;" /></td></tr>'.PHP_EOL;
                                    }
                                    $ContentPg[$page].='</table>'.PHP_EOL;
                                    $ContentPg[$page].=($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
                                }
                                break;
                            */

							/*case "off_opdikken":
                                $val=$PgFldVal[$page][$fld];
                                $pg=1;
                                $fld_materiaal="off_materiaal";
                                $val_materiaal=$PgFldVal[$pg][$fld_materiaal];
                                if ($mode=="init" || $mode=="ctrl") {
                                    $msg[$fld]="";
                                    $whitelist="0123456789";
                                    $maxlen=3;
                                    switch ($val_materiaal) {
                                    case "BC":
                                    //case "BL":
                                    //case "T":
                                        if ($val!="60" && $val!="70" && $val!="80" && $val!="90" && $val!="100") $val="";
                                        break;
                                    case "L":
                                        if ($val!="50" && $val!="64" && $val!="75" && $val!="96") $val="";
                                        $maxlen=2;
                                        break;
                                    default: //unknown/illegal value
                                        $val="";
                                    }
                                    if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, $maxlen, "Het opdikken van het werkblad", $msg[$fld]);
                                    if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
                                    $PgFldVal[$page][$fld]=$val;
                                }
                                if ($mode=="proposal" || $mode=="email") {
                                    $valtxt="n.v.t.";
                                    $valimg="";
                                    $title="";
                                    if ($val!="") {
                                        $valtxt=$val." mm";
                                        $valimg="opdikken_rand";
                                        $title="opgedikte rand werkblad";
                                    }
                                    $ContentPg[$page].='<tr><td class="FrmTblTdLabel">Opdikken werkblad:</td>'.PHP_EOL;
                                    $ContentPg[$page].='	<td>';
                                    $ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
                                    $ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
                                    if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:80px;height:40px;" />';
                                    $ContentPg[$page].='</td></tr></table>'.PHP_EOL;
                                    $ContentPg[$page].='</td></tr>'.PHP_EOL;
                                    $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
                                }
                                if ($mode=="html") {
                                    $ContentPg[$page].='<tr><td class="FrmTblTdLabel">Opdikken werkblad:</td>'.PHP_EOL;
                                    $ContentPg[$page].='	<td>';
                                    $ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
                                    $ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:300px">';
                                    $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="" '.($val==""?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;n.v.t.&nbsp;&nbsp;&nbsp;';
                                    switch ($val_materiaal) {
                                    case "BC":
                                    //case "BL":
                                    //case "T":
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="60" '.($val=="60"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;60&nbsp;mm&nbsp;&nbsp;&nbsp;';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="70" '.($val=="70"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;70&nbsp;mm&nbsp;&nbsp;&nbsp;<br />';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="80" '.($val=="80"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;80&nbsp;mm&nbsp;&nbsp;&nbsp;';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="90" '.($val=="90"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;90&nbsp;mm&nbsp;&nbsp;&nbsp;';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="100" '.($val=="100"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;100&nbsp;mm';
                                        break;
                                    case "L":
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="50" '.($val=="50"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;50&nbsp;mm&nbsp;&nbsp;&nbsp;';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="64" '.($val=="64"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;64&nbsp;mm&nbsp;&nbsp;&nbsp;<br />';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="75" '.($val=="75"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;75&nbsp;mm&nbsp;&nbsp;&nbsp;';
                                        $ContentPg[$page].='<input type="radio" name="'.$fld.'" value="96" '.($val=="96"?'checked="checked" ':NULL).'onclick="check_thickening();" class="radio" />&nbsp;96&nbsp;mm';
                                        break;
                                    default: //unknown/illegal value
                                    }
                                    $ContentPg[$page].='</td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/opdikken_rand.jpg" title="opgedikte rand werkblad" style="width:100px;height:50px;" /></td></tr>'.PHP_EOL;
                                    $ContentPg[$page].='</table>'.PHP_EOL;
                                    $ContentPg[$page].=($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
                                }
                                break;
                            */

							case "off_kookplaatuitsparing":
								$val=$PgFldVal[$page][$fld];
								$pg=1;
								$fld_materiaal="off_materiaal";
								$val_materiaal=$PgFldVal[$pg][$fld_materiaal];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="VP";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, 1, "De kookplaat uitsparing", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									if ($mode=="ctrl" && $val_materiaal=="L" && $val=="V") { //rule: on Laminate only build-up on top possible
										if ($msg[$fld]=="") $msg[$fld]='<br />';
										$msg[$fld].='<font color="#FF0000">Bij een werkblad van laminaat is alleen opbouw van een kookplaat mogelijk.</font><br />';
										$val="P";
										$result=FALSE;
									}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal" || $mode=="email") {
									switch ($val) {
										case "":
											$valtxt="n.v.t.";
											$valimg="";
											$title="";
											break;
										case "P":
											$valtxt="Opbouw";
											$valimg="kookplaat_opbouw";
											$title="opbouw";
											break;
										case "V":
											$valtxt="Vlakinbouw";
											$valimg="kookplaat_vlakinbouw";
											$title="vlakinbouw";
											break;
										default: //unknown/illegal value
											$valtxt="UNKNOWN";
											$valimg="";
											$title="";
									}
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kookplaat uitsparing:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
									if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:80px;height:40px;" />';
									$ContentPg[$page].='</td></tr></table>'.PHP_EOL;
									$ContentPg[$page].='</td></tr>'.PHP_EOL;
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kookplaat uitsparing:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>';
									$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px"><input type="radio" name="'.$fld.'" value="" '.($val==""?'checked="checked" ':NULL).'class="radio" />&nbsp;n.v.t.</td><td></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="P" '.($val=="P"?'checked="checked" ':NULL).'class="radio" />&nbsp;Opbouw<div class="HelpIcon" title="Klein randje van kookplaat ligt op het blad."></div></td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/kookplaat_opbouw.jpg" title="opbouw" style="width:80px;height:40px;" /></td></tr>'.PHP_EOL;

									if ($val_materiaal!="L") {
										$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
										$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="V" '.($val=="V"?'checked="checked" ':NULL).'class="radio" />&nbsp;Vlakinbouw<div class="HelpIcon" title="Kookplaat ligt geintegreerd/verzonken in het blad."></div></td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/kookplaat_vlakinbouw.jpg" title="vlakinbouw" style="width:80px;height:40px;" /></td></tr>'.PHP_EOL;
									}
									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].=($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL;
									if ($mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							case "off_spoelbakuitsparing":
							case "off_spoelbaklevering":
							case "off_spoelbaktype":
								$fld_spoelbakuitsparing="off_spoelbakuitsparing";
								$val_spoelbakuitsparing=$PgFldVal[$page][$fld_spoelbakuitsparing];
								$fld_spoelbaklevering="off_spoelbaklevering";
								$val_spoelbaklevering=$PgFldVal[$page][$fld_spoelbaklevering];
								$fld_spoelbaktype="off_spoelbaktype";
								$val_spoelbaktype=$PgFldVal[$page][$fld_spoelbaktype];
								$pg=1;
								$fld_materiaal="off_materiaal";
								$val_materiaal=$PgFldVal[$pg][$fld_materiaal];
								if ($fld==$fld_spoelbakuitsparing) {
									$val=$val_spoelbakuitsparing;
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										$whitelist="VPN";
										if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, 1, "De spoelbak uitsparing", $msg[$fld]);
										if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
										if ($mode=="ctrl" && $val_materiaal=="L" && $val=="V" && $val_spoelbaktype=="") { //rule: on Laminate and build-in at equal hight is possible together with a choosen washing basin
											if ($msg[$fld]=="") $msg[$fld]='<br />';
											$msg[$fld].='<font color="#FF0000">Bij een werkblad van laminaat is vlakinbouw alleen mogelijk indien samen met een spoelbak.</font><br />';
											$result=FALSE;
										}
										if ($mode=="ctrl" && $val_materiaal=="L" && $val=="N") { //rule: on Laminate then build-underneath is not possible
											if ($msg[$fld]=="") $msg[$fld]='<br />';
											$msg[$fld].='<font color="#FF0000">Bij een werkblad van laminaat is onderbouw van een spoelbak niet mogelijk.</font><br />';
											$val="P";
											$result=FALSE;
										}
										$val_spoelbakuitsparing=$val;
										$PgFldVal[$page][$fld]=$val;
									}
									if ($mode=="proposal" || $mode=="email") {
										switch ($val) {
											case "":
												$valtxt="n.v.t.";
												$valimg="";
												$title="";
												break;
											case "N":
												$valtxt="Onderbouw";
												$valimg="kookplaat_onderbouw";
												$title="onderbouw";
												break;
											case "V":
												$valtxt="Vlakinbouw";
												$valimg="kookplaat_vlakinbouw";
												$title="vlakinbouw";
												break;
											case "P":
												$valtxt="Opbouw";
												$valimg="kookplaat_opbouw";
												$title="opbouw";
												break;
											default: //unknown/illegal value
												$valtxt="UNKNOWN";
												$valimg="";
												$title="";
										}
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Spoelbak uitsparing:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td>';
										$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
										$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px">'.htmlspecialchars($valtxt).'</td><td>'.PHP_EOL;
										if ($valimg!="") $ContentPg[$page].='	<img src="'.$GLOBALS["own"]["baseurl"].'images/'.$valimg.'.jpg" title="'.$title.'" style="width:80px;height:40px;" />';
										$ContentPg[$page].='</td></tr></table>'.PHP_EOL;
										$ContentPg[$page].='</td></tr>'.PHP_EOL;
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
									}
									if ($mode=="html") {
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Spoelbak uitsparing:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td>';
										$ContentPg[$page].='<table border="0" cellpadding="0" cellspacing="0">'.PHP_EOL;
										$ContentPg[$page].='<tr><td style="text-align:left;vertical-align:middle;width:180px"><input type="radio" name="'.$fld.'" value="" '.($val==""?'checked="checked" ':NULL).'onclick="Sink_hole(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" '.'class="radio" />&nbsp;n.v.t.</td><td></td></tr>'.PHP_EOL;
										$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
										if ($val_materiaal!="L") {
											$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
											$ContentPg[$page].='<tr class="light-up-hover"><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="N" '.($val=="N"?'checked="checked" ':NULL).'onclick="Sink_hole(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Onderbouw<div class="HelpIcon" title="De spoelbak wordt aan de onderzijde van het werkblad gemonteerd"></div></td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/stap6/onderbouw-spoelbak.jpg" title="onderbouw" style="width:160px;border: 1px solid rgba(0, 0, 0, 0.0);" /></td></tr>'.PHP_EOL;
										}
										$ContentPg[$page].='<tr class="light-up-hover"><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="V" '.($val=="V"?'checked="checked" ':NULL).'onclick="Sink_hole(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Vlakinbouw<div class="HelpIcon" title="poelbak wordt in het blad geïntegreerd /verzonken"></div></td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/stap6/vlakbouw-spoelbak.jpg" title="vlakinbouw" style="width:160px;border: 1px solid rgba(0, 0, 0, 0.0);" /></td></tr>'.PHP_EOL;
										$ContentPg[$page].='<tr height="10px"><td colspan="2"></td></tr>'.PHP_EOL;
										$ContentPg[$page].='<tr class="light-up-hover"><td style="text-align:left;vertical-align:middle;"><input type="radio" name="'.$fld.'" value="P" '.($val=="P"?'checked="checked" ':NULL).'onclick="Sink_hole(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Opbouw<div class="HelpIcon" title="De spoelbak ligt op het blad"></div></td><td><img src="'.$GLOBALS["own"]["baseurl"].'images/stap6/opbouw-spoelbak.jpg" title="opbouw" style="width:160px;border: 1px solid rgba(0, 0, 0, 0.0);" /></td></tr>'.PHP_EOL;
										$ContentPg[$page].='</table>'.PHP_EOL;
										$ContentPg[$page].=($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL;
										if ($mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
									}
								}

								//checkbox, onclick-action is functioning better than a changevalue of a dropdownlist
								if ($fld==$fld_spoelbaklevering) {
									$val=$val_spoelbaklevering;
									/* checkbox handling example
                                    if ($val=="") $val="0";
                                    if ($val=="on") $val="1";
                                    if ($val!="0" && $val!="1") $val="1";
                                    $ContentPg[$page].='<input type="checkbox" name="'.$fld.'" '.($val=="1"?'checked="checked" ':NULL).($mode=="html"?'onclick="Checkbox_washbasin(if (this.checked==true) document.getElementById(\''.$fld.'_txt\').innerHTML=\'&nbsp;Ja\'; else document.getElementById(\''.$fld.'_txt\').innerHTML=\'&nbsp;Nee\';)" ':NULL).'class="checkbox" />';
                                    $ContentPg[$page].='<span id="'.$fld.'_txt">&nbsp;'.($val_spoelbaklevering=="1"?'Ja':'Nee').'</span>';*/
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										$whitelist="JN";
										if ($val=="") $val="N";
										if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 1, "Spoelbak meeleveren", $msg[$fld]);
										if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
										if ($val=="J" && $val_spoelbakuitsparing=="") $val="N"; //reset, no use for washbasin without washbasin placeholder
										if ($val=="N" && $val_spoelbaktype>"") $val_spoelbaktype=""; //reset washbasin
										if ($val=="J" && $val_spoelbaktype=="") $val="N";
										$val_spoelbaklevering=$val;
										$PgFldVal[$page][$fld]=$val;
										$PgFldVal[$page][$fld_spoelbaktype]=$val_spoelbaktype;
									}
									if ($mode=="proposal" || $mode=="email") {
										$valtxt="n.v.t.";
										if ($val_spoelbakuitsparing!="") {
											if ($val=="J") $valtxt="Ja"; else $valtxt="Nee";
										}
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Spoelbak meeleveren:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td>'.htmlspecialchars($valtxt);
										$ContentPg[$page].='</td></tr>'.PHP_EOL;
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
									}
									if ($mode=="html") {
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Spoelbak meeleveren:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td><span id="'.$fld.'_nvt" style="display:'.($val_spoelbakuitsparing==""?'inline':'none').'">&nbsp;n.v.t.</span>';
										$ContentPg[$page].='<span id="'.$fld.'_inp" style="display:'.($val_spoelbakuitsparing==""?'none':'inline').'">';
										$ContentPg[$page].='<input type="radio" id="'.$fld.'_J" name="'.$fld.'" value="J" '.($val=="J"?'checked="checked" ':NULL).'onclick="Sink_supply(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Ja&nbsp;&nbsp;&nbsp;';
										$ContentPg[$page].='<input type="radio" id="'.$fld.'_N" name="'.$fld.'" value="N" '.($val=="N"?'checked="checked" ':NULL).'onclick="Sink_supply(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Nee</span>';
										$ContentPg[$page].='<span id="'.$fld.'_msg" style="display:'.($msg[$fld]==""?'none':'inline').'">'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></span>';
										$ContentPg[$page].='</td></tr>'.PHP_EOL;
									}
								}

								if ($fld==$fld_spoelbaktype) {
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										if ($mode=="ctrl" && $val_spoelbaklevering=="J") {
											$valid=FALSE;
											if ($val_spoelbaktype>"") {
												foreach($arrTabItems['cnt'] as $typeId=>$dum) {
													if ($val_materiaal==$typeId || substr($typeId, 0, 4)=="RVS_") {
														foreach($arrTabItems['cnt'][$typeId] as $cntId=>$cntDescPrice) {
															if ($val_spoelbaktype==$cntId) {$valid=TRUE; break;}
														}
														if ($valid==TRUE) break;
													}
												}
											}
											if ($valid==FALSE) {
												$msg[$fld]='<br /><font color="#FF0000">Selecteer een spoelbak uit een van de artikelen hieronder.</font><br />';
												$val_spoelbaktype=""; //reset
												$result=FALSE;
											}
										}
										$PgFldVal[$page][$fld]=$val_spoelbaktype;
									}
									if ($mode=="html" || $mode=="proposal" || $mode=="email")
									{
										$valtxt="";
										if ($val_spoelbaktype>"") {
											foreach($arrTabItems['cnt'] as $typeId=>$dum) {
												foreach($arrTabItems['cnt'][$typeId] as $cntId=>$cntDescPrice) {
													if ($val_spoelbaktype==$cntId) {$valtxt=$cntDescPrice['desc']; break;}
												}
												if ($valtxt>"") break;
											}
										} else {
											if ($mode=="proposal" || $mode=="email") $valtxt="n.v.t.";
										}
										if ($mode=="proposal" || $mode=="email") {
											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Spoelbak type:</td>'.PHP_EOL;
											$ContentPg[$page].='	<td valign="top">'.htmlspecialchars($valtxt).'<br />'.PHP_EOL;
											if ($val_spoelbaktype>"") {
												$ContentPg[$page].='	<img src="'.$arrTabItems['dir'][$typeId].$val_spoelbaktype.'.jpg" class="SelectCntBlock"/><br />'.PHP_EOL;
											}
											$ContentPg[$page].='</td></tr>'.PHP_EOL;
										}
										if ($mode=="html") {
											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Spoelbak type:</td>'.PHP_EOL;
											$ContentPg[$page].='	<td valign="top"><span id="'.$fld.'_nvt" style="display:'.($val_spoelbaktype==""?'inline':'none').'">&nbsp;n.v.t.</span>';
											$ContentPg[$page].='<span id="'.$fld.'_txt" style="display:'.($val_spoelbaktype==""?'none':'inline').'">'.htmlspecialchars($valtxt).'</span>';
											$ContentPg[$page].='<span id="'.$fld.'_wyz" style="display:'.($val_spoelbaktype==""?'none':'inline').';color:red;margin-left: 5px;"><a href="#" onclick="return Sink_wyz(\''.$arrTabItems['css_id']['tabCntContainer'].'\')"><i>wijzig</i></a></span>';
											$ContentPg[$page].='<span id="'.$fld.'_img" style="display:'.($val_spoelbaktype==""?'none':'inline').'"><br /><img src="'.($val_spoelbaktype==""?NULL:$arrTabItems['dir'][$typeId].$val_spoelbaktype).'.jpg" class="SelectCntBlock"/></span>';
											$ContentPg[$page].='<span id="'.$fld.'_msg" style="display:'.($msg[$fld]==""?'none':'inline').'">'.($msg[$fld]>""?$msg[$fld]:NULL).'</span>';
											$ContentPg[$page].='</td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											$ContentPg[$page].='</div>'.PHP_EOL;
										}
									}
									if ($mode=="html")
									{ //field having own div block of washbasin items
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val_spoelbaktype).'" />'.PHP_EOL;
										//$ContentPg[$page].='<tr><td colspan="2">'.PHP_EOL;

										//build content
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'" class="tabCntContainer" style="display:'.($val_spoelbaklevering=="J" && $val_spoelbaktype==""?'block':'none').'">'.PHP_EOL;
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_all" class="tabCntContainerElem tabCntActive" name="all">'.PHP_EOL;
										$ContentPg[$page].='<div class="tabCntScroll" style="border:1px dotted #666666">'.PHP_EOL;
										foreach($arrTabItems['btn'] as $btnId=>$btnTxt) {
											if ($val_materiaal==$btnId || substr($btnId, 0, 4)=="RVS_") {
												$ContentPg[$page].='<div style="text-align:left;"><h3>'.$btnTxt.'</h3></div>'.PHP_EOL;
												foreach($arrTabItems['cnt'][$btnId] as $cntId=>$cntDescPrice) {
													$ContentPg[$page].='<div class="tabCntBlock" style="height:210px;">'.PHP_EOL; //new height=210
													$ContentPg[$page].='<a href="#" onclick="return tabCntBlockClick(this, \'all\', \''.$cntId.'\', \''.$arrTabItems['css_id']['tabCntContainer'].'\', \'Spoelbak\');">';
													$ContentPg[$page].='<div id="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'" class="'.($cntId==$val_spoelbaktype?'tabCntBlockActive':'tabCntBlockInactive').'" style="height:204px;">'.PHP_EOL; //204=210-2*3 (border-width)
													$ContentPg[$page].='<img id="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'_img" src="'.$arrTabItems['dir'][$btnId].$cntId.'.jpg" title="'.$cntDescPrice['desc'].'" />';

													$ContentPg[$page].='<span id="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'_txt">'.$cntDescPrice['desc'].'</span>';
													//if (!isset($cntDescPrice['nonDiscountPrice'])) {
													$ContentPg[$page].='<span class="tabCntBlockPrice" style="top:185px;">&euro;'.$cntDescPrice['price'].',-</span>'.PHP_EOL; //185=210-25
													//} else {
													//$ContentPg[$page].='<span class="tabCntBlockPrice" style="top:167px;">&euro;<strike>'.$cntDescPrice['nonDiscountPrice'].',-</strike><br />nu <span style="color:#f21000;">&euro;'.$cntDescPrice['price'].',-</span></span>'.PHP_EOL; //185=210-25-18
													//}
													$ContentPg[$page].='</div>'.PHP_EOL;
													$ContentPg[$page].='</a>'.PHP_EOL;
													$ContentPg[$page].='<input type="submit" onclick="return false;" name="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'" value="" class="tabCntBlockFocusFld" />';//add field to give focus to
													$ContentPg[$page].='</div>'.PHP_EOL;  //end tabCntBlock
												}
												$ContentPg[$page].='<div class="FrmEmptyDiv" style="clear:both;"></div>'.PHP_EOL; //empty row
											}
										}
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntScroll
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer_
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer
										$ContentPg[$page].='<div id="emptyrow_sink_block" class="FrmEmptyDiv" style="height:60px;display:'.($val_spoelbaklevering=="J" && $val_spoelbaktype==""?'block':'none').'"></div>'.PHP_EOL; //empty row

										//$ContentPg[$page].='	</td></tr>'.PHP_EOL;
									}
								}
								break;

							case "off_aantalgaten":
							case "off_kraanlevering":
							case "off_kraantype":
								$fld_aantalgaten="off_aantalgaten";
								$val_aantalgaten=$PgFldVal[$page][$fld_aantalgaten];
								$fld_kraanlevering="off_kraanlevering";
								$val_kraanlevering=$PgFldVal[$page][$fld_kraanlevering];
								$fld_kraantype="off_kraantype";
								$val_kraantype=$PgFldVal[$page][$fld_kraantype];
								if ($fld==$fld_aantalgaten) {
									$val=$val_aantalgaten;
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										$whitelist="012345";
										if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, 1, "Het aantal gaten", $msg[$fld]);
										if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
										$val_aantalgaten=$val;
										$PgFldVal[$page][$fld]=$val;
									}
									if ($mode=="html" || $mode=="proposal" || $mode=="email") {
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Aantal gaten voor kraan, quooker of zeepdispenser:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td><select name="'.($mode=="html"?NULL:'tmp_').$fld.'" '.($mode=="html"?NULL:'disabled="disabled" ').($mode=="html"?'onchange="Watertap_hole(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" ':NULL).'class="boxlook_content" style="width:50px">'.PHP_EOL;
										$ContentPg[$page].='				<option value="0" '.($val=="" || $val=="0"?'selected="selected"':NULL).'>0</option>'.PHP_EOL;
										for ($i=1; $i<6; $i++) {
											$ContentPg[$page].='				<option value="'.$i.'" '.($val==$i?'selected="selected"':NULL).'>'.$i.'</option>'.PHP_EOL;
										}
										$ContentPg[$page].='			</select>'.($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL;
										if ($mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
									}
								}
								if ($fld==$fld_kraanlevering) {
									$val=$val_kraanlevering;
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										$whitelist="JN";
										if ($val=="") $val="N";
										if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 1, "Kraan meeleveren", $msg[$fld]);
										if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
										if ($val=="J" && $val_aantalgaten<"1") $val="N"; //reset, no use for watertap without holes in plate
										if ($val=="N" && $val_aantalgaten>"0") $val_kraantype=""; //reset watertap
										if ($val=="J" && $val_kraantype=="") $val="N";
										$val_kraanlevering=$val;
										$PgFldVal[$page][$fld]=$val;
										$PgFldVal[$page][$fld_kraantype]=$val_kraantype;
									}
									if ($mode=="proposal" || $mode=="email") {
										$valtxt="n.v.t.";
										if ($val_aantalgaten>"0") {
											if ($val=="J") $valtxt="Ja"; else $valtxt="Nee";
										}
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kraan meeleveren:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td>'.htmlspecialchars($valtxt);
										$ContentPg[$page].='</td></tr>'.PHP_EOL;
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
									}
									if ($mode=="html") {
										$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kraan meeleveren:</td>'.PHP_EOL;
										$ContentPg[$page].='	<td><span id="'.$fld.'_nvt" style="display:'.($val_aantalgaten<"1"?'inline':'none').'">&nbsp;n.v.t.</span>';
										$ContentPg[$page].='<span id="'.$fld.'_inp" style="display:'.($val_aantalgaten<"1"?'none':'inline').'">';
										$ContentPg[$page].='<input type="radio" id="'.$fld.'_J" name="'.$fld.'" value="J" '.($val=="J"?'checked="checked" ':NULL).'onclick="Watertap_supply(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Ja&nbsp;&nbsp;&nbsp;';
										$ContentPg[$page].='<input type="radio" id="'.$fld.'_N" name="'.$fld.'" value="N" '.($val=="N"?'checked="checked" ':NULL).'onclick="Watertap_supply(this, \''.$arrTabItems['css_id']['tabCntContainer'].'\');" class="radio" />&nbsp;Nee</span>';
										$ContentPg[$page].='<span id="'.$fld.'_msg" style="display:'.($msg[$fld]==""?'none':'inline').'">'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></span>';
										$ContentPg[$page].='</td></tr>'.PHP_EOL;
									}
								}

								if ($fld==$fld_kraantype) {
									if ($mode=="init" || $mode=="ctrl") {
										$msg[$fld]="";
										if ($mode=="ctrl" && $val_kraanlevering=="J") {
											$valid=FALSE;
											if ($val_kraantype>"") {
												foreach($arrTabItems['cnt'] as $typeId=>$dum) {
													//if ($val_materiaal==$typeId || substr($typeId, 0, 4)=="RVS_") {
													if (substr($typeId, 0, 4)=="RVS_") {
														foreach($arrTabItems['cnt'][$typeId] as $cntId=>$cntDescPrice) {
															if ($val_kraantype==$cntId) {$valid=TRUE; break;}
														}
														if ($valid==TRUE) break;
													}
												}
											}
											if ($valid==FALSE) {
												$msg[$fld]='<br /><font color="#FF0000">Selecteer een kraan uit een van de artikelen hieronder.</font><br />';
												$val_kraantype=""; //reset
												$result=FALSE;
											}
										}
										$PgFldVal[$page][$fld]=$val_kraantype;
									}
									if ($mode=="html" || $mode=="proposal" || $mode=="email") {
										$valtxt="";
										if ($val_kraantype>"") {
											foreach($arrTabItems['cnt'] as $typeId=>$dum) {
												foreach($arrTabItems['cnt'][$typeId] as $cntId=>$cntDescPrice) {
													if ($val_kraantype==$cntId) {$valtxt=$cntDescPrice['desc']; break;}
												}
												if ($valtxt>"") break;
											}
										} else {
											if ($mode=="proposal" || $mode=="email") $valtxt="n.v.t.";
										}
										if ($mode=="proposal" || $mode=="email") {
											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kraan type:</td>'.PHP_EOL;
											$ContentPg[$page].='	<td valign="top">'.htmlspecialchars($valtxt).'<br />'.PHP_EOL;
											if ($val_kraantype>"") {
												$ContentPg[$page].='	<img src="'.$arrTabItems['dir'][$typeId].$val_kraantype.'.jpg" class="SelectCntBlock"/><br />'.PHP_EOL;
											}
											$ContentPg[$page].='</td></tr>'.PHP_EOL;
										}
										if ($mode=="html") {
											$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Kraan type:</td>'.PHP_EOL;
											$ContentPg[$page].='	<td valign="top"><span id="'.$fld.'_nvt" style="display:'.($val_kraantype==""?'inline':'none').'">&nbsp;n.v.t.</span>';
											$ContentPg[$page].='<span id="'.$fld.'_txt" style="display:'.($val_kraantype==""?'none':'inline').'">'.htmlspecialchars($valtxt).'</span>';
											$ContentPg[$page].='<span id="'.$fld.'_wyz" style="display:'.($val_kraantype==""?'none':'inline').';float:right;"><a href="#" onclick="return Watertap_wyz(\''.$arrTabItems['css_id']['tabCntContainer'].'\')"><i>wijzig</i></a></span>';
											$ContentPg[$page].='<span id="'.$fld.'_img" style="display:'.($val_kraantype==""?'none':'inline').'"><br /><img src="'.($val_kraantype==""?NULL:$arrTabItems['dir'][$typeId].$val_kraantype).'.jpg" class="SelectCntBlock"/></span>';
											$ContentPg[$page].='<span id="'.$fld.'_msg" style="display:'.($msg[$fld]==""?'none':'inline').'">'.($msg[$fld]>""?$msg[$fld]:NULL).'</span>';
											$ContentPg[$page].='</td></tr>'.PHP_EOL;
											$ContentPg[$page].='</table>'.PHP_EOL;
											$ContentPg[$page].='</div>'.PHP_EOL;
										}
									}
									if ($mode=="html") { //field having own div block of watertap items
										$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val_kraantype).'" />'.PHP_EOL;
										//$ContentPg[$page].='<tr><td colspan="2">'.PHP_EOL;

										//build content
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'" class="tabCntContainer watertap" style="display:'.($val_kraanlevering=="J" && $val_kraantype==""?'block':'none').'">'.PHP_EOL;
										$ContentPg[$page].='<div id="'.$arrTabItems['css_id']['tabCntContainer'].'_all" class="tabCntContainerElem tabCntActive" name="all">'.PHP_EOL;
										$ContentPg[$page].='<div class="tabCntScroll watertap" style="border:1px dotted #666666">'.PHP_EOL;
										foreach($arrTabItems['btn'] as $btnId=>$btnTxt) {
											//if ($val_materiaal==$btnId || substr($btnId, 0, 4)=="RVS_") {
											if (substr($btnId, 0, 4)=="RVS_") {
												$ContentPg[$page].='<div style="text-align:left;"><h3>'.$btnTxt.'</h3></div>'.PHP_EOL;
												foreach($arrTabItems['cnt'][$btnId] as $cntId=>$cntDescPrice) {
													$ContentPg[$page].='<div class="tabCntBlock" style="height:210px;">'.PHP_EOL; //new height=210
													$ContentPg[$page].='<a href="#" onclick="return tabCntBlockClick(this, \'all\', \''.$cntId.'\', \''.$arrTabItems['css_id']['tabCntContainer'].'\', \'Kraan\');">';
													$ContentPg[$page].='<div id="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'" class="'.($cntId==$val_kraantype?'tabCntBlockActive':'tabCntBlockInactive').'" style="height:204px;">'.PHP_EOL; //204=210-2*3 (border-width)
													$ContentPg[$page].='<img id="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'_img" src="'.$arrTabItems['dir'][$btnId].$cntId.'.jpg" title="'.$cntDescPrice['desc'].'" />';

													$ContentPg[$page].='<span id="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'_txt">'.$cntDescPrice['desc'].'</span>';
													//if (!isset($cntDescPrice['nonDiscountPrice'])) {
													$ContentPg[$page].='<span class="tabCntBlockPrice" style="top:185px;">&euro;'.$cntDescPrice['price'].',-</span>'.PHP_EOL; //185=210-25
													//} else {
													//$ContentPg[$page].='<span class="tabCntBlockPrice" style="top:167px;">&euro;<strike>'.$cntDescPrice['nonDiscountPrice'].',-</strike><br />nu <span style="color:#f21000;">&euro;'.$cntDescPrice['price'].',-</span></span>'.PHP_EOL; //185=210-25-18
													//}
													$ContentPg[$page].='</div>'.PHP_EOL;
													$ContentPg[$page].='</a>'.PHP_EOL;
													$ContentPg[$page].='<input type="submit" onclick="return false;" name="tabCntBlock_'.$arrTabItems['css_id']['tabCntContainer'].'_all_'.$cntId.'" value="" class="tabCntBlockFocusFld" />';//add field to give focus to
													$ContentPg[$page].='</div>'.PHP_EOL;  //end tabCntBlock
												}
												$ContentPg[$page].='<div class="FrmEmptyDiv" style="clear:both;"></div>'.PHP_EOL; //empty row
											}
										}
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntScroll
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer_
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer
										$ContentPg[$page].='<div id="emptyrow_watertap_block" class="FrmEmptyDiv" style="height:60px;display:'.($val_kraanlevering=="J" && $val_kraantype==""?'block':'none').'"></div>'.PHP_EOL; //empty row

										//$ContentPg[$page].='	</td></tr>'.PHP_EOL;
									}
								}
								break;

							case "off_TerrazzoCoating":
								$val=$PgFldVal[$page][$fld];
								$pg=1;
								$fld_materiaal="off_materiaal";
								$val_materiaal=$PgFldVal[$pg][$fld_materiaal];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="JN";
									$maxlen=1;
									switch ($val_materiaal) {
										case "BC":
											//case "BL":
											$val="";
											break;
										//case "T":
										//	if ($val=="") $val="N";
										//	if ($val!="J" && $val!="N") $val="N";
										//	break;
										case "L":
											$val="";
											break;
										default: //unknown/illegal value
											$val="";
									}
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, $maxlen, "Het coaten van het werkblad", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="html" || $mode=="proposal" || $mode=="email") {
									//if ($val_materiaal=="T") {
									//	$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Werkblad coaten:</td>'.PHP_EOL;
									//	$ContentPg[$page].='	<td><select name="'.($mode=="html"?NULL:'tmp_').$fld.'" '.($mode=="html"?NULL:'disabled="disabled" ').'class="boxlook_content" style="width:50px">'.PHP_EOL;
									//	$ContentPg[$page].='			<option value="J" '.($val=="J"?'selected="selected"':NULL).'>Ja</option>'.PHP_EOL;
									//	$ContentPg[$page].='			<option value="N" '.($val=="N"?'selected="selected"':NULL).'>Nee</option>'.PHP_EOL;
									//	$ContentPg[$page].='		</select>'.($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL;
									//}
									if (/*$val_materiaal!="T" ||*/ $mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							case "off_inmeten":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="ZP";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 1, "Het inmeten", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="html" || $mode=="proposal" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Inmeten:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="radio" name="'.($mode=="html"?NULL:'tmp_').$fld.'" value="Z" '.($val=="Z"?'checked="checked" ':NULL).($mode=="html"?NULL:'disabled="disabled" ').'class="radio" />&nbsp;Zelf&nbsp;&nbsp;&nbsp;';
									$ContentPg[$page].='<input type="radio" name="'.($mode=="html"?NULL:'tmp_').$fld.'" value="P" '.($val=="P"?'checked="checked" ':NULL).($mode=="html"?NULL:'disabled="disabled" ').'class="radio" />&nbsp;Professional'.($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL; //Professional (kosten &euro;150,-)
									if ($mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							case "off_afmonteren":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="JN";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 1, "Het afmonteren", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="html" || $mode=="proposal" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Afmonteren:<div class="HelpIcon" title="Standaard worden de bladen op de keukenkasten gelegd. Als u kiest voor volledig afmonteren, dan monteren wij de spoelbak, kraan, kookplaat en kitten alles netjes af."></div></td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="radio" name="'.($mode=="html"?NULL:'tmp_').$fld.'" value="N" '.($val=="N"?'checked="checked" ':NULL).($mode=="html"?NULL:'disabled="disabled" ').'class="radio" />&nbsp;Nee&nbsp;&nbsp;&nbsp;';
									$ContentPg[$page].='<input type="radio" name="'.($mode=="html"?NULL:'tmp_').$fld.'" value="J" '.($val=="J"?'checked="checked" ':NULL).($mode=="html"?NULL:'disabled="disabled" ').'class="radio" />&nbsp;Ja'.($msg[$fld]>""?$msg[$fld].'<br />':NULL).'</td></tr>'.PHP_EOL;
									if ($mode!="html") $ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								break;

							case "off_opm":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?@#$%^&*-_+=()[]{}<>/\'.:;, ".'"'.PHP_EOL;
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, 500, "Uw aanvullende wensen", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Aanvullende wensen:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><textarea name="'.$fld.'" rows="5" class="boxlook_content" style="width:450px;">'.htmlspecialchars($val).'</textarea>'.($msg[$fld]>""?$msg[$fld]:NULL).'</td></tr>'.PHP_EOL;
								}
								break;

							case "off_aanhef":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									if ($val!="" && $val!="M" && $val!="V") $val="";
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Uw naam:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td valign="top">'.PHP_EOL;
									$ContentPg[$page].='		<table cellpadding="0" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='			<tr><td valign="top" width="80px"><select name="'.$fld.'" class="boxlook_content">'.PHP_EOL;
									$ContentPg[$page].='				<option value="" '.($val==""?'selected="selected"':NULL).'></option>'.PHP_EOL;
									$ContentPg[$page].='				<option value="M" '.($val=="M"?'selected="selected"':NULL).'>Heer</option>'.PHP_EOL;
									$ContentPg[$page].='				<option value="V" '.($val=="V"?'selected="selected"':NULL).'>Mevrouw</option>'.PHP_EOL;
									$ContentPg[$page].='			</select></td>'.PHP_EOL;
								}
								break;

							case "off_naam":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789&-_+()\/'., ";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 80, "Uw naam", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='			<td valign="top"><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:361px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
									$ContentPg[$page].='		</table>'.PHP_EOL;
									$ContentPg[$page].='</td></tr>'.PHP_EOL;
								}
								break;

							case "off_email":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@-_.,";
									if ($mode=="ctrl") {
										fnc_ChkFldInput($val, $whitelist, 6, 60, "Uw e-mail", $msg[$fld]);
										if (strlen($val)<6 || strpos($val, '@', 0)===FALSE || strpos($val, '.', 0)===FALSE) {
											$msg[$fld].='<font color="#FF0000">'."n.b. Een e-mail adres moet minimaal voldoen aan het format x@x.nl.<br />".'</font>';
										}
									}
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Uw e-mail:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:450px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;

							case "off_adres":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789&-+()\/'., ";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 200, "Uw adres", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Uw adres:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:450px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;

							case "off_postcode":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-+\/., ";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 10, "De postcode", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">De postcode:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:75px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;

							case "off_woonplaats":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789&-+()\/'., ";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 1, 100, "Uw woonplaats", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Uw woonplaats:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:450px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;
							case "off_land":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
									if ($val>"" && $mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 2, 2, "De landcode", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=strtoupper($val);
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">De landcode:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:40px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;

							case "off_telefoon":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-+=()/.:;, ";
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 10, 35, "Uw telefoon", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Uw telefoon:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="text" name="'.$fld.'" value="'.htmlspecialchars($val).'" class="boxlook_content" style="width:450px" />'.($msg[$fld]>""?$msg[$fld]:NULL).'<br /></td></tr>'.PHP_EOL;
								}
								break;

							case "off_bericht":
								$val=$PgFldVal[$page][$fld];
								if ($mode=="init" || $mode=="ctrl") {
									$msg[$fld]="";
									$whitelist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?@#$%^&*-_+=()[]{}<>/\'.:;, ".'"'.PHP_EOL;
									if ($mode=="ctrl") fnc_ChkFldInput($val, $whitelist, 0, 500, "Het bericht", $msg[$fld]);
									if ($msg[$fld]>"") {$msg[$fld]='<br />'.$msg[$fld]; $result=FALSE;}
									$PgFldVal[$page][$fld]=$val;
								}
								if ($mode=="proposal") {
									$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
								}
								if ($mode=="html" || $mode=="email") {
									$ContentPg[$page].='<tr><td class="FrmTblTdLabel">Uw bericht:</td>'.PHP_EOL;
									$ContentPg[$page].='	<td><textarea name="'.$fld.'" rows="5" class="boxlook_content" style="width:450px;">'.htmlspecialchars($val).'</textarea>'.($msg[$fld]>""?$msg[$fld]:NULL).'</td></tr>'.PHP_EOL;
								}
								break;
							default: //unknown field
								die("Veld '".$fld."' niet gedefinieerd");
						}
					} //fnc_FldDef

					//get all POSTed form input of all pages
					$PgFldVal=array();
					foreach($PgFldList as $pg=>$FldList) {
						$PgFldVal[$pg]=array();
						if ($FldList>"") {
							$flds=explode(",", $FldList);
							foreach ($flds as $fld) {
								$val="";
								if (isset($_POST[$fld])) $val=$_POST[$fld];
								$PgFldVal[$pg][$fld]=$val;
							}
						}
					}

					//execute requested page
					$page=1;
					$pag_skip=0; //-1/1, on empty page then directly go to the pervious/next page
					if (isset($_POST["page"])) $page=(int)$_POST["page"];

					//if (isset($_POST["NavPrevPg"])) {$page--; $pag_skip=-1;}
					//if (isset($_POST["NavNextPg"])) {$page++; $pag_skip=1;}
					foreach($PgFldList as $pg=>$FldList) {
						if (isset($_POST["NavPage_".$pg])) {
							$page=$pg;
							break;
						}
					}

					if (isset($_POST["GoNew"])) unset($_POST["GoNew"]);
					if (isset($_POST["GoBack"]) && isset($_POST["GoForward"])) unset($_POST["GoForward"]);
					if (isset($_POST["GoBack"])) {
						$page--;
						$pag_skip=-1;
						unset($_POST["GoBack"]);
					}
					if (isset($_POST["GoForward"])) $pag_skip=1;
					if ($page<1) $page=1;

					$CalculateAll=0; //1=proposal, go through all pages and check all input
					$CheckAll=0; //1=security, go through all pages and check all input again
					$ContentPg=array();
					do {
						$DoRepeat=FALSE;
						switch ($page) {
							case 1: //page 1, invoer materiaal en kleur.
							case 2: //page 2, invoer model en alle maten etc..
							case 3: //page 3, type werkblad.
							case 4: //page 4, invoer zichtbare randen van model.
							case 5: //page 5, invoer staanders van model.
							case 6: //page 6, invoer achterwanden/plinten van model.
							case 7: //page 7, invoer overige.
							case 9: //page 8, invoer contactgegevens
								//initialize and check field input
								if (isset($_POST["GoForward"]) || $CalculateAll==1 || $CheckAll==1) $mode="ctrl"; else $mode="init";

								$msg=array();
								$result=TRUE;
								if ($PgFldList[$page]>"") {
									$flds=explode(",", $PgFldList[$page]);
									foreach ($flds as $fld) {
										$arrTabItems=NULL;
										if ($page==1) $arrTabItems=$arrTabItems_MatKlr;
										if ($page==2 || $page==4 || $page==5 || $page==6) $arrTabItems=$arrTabItems_Shape;
										if ($page==7 && ($fld=="off_spoelbakuitsparing" || $fld=="off_spoelbaklevering" || $fld=="off_spoelbaktype")) $arrTabItems=$arrTabItems_Spoelbak;
										if ($page==7 && ($fld=="off_aantalgaten" || $fld=="off_kraanlevering" || $fld=="off_kraantype")) $arrTabItems=$arrTabItems_Kraan;
										fnc_FldDef($mode, $arrTabItems, $PgFldVal, $page, $fld, $result, $msg, $ContentPg);

										//keep email beginning, used by sending
										if ($fld=="off_aanhef") {
											$val=$PgFldVal[$page][$fld];
											$email_beginning="Geachte";
											if ($val=="M") $email_beginning.=" heer";
											if ($val=="V") $email_beginning.=" mevrouw";
										}
										if ($fld=="off_naam") {
											$val=$PgFldVal[$page][$fld];
											$email_beginning.=" ".$val;
										}
										//keep email to-address, used by sending
										if ($fld=="off_email") {
											$val=$PgFldVal[$page][$fld];
											$email_to=$val;
										}
									}
								}

								//when input is OK then go to next page else display this page, or build-up its content for the email
								if ($result==TRUE && isset($_POST["GoForward"])) {
									unset($_POST["GoForward"]);
									$page++;
									$DoRepeat=TRUE;
									break;
								}
								if ($result==TRUE && $CalculateAll==1) $mode="proposal";
								elseif ($result==TRUE && $CheckAll==1) $mode="email";
								else $mode="html";

								//output html/email form
								$ContentPg[$page]="";

								//start form
								if ($mode=="html") {
									$ContentPg[$page].='<form name="input_'.$page.'" action="'.$action.'" method="post">'.PHP_EOL;
									$ContentPg[$page].='<input type="hidden" name="page" value="'.$page.'" />'.PHP_EOL;
									foreach($PgFldList as $pg=>$FldList) {
										if ($FldList>"") {
											$flds=explode(",", $FldList);
											foreach ($flds as $fld) {
												if ($pg!=$page) {
													$val=$PgFldVal[$pg][$fld];
													$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
												}
											}
										}
									}
								}

								//form title & step-navigation
								if ($page==1 || $mode=="html") {
									$ContentPg[$page].='<div class="FrmHeader">'.PHP_EOL;
									$ContentPg[$page].='<table cellpadding="0" cellspacing="0" border="0" width="100%">'.PHP_EOL;
									$ContentPg[$page].='<tr><td class="FrmHeaderTitle_h1">Offerte aanvragen</td>'.PHP_EOL;
									if ($mode=="html") {
										//if ($page<7) $NavText="&nbsp;&nbsp;Stap&nbsp;".$page."&nbsp;van&nbsp;7&nbsp;&nbsp;"; else $NavText="&nbsp;&nbsp;Stap&nbsp;6&nbsp;van&nbsp;6"; //TODO, skip page 6
										//$ContentPg[$page].='	<td>&nbsp;</td><td class="FrmHeaderNav">'.($page>1?'<input type="submit" name="NavPrevPg" value="&lt;" class="boxlook_content" />':NULL).$NavText.($page<8?'<input type="submit" name="NavNextPg" value="&gt;" class="boxlook_content" />':NULL).'</td></tr>'.PHP_EOL;
										$ContentPg[$page].='	<td>&nbsp;</td><td class="FrmHeaderNav"><ul class="FrmHeaderNav_ul">Stap:&nbsp;'; //padding:0px;margin-top:4px;
										$i=0;
										foreach($PgFldList as $pg=>$FldList) {
											if ($FldList>"") {
												$i++;
												$ContentPg[$page].='<li class="FrmHeaderNav_li"><input type="submit" name="NavPage_'.$pg.'" value="'.$i.'" class="'.($pg==$page?'FrmHeaderNav_active':'FrmHeaderNav_inactive').'"'.($pg==$page?' onClick="return false;"':NULL).' title="'.$PgNavTxtList[$pg].'" /></li>';
											}
										}
										$ContentPg[$page].='</ul></td></tr>'.PHP_EOL;
									} else {
										$ContentPg[$page].='	<td colspan="2"></td></tr>'.PHP_EOL;
									}
									$ContentPg[$page].='<tr height="23px"><td colspan="3"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].='</div>'.PHP_EOL;
								}

								//step sub-title
								$ContentPg[$page].='<div class="FrmHeader">'.PHP_EOL;
								$ContentPg[$page].='<table cellpadding="0" cellspacing="0" border="0" width="100%">'.PHP_EOL;
								$ContentPg[$page].='<tr><td class="FrmHeaderTitle_h2">'.$PgTitleTxtList[$page].'</td><td colspan="2"></td></tr>'.PHP_EOL;
								$ContentPg[$page].='<tr>'.PHP_EOL;
								if ($mode=="html") {
									$ContentPg[$page].='	<td class="FrmHeaderBtns">'.($page>1?'<input type="submit" id="GoBack" name="GoBack" value="&lt;&lt; Terug" class="boxlook_content_button" />&nbsp;':NULL).'<input type="submit" id="GoForward" name="GoForward" value="'.($page<count($PgFldList)?'Verder &gt;&gt;':'Verzenden &bull;&bull;&bull;').'" class="boxlook_content_button" /></td></tr>'.PHP_EOL;
								} else {
									$ContentPg[$page].='	<td colspan="2"></td></tr>'.PHP_EOL;
								}
								$ContentPg[$page].='</table>'.PHP_EOL;
								$ContentPg[$page].='</div>'.PHP_EOL;

								//form fields
								if (($page==1 && $mode!="html") || $page>6) {
									if ($page==7 && $mode=="html") $ContentPg[$page].='<div style="position:relative;display:block;">'.PHP_EOL;
									$ContentPg[$page].='<table cellpadding="4" cellspacing="0">'.PHP_EOL;
								}
								foreach($PgFldList as $pg=>$FldList) {
									if ($FldList>"") {
										$flds=explode(",", $FldList);
										foreach ($flds as $fld) {
											$arrTabItems=NULL;
											if ($pg==1) $arrTabItems=$arrTabItems_MatKlr;
											if ($pg==2 || $pg==3 || $pg==4 || $pg==5 || $pg==6) $arrTabItems=$arrTabItems_Shape;
											if ($pg==7 && ($fld=="off_spoelbakuitsparing" || $fld=="off_spoelbaklevering" || $fld=="off_spoelbaktype")) $arrTabItems=$arrTabItems_Spoelbak;
											if ($pg==7 && ($fld=="off_aantalgaten" || $fld=="off_kraanlevering" || $fld=="off_kraantype")) $arrTabItems=$arrTabItems_Kraan;
											if ($pg==$page) {
												if (($pg==5 && $mode=="html" && $fld=="off_staander") ||
													($pg==6 && $mode=="html" && $fld=="off_achterwand_plinten")) { //field having own div block
													$ContentPg[$page].='<div style="position:relative;display:block;">'.PHP_EOL;
													$ContentPg[$page].='<table cellpadding="4" cellspacing="0">'.PHP_EOL;
												}
												if ($pg==7 && $mode=="html" && ($fld=="off_aantalgaten" || $fld=="off_inmeten")) { //field followed just after field off_spoelbaktype or off_kraantype (having own div block)
													$ContentPg[$page].='<div style="position:relative;display:block;">'.PHP_EOL;
													$ContentPg[$page].='<table cellpadding="4" cellspacing="0">'.PHP_EOL;
													$ContentPg[$page].='<tr>'.PHP_EOL;
													$ContentPg[$page].='   <td class="FrmTblTdLabel">&nbsp;</td><td valign="top" class="FrmTblTdCntWyz">&nbsp;</td>'.PHP_EOL;
													$ContentPg[$page].='</tr>'.PHP_EOL;
												}
												fnc_FldDef($mode, $arrTabItems, $PgFldVal, $page, $fld, $result, $msg, $ContentPg);
												if (($pg==5 && $mode=="html" && $fld=="off_staander") ||
													($pg==6 && $mode=="html" && $fld=="off_achterwand_plinten")) {
													$ContentPg[$page].='</table></div>'.PHP_EOL;
												}
											}
										}
									}
								}

								//submit buttons
								if (($page==1 && $mode!="html") || $page>1) $ContentPg[$page].='</table>'.PHP_EOL;
								if ($page==7 && $mode=="html") $ContentPg[$page].='</div>'.PHP_EOL;
								if ($mode=="html") {
									//if ($page==1) {
									//	why does button not work, is not all form el.
									//	$ContentPg[$page].='<input id="GoForwardContent" type="submit" name="GoForward" value="Verder &gt;&gt;" class="boxlook_content_button" style="position:absolute;top:660px" />'.PHP_EOL;
									//}
									if ($page>1) {
										$ContentPg[$page].='<div style="height:15px;">&nbsp;</div>'.PHP_EOL;
										$ContentPg[$page].=($page>1?'<input id="GoBackContent" type="submit" name="GoBack" value="&lt;&lt; Terug" class="boxlook_content_button" />&nbsp;':NULL).'<input id="GoForwardContent" type="submit" name="GoForward" value="'.($page<count($PgFldList)?'Verder &gt;&gt;':'Verzenden &bull;&bull;&bull;').'" class="boxlook_content_button" />'.PHP_EOL;
									}
									$ContentPg[$page].='</form>'.PHP_EOL;
								}

								//build special tab pages, for material & color
								if ($page==1 && $mode=="html") {
									$fld_materiaal="off_materiaal";
									$val_materiaal=$PgFldVal[$page][$fld_materiaal];
									if ($val_materiaal=="") { //open the first tab only
										$array_keys=array_keys($arrTabItems_MatKlr['btn']);
										$val_materiaal=$array_keys[0];
									}
									$fld_kleur="off_kleur";
									$val_kleur=$PgFldVal[$page][$fld_kleur];

									//build tab buttons
									$ContentPg[$page].='<div id="'.$arrTabItems_MatKlr['css_id']['tabBtnContainer'].'" class="tabBtnContainer">'.PHP_EOL;
									$ContentPg[$page].='<span class="tabBtnEmpty"></span>'.PHP_EOL;
									foreach($arrTabItems_MatKlr['btn'] as $btnId=>$btnTxt) {
										//if ($btnId=="BL") $btnTxt='HS Beton &nbsp;Lite'; //align or wrap the text 'Lite' under the text 'Beton'
										$ContentPg[$page].='<a href="#" id="'.$arrTabItems_MatKlr['css_id']['tabBtnContainer'].'_'.$btnId.'" onclick="return tabBtnClick(this, \''.$arrTabItems_MatKlr['css_id']['tabBtnContainer'].'\', \''.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'\');" class="'.($btnId==$val_materiaal?'tabBtnActive':'tabBtnInactive').'" name="'.$btnId.'">';
										$ContentPg[$page].='<div class="tabBtnText"><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr><td class="tabBtnText">'.$btnTxt.'</td></tr></table></div>';
										$ContentPg[$page].='</a>'.PHP_EOL;
									}
									$ContentPg[$page].='</div>'.PHP_EOL; //end tabBtnContainer

									//build tab content
									$ContentPg[$page].='<div id="'.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'" class="tabCntContainer">'.PHP_EOL;
									foreach($arrTabItems_MatKlr['btn'] as $btnId=>$btnTxt) {
										$ContentPg[$page].='<div id="'.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'_'.$btnId.'" class="tabCntContainerElem '.($btnId==$val_materiaal?'tabCntActive':'tabCntInactive').'" name="'.$btnId.'">'.PHP_EOL;
										$ContentPg[$page].='<div class="tabCntScroll">'.PHP_EOL;
										if (!is_array($arrTabItems_MatKlr['cnt'][$btnId])) {
											$ContentPg[$page].='<span><br /><h3>'.$btnTxt.' is in vele kleuren leverbaar.</h3></span>'.PHP_EOL;
											$ContentPg[$page].='<div class="tabCntNone">'.PHP_EOL;
											$ContentPg[$page].='<input type="submit" onclick="return tabCntBlockClick(this, \''.$btnId.'\', \'\', \''.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'\', \'MatKlr\');" value="Selecteer" class="boxlook_content_button" />'.PHP_EOL;
											$ContentPg[$page].='<img src="'.$GLOBALS["own"]["baseurl"].'images/ok.png" id="tabCntNone_'.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'_'.$btnId.'" class="'.($btnId==$val_materiaal?'tabCntNoneActive':'tabCntNoneInactive').'" title="OK" />'.PHP_EOL;
											$ContentPg[$page].='</div>'.PHP_EOL;  //end tabCntNone
										} else {
											if ($btnId=="L") { //extra text for laminaat
												$ContentPg[$page].='<span><h3>'.$btnTxt.' is in vele kleuren leverbaar.</h3></span>'.PHP_EOL;
											}
											foreach($arrTabItems_MatKlr['cnt'][$btnId] as $cntId=>$cntTxt) {
												$ContentPg[$page].='<div class="tabCntBlock">'.PHP_EOL;
												$ContentPg[$page].='<a href="#" onclick="return tabCntBlockClick(this, \''.$btnId.'\', \''.$cntId.'\', \''.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'\', \'MatKlr\');">';
												$ContentPg[$page].='<div id="tabCntBlock_'.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'_'.$btnId.'_'.$cntId.'" class="'.($btnId==$val_materiaal && $cntId==$val_kleur?'tabCntBlockActive':'tabCntBlockInactive').'">'.PHP_EOL;
												$ContentPg[$page].='<img src="'.$arrTabItems_MatKlr['dir'][$btnId].$cntId.'.jpg" title="'.$cntTxt.'" />';
												$ContentPg[$page].='<span>'.$cntTxt.'</span>'.PHP_EOL;
												$ContentPg[$page].='</div>'.PHP_EOL;
												$ContentPg[$page].='</a>'.PHP_EOL;
												$ContentPg[$page].='<input type="submit" onclick="return false;" name="tabCntBlock_'.$arrTabItems_MatKlr['css_id']['tabCntContainer'].'_'.$btnId.'_'.$cntId.'" value="" class="tabCntBlockFocusFld" />';//add field to give focus to
												$ContentPg[$page].='</div>'.PHP_EOL;  //end tabCntBlock
											}
										}
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntScroll
										$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer_
									}
									$ContentPg[$page].='</div>'.PHP_EOL; //end tabCntContainer

									//why doesnt pointer and click not work
									//$ContentPg[$page].='<div style="height:15px;">&nbsp;</div>'.PHP_EOL;
									//$ContentPg[$page].='<input type="button" onClick="alert(1);GoForwardClick();" name="GoForwardButton" value="Verder &gt;&gt;" class="boxlook_content_button" />'.PHP_EOL;
									//$ContentPg[$page].='<a href="#" onclick="alert(2);return GoForwardClick();" class="off_boxlook_content_button"><span>Verder &gt;&gt;</span></a>'.PHP_EOL;
								}

								if ($mode=="html") {
									echo $ContentPg[$page];
								} else {
									$page++;
									$DoRepeat=TRUE;
								}
								break;

							case 8: //page 8, berekening offerte
								if (1==0 && $CheckAll!=1) { //add a field 'total value proposal' to open this page view
									//check all former pages, on error it will hold there and focus is set to the page and field
									$CalculateAll++;
									if ($CalculateAll==1) {
										$page=1;
										$DoRepeat=TRUE;
										break;
									}
									$DoRepeat=FALSE;

									//TODO calculate and display the proposal page

									//output html form
									$ContentPg[$page]="";

									//start form
									$ContentPg[$page].='<form name="input_'.$page.'" action="'.$action.'" method="post">'.PHP_EOL;
									$ContentPg[$page].='<input type="hidden" name="page" value="'.$page.'" />'.PHP_EOL;
									foreach($PgFldList as $pg=>$FldList) {
										if ($FldList>"") {
											if ($pg>$page) {
												$flds=explode(",", $FldList);
												foreach ($flds as $fld) {
													if ($pg!=$page) {
														$val=$PgFldVal[$pg][$fld];
														$ContentPg[$page].='<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
													}
												}
											}
										}
									}

									//form title & step-navigation
									$ContentPg[$page].='<div class="FrmHeader">'.PHP_EOL;
									$ContentPg[$page].='<table cellpadding="0" cellspacing="0" border="0" width="100%">'.PHP_EOL;
									$ContentPg[$page].='<tr><td class="FrmHeaderTitle_h1">Offerte aanvragen</td>'.PHP_EOL;
									//if ($page<7) $NavText="&nbsp;&nbsp;Stap&nbsp;".$page."&nbsp;van&nbsp;6&nbsp;&nbsp;"; else $NavText="&nbsp;&nbsp;Stap&nbsp;6&nbsp;van&nbsp;6"; //TODO, skip page 6
									//$ContentPg[$page].='	<td>&nbsp;</td><td class="FrmHeaderNav">'.($page>1?'<input type="submit" name="NavPrevPg" value="&lt;" class="boxlook_content" />':NULL).$NavText.($page<8?'<input type="submit" name="NavNextPg" value="&gt;" class="boxlook_content" />':NULL).'</td></tr>'.PHP_EOL;
									$ContentPg[$page].='	<td>&nbsp;</td><td class="FrmHeaderNav"><ul class="FrmHeaderNav_ul">Stap:&nbsp;'; //padding:0px;margin-top:4px;
									$i=0;
									foreach($PgFldList as $pg=>$FldList) {
										if ($FldList>"") {
											$i++;
											$ContentPg[$page].='<li class="FrmHeaderNav_li"><input type="submit" name="NavPage_'.$pg.'" value="'.$i.'" class="'.($pg==$page?'FrmHeaderNav_active':'FrmHeaderNav_inactive').'"'.($pg==$page?' onClick="return false;"':NULL).' title="'.$PgNavTxtList[$pg].'" /></li>';
										}
									}
									$ContentPg[$page].='</ul></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr height="23px"><td colspan="3"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].='</div>'.PHP_EOL;

									//step sub-title
									$ContentPg[$page].='<div class="FrmHeader">'.PHP_EOL;
									$ContentPg[$page].='<table cellpadding="0" cellspacing="0" border="0" width="100%">'.PHP_EOL;
									$ContentPg[$page].='<tr><td class="FrmHeaderTitle_h2">'.$PgTitleTxtList[$page].'</td>'.PHP_EOL;
									$ContentPg[$page].='	<td>&nbsp;</td><td class="FrmHeaderBtns">'.($page>1?'<input type="submit" name="GoBack" value="&lt;&lt; Terug" class="boxlook_content_button" />&nbsp;':NULL).'<input type="submit" name="GoForward" value="'.($page<count($PgFldList)?'Verder &gt;&gt;':'Verzenden &bull;&bull;&bull;').'" class="boxlook_content_button" /></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr height="8px"><td colspan="3"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].='</div>'.PHP_EOL;

									//form fields
									foreach($PgFldList as $pg=>$FldList) {
										if ($FldList>"") {
											if ($pg>1 && $pg!=3 && $pg!=4 && $pg!=5) $ContentPg[$page].='<br /><br /><br /><hr><br /><br />';
											if ($pg<$page) $ContentPg[$page].=$ContentPg[$pg];
										}
									}

									//submit buttons
									$ContentPg[$page].='<table cellpadding="4" cellspacing="0">'.PHP_EOL;
									$ContentPg[$page].='<tr height="15px"><td colspan="2"></td></tr>'.PHP_EOL;
									$ContentPg[$page].='<tr><td><input type="submit" name="GoBack" value="&lt;&lt; Terug" class="boxlook_content_button" /></td>'.PHP_EOL;
									$ContentPg[$page].='	<td><input type="submit" name="GoForward" value="'.($page<count($PgFldList)?'Verder &gt;&gt;':'Verzenden &bull;&bull;&bull;').'" class="boxlook_content_button" /></td></tr>'.PHP_EOL;
									$ContentPg[$page].='</table>'.PHP_EOL;
									$ContentPg[$page].='</form>'.PHP_EOL;

									break;
								} else {
									$ContentPg[$page]="";
									if ($pag_skip<1) $page--; else $page++; //skip page, directly go to previous/next page
									$DoRepeat=TRUE;
									break;
								}

							case 10: //final page, check all and send input, display confirmation page whether error/succesful processed
								//check all pages again, on error it will hold there and focus is set to the page and field

								$CheckAll++;
								if ($CheckAll==1) {
									$page=1;
									$DoRepeat=TRUE;
									break;
								}
								$DoRepeat=FALSE;

								//TODO generate pdf-file
								$email_attachments=array(0=>"./offerte.php",1=>"./images/ok.png"); //TEST
								$email_attachments=array();
								$email_Chkfiles=TRUE;

								//send offerte per e-mail
								//- define email type
								$email_ContentType=$ApplInit["email_ContentType"];
								//-define email address
								$email_to=''.$ApplInit["email_to"].'';
								//if ($_SERVER["SERVER_NAME"]=="www.go2all.nl") $email_to='ggj.cremers@casema.nl'; //TEST, overrule
								$email_from='"hs-interieur" <'.$ApplInit["email_to"].'>';
								$email_replyto=$email_to;
								$email_failto=''; //NOT_TODO, not supported
								$email_cc="";
								$email_bcc="";
								//-define email subject
								$email_subject='Offerte HS-INTERIEUR';
								//-compose email message, txt/html
								$email_msg="";
								if ($email_ContentType=="txt") {
									//$email_msg=$email_beginning.PHP_EOL.PHP_EOL;
									//$email_msg.='Hartelijk dank voor uw offerte aanvraag. Wij zullen zo spoedig mogelijk contact met u opnemen.'.PHP_EOL.PHP_EOL.'Een copy van uw offerte gegevens is onder aan deze e-mail toegevoegd.'.PHP_EOL.PHP_EOL;
									//$email_msg.='Met vriendelijke groet,'.PHP_EOL.'Dennis Sieval'.PHP_EOL.PHP_EOL.PHP_EOL;
									$email_msg.="Offerte HS-INTERIEUR:".PHP_EOL.PHP_EOL;
									foreach($PgFldList as $pg=>$FldList) {
										if ($FldList>"") {
											$flds=explode(",", $FldList);
											foreach ($flds as $fld) {
												$val=$PgFldVal[$pg][$fld];

												if ($fld=="off_materiaal") {
													$val_materiaal=$val;
													$val=$arrTabItems_MatKlr['btn'][$val_materiaal];
												}
												if ($fld=="off_kleur") {
													$valtxt="";
													if ($val_materiaal>"") {
														if ($val>"") {
															$valtxt=$arrTabItems_MatKlr['cnt'][$val_materiaal][$val];
														} else {
															$valtxt="n.v.t.";
														}
													}
													$val=$valtxt;
												}
												if ($fld=="off_spoelbaktype") {
													$valtxt="";
													if ($val>"") {
														foreach($arrTabItems_Spoelbak['cnt'] as $typeId=>$dum) {
															foreach($arrTabItems_Spoelbak['cnt'][$typeId] as $cntId=>$cntDescPrice) {
																if ($val==$cntId) {$valtxt=$cntDescPrice['desc']; break;}
															}
															if ($valtxt>"") break;
														}
													} else {
														$valtxt="n.v.t.";
													}
													$val=$valtxt;
												}
												if ($fld=="off_kraantype") {
													$valtxt="";
													if ($val>"") {
														foreach($arrTabItems_Kraan['cnt'] as $typeId=>$dum) {
															foreach($arrTabItems_Kraan['cnt'][$typeId] as $cntId=>$cntDescPrice) {
																if ($val==$cntId) {$valtxt=$cntDescPrice['desc']; break;}
															}
															if ($valtxt>"") break;
														}
													} else {
														$valtxt="n.v.t.";
													}
													$val=$valtxt;
												}

												$email_msg.=$fld."=".htmlspecialchars($val).PHP_EOL;
											}
											$email_msg.=PHP_EOL;
										}
									}
								} else {
									$email_msg.='<html><head><title>Offerte aanvragen | HS-INTERIEUR</title></head><body>';
									//$email_msg.=$email_beginning.'<br /><br />';
									//$email_msg.='Hartelijk dank voor uw offerte aanvraag. Wij zullen zo spoedig mogelijk contact met u opnemen.<br /><br />Een copy van uw offerte gegevens is onder aan deze e-mail toegevoegd.<br /><br />';
									//$email_msg.='Met vriendelijke groet,<br />Dennis Sieval<br /><br /><br /><hr><br /><br />';
									foreach($PgFldList as $pg=>$FldList) {
										if ($pg>1 && $FldList>"") $email_msg.='<br /><br /><br /><hr><br /><br />';
										$email_msg.=$ContentPg[$pg];
									}
									$email_msg.='</body></html>';
								}
								//- send email
								$result=TRUE;
								//if ($ApplInit["email_test"]==1 && $_SERVER["REMOTE_ADDR"]=="127.0.0.1") { //TEST, no local email server present
								if ($ApplInit["email_test"]==1) { //TEST, no local email server present
									$result=TRUE; //TEST
									if (1==1 && $result==TRUE) { //TEST, output email content to browser
										echo 'email_from: '.$email_from.'<br />';
										echo 'email_to: '.$email_to.'<br />';
										echo 'email_cc: '.$email_cc.'<br />';
										echo 'email_bcc: '.$email_bcc.'<br />';
										echo 'email_replyto: '.$email_replyto.'<br />';
										echo 'email_subject: '.$email_subject.'<br />';
										echo 'email_attachments: '.print_r($email_attachments).'<br />';
										echo 'email_msg:<br />';
										echo $email_msg.'<hr>';
									}
								} else {
									if (!@is_file("./cmp_email.php")) die("Component 'cmp_email.php' is missing.<br />");
									@include("./cmp_email.php");
									$result=Fnc_email_smtp($email_ContentType, $email_from, $email_to, $email_cc, $email_bcc, $email_replyto, $email_failto, $email_subject, $email_msg, $email_Chkfiles, $email_attachments);
								}

								//output html form
								//- display error page
								if ($result==FALSE) { //go back to previous page and try to send the email again
									echo '<h1><font color="#FF0000">Fout bij e-mail verzending</font></h1><br />'.PHP_EOL;
									echo '<form name="input" action="'.$action.'" method="post">'.PHP_EOL;
									echo '<input type="hidden" name="page" value="'.$page.'" />'.PHP_EOL;
									foreach($PgFldList as $pg=>$FldList) {
										if ($FldList>"") {
											$flds=explode(",", $FldList);
											foreach ($flds as $fld) {
												$val=$PgFldVal[$pg][$fld];
												echo '<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
											}
										}
									}
									echo '<table cellpadding="4" cellspacing="0">'.PHP_EOL;
									echo '<tr><td class="FrmTblTdLabel">&nbsp;</td>'.PHP_EOL;
									echo '	<td>Uw offerte aanvraag kon niet per e-mail verzonden worden. controleer uw e-mail adres en probeer opnieuw.<br /></td></tr>'.PHP_EOL;
									echo '<tr height="15px"><td colspan="2"></td></tr>'.PHP_EOL;
									echo '<tr><td><input type="submit" name="GoBack" value="&lt;&lt; Terug" class="boxlook_content_button" /></td>'.PHP_EOL;
									echo '	<td>&nbsp;</td></tr>'.PHP_EOL;
									echo '</table>'.PHP_EOL;
									echo '</form>'.PHP_EOL;
									break;
								}

								//- display confirmation page
								$tmppage=1; //go back to page 1 for a new proposition
								echo '<h1>Bevestiging</h1><br />'.PHP_EOL;
								echo '<form name="input" action="'.$action.'" method="post">'.PHP_EOL;
								echo '<input type="hidden" name="page" value="'.$tmppage.'" />'.PHP_EOL;
								foreach($PgFldList as $pg=>$FldList) {
									if ($FldList>"") {
										$flds=explode(",", $FldList);
										foreach ($flds as $fld) {
											$val=$PgFldVal[$pg][$fld];
											echo '<input type="hidden" name="'.$fld.'" value="'.htmlspecialchars($val).'" />'.PHP_EOL;
										}
									}
								}
								echo '<table cellpadding="4" cellspacing="0">'.PHP_EOL;
								echo '<tr><td class="FrmTblTdLabel">&nbsp;</td>'.PHP_EOL;
								//echo '	<td>Hartelijk dank voor uw offerte aanvraag. Een copy van uw aanvraag is naar uw e-mail adres gestuurd.<br />De offerte zullen wij zo spoedig mogelijk toesturen.<br /><br /></td></tr>'.PHP_EOL;
								echo '	<td>'.$email_beginning.',<br /><br />';
								echo 'Hartelijk dank voor uw offerte aanvraag.<br />Wij zullen u deze zo spoedig mogelijk toesturen.<br /></td></tr>'.PHP_EOL;
								echo '<tr height="15px"><td colspan="2"></td></tr>'.PHP_EOL;
								echo '<tr><td><input type="submit" name="GoNew" value="Nieuwe offerte" class="boxlook_content_button" /></td>'.PHP_EOL;
								echo '	<td>&nbsp;</td></tr>'.PHP_EOL;
								echo '</table>'.PHP_EOL;
								echo '</form>'.PHP_EOL;
								break;

							default: //unknown page request
								$page=1;
								if (isset($_POST["GoForward"])) unset($_POST["GoForward"]);
								$DoRepeat=TRUE;
						}
					} while ($DoRepeat==TRUE);

					//get name of field to focus to
					$FocusFld="";
					foreach($msg as $fld=>$txt) {
						if ($txt>"") {
							$FocusFld=$fld;
							break;
						}
					}
					if ($FocusFld=="") { //choose field that is first in list
						if ($mode=="html" && isset($PgFldList[$page]) && $PgFldList[$page]>"") {
							$flds=explode(",", $PgFldList[$page]);
							$FocusFld=$flds[0];
						}
					}
					if ($page==1 & $mode=="html") { //special fields on tab page to set focus to a tab content item
						$FocusFld="";
						if ($val_materiaal>"" && $val_kleur>"") $FocusFld="tabCntBlock_".$arrTabItems_MatKlr['css_id']['tabCntContainer'].'_'.$val_materiaal."_".$val_kleur;
					}
					if ($page==2 & $mode=="html") { //ommit hidden fields
						$fld_model="off_model";
						if ($FocusFld==$fld_model) $FocusFld="";
						if ($PgFldVal[$page][$fld_model]=="") $FocusFld="";
						if ($FocusFld=="" && $PgFldVal[$page][$fld_model]>"") {
							$FocusFld="off_blad1_lengte";
							for ($i=1; $i<10; $i++) {
								$fld_L="off_blad".$i."_lengte";
								$fld_B="off_blad".$i."_breedte";
								$val_L=$PgFldVal[$page][$fld_L];
								$val_B=$PgFldVal[$page][$fld_B];
								if ($val_L>"") {$FocusFld=$fld_L; break;}
								if ($val_B>"") {$FocusFld=$fld_B; break;}
							}
						}
					}
//					if ($page==3 & $mode=="html") { //special fields on tab page to set focus to a tab content item
//						$FocusFld="";
//						if ($val_materiaal>"" && $val_kleur>"") $FocusFld="tabCntBlock_".$arrTabItems_MatKlr['css_id']['tabCntContainer'].'_'.$val_materiaal."_".$val_kleur;
//					}
					if ($page==7 & $mode=="html") { //special fields on tab page to set focus to a tab content item
						$fld_spoelbaklevering="off_spoelbaklevering";
						$val_spoelbaklevering=$PgFldVal[$page][$fld_spoelbaklevering];
						$fld_spoelbaktype="off_spoelbaktype";
						$val_spoelbaktype=$PgFldVal[$page][$fld_spoelbaktype];
						if ($val_spoelbaklevering=="J" && $val_spoelbaktype=="") $FocusFld="tabCntBlock_".$arrTabItems_Spoelbak['css_id']['tabCntContainer'].'_all_'.$val_spoelbaktype;
						if ($FocusFld=="") {
							$fld_kraanlevering="off_kraanlevering";
							$val_kraanlevering=$PgFldVal[$page][$fld_kraanlevering];
							$fld_kraantype="off_kraantype";
							$val_kraantype=$PgFldVal[$page][$fld_kraantype];
							if ($val_kraanlevering=="J" && $val_kraantype=="") $FocusFld="tabCntBlock_".$arrTabItems_Kraan['css_id']['tabCntContainer'].'_all_'.$val_kraantype;
						}
					}
					if ($mode!="html") $FocusFld="";

					?>
					<br /><br />	</div>
			</div>
			<!-- google_ad_section_end -->

			<!-- footer begin -->
			<div class="clearfix"></div>
			<div id="mwwFooter">
				<div id="footer">
					<div class="inner">
						� 2011 - 2015 HS-Interieur
						| <a href="http://www.hs-interieur.nl/sitemap/" class="footer">sitemap</a>
						| <a href="http://www.hs-interieur.nl/rss/" class="footer" target="_blank">rss</a>
					</div>
				</div>
			</div>
			<!-- footer end -->

		</div>
	</div>
	<!--<br /><br />-->
</div>

<!-- BEGIN - Google Analytics -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("UA-27979162-1");
		pageTracker._trackPageview();
	} catch(err) {}
</script>
<!-- END - Google Analytics -->
<!-- BEGIN - Google Analytics - Total -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var lemonTracker = _gat._getTracker("UA-3963595-5");
		lemonTracker._trackPageview();
	} catch(err) {}
</script>
<!-- END - Google Analytics - Total -->

</body>
</html>

<!---focus field--->
<script type="text/javascript">
	var sFocusFld = "<?php echo $FocusFld; ?>";
	if (sFocusFld != "") {
		if (document.getElementsByName(sFocusFld).length>0) {
			document.getElementsByName(sFocusFld)[0].focus();
			//fld=document.getElementsByName(sFocusFld)[0];
			//setTimeout(function(){fld.focus()}, 0);
		}
	}
</script>