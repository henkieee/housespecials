<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 30-10-15
 * Time: 19:12
 */

//Dan de volgorde van de foto's van de spoelbakken wijzigen.
//40R, 50R, 34R, 74R, 1534R, 3434R, 17R, 40V, 50V, 74V, 1534V, 3434V, 17V

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
    'article21413050'=>array('desc'=>'Lorreine 74V', 'price'=>349.00, 'nonDiscountPrice'=>628.00),
    'article21413227'=>array('desc'=>'Lorreine 1534V', 'price'=>479.00, 'nonDiscountPrice'=>739.00),
    'article21413298'=>array('desc'=>'Lorreine 3434V', 'price'=>569.00, 'nonDiscountPrice'=>869.00),
    'article21412648'=>array('desc'=>'Lorreine 17V', 'price'=>345.00, 'nonDiscountPrice'=>545.00),
//    'article30270053'=>array('desc'=>'Lorreine RVS Inzetbak', 'price'=>99.00, 'nonDiscountPrice'=>149.00),
//    'article21412716'=>array('desc'=>'Lorreine 34V', 'price'=>329.00, 'nonDiscountPrice'=>517.00),
);

//Moet de 34V eruit

//step sub-title
$ContentPg[$page].='<div class="FrmHeader">'.PHP_EOL;
$ContentPg[$page].='<table cellpadding="0" cellspacing="0" border="0" width="100%">'.PHP_EOL;
$ContentPg[$page].='<tr><td class="FrmHeaderTitle_h2">'.$PgTitleTxtList[$page].'</td><td colspan="2"></td></tr>'.PHP_EOL;
$ContentPg[$page].='<tr>'.PHP_EOL;
if ($mode=="html") {
    $ContentPg[$page].='	<td>&nbsp;</td><td class="FrmHeaderBtns">'.($page>1?'<input type="submit" id="GoBack" name="GoBack" value="&lt;&lt; Terug" class="boxlook_content_button" />&nbsp;':NULL).'<input type="submit" id="GoForward" name="GoForward" value="'.($page<count($PgFldList)?'Verder &gt;&gt;':'Verzenden &bull;&bull;&bull;').'" class="boxlook_content_button" /></td></tr>'.PHP_EOL;
} else {
    $ContentPg[$page].='	<td colspan="2"></td></tr>'.PHP_EOL;
}
$ContentPg[$page].='</table>'.PHP_EOL;
$ContentPg[$page].='</div>'.PHP_EOL;