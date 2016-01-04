//material and color
function GoForwardClick() {
	document.getElementById('GoForward').click();
	return false;
}

function tabBtnClick(thisTabBtn, tabBtnContainer, tabCntContainer) {
	if (thisTabBtn.className=='tabBtnActive') return false;
	var id='';
	var i=0;

	arrTabBtns=document.getElementById(tabBtnContainer).getElementsByTagName('a');
	for (i=0; i<arrTabBtns.length; i++) {
		arrTabBtns[i].className='tabBtnInactive';
	}
	thisTabBtn.className='tabBtnActive';

	arrTabCnts=document.getElementById(tabCntContainer).getElementsByTagName('div');
	for (i=0; i<arrTabCnts.length; i++) {
		if (arrTabCnts[i].className=='tabCntContainerElem tabCntActive') {
			arrTabCnts[i].className='tabCntContainerElem tabCntInactive';
		}
	}
	id=tabCntContainer+'_'+thisTabBtn.name;
	document.getElementById(id).className='tabCntContainerElem tabCntActive';

	//SetPosGoBackGoForward(id);
	return false;
} //tabBtnClick

function SetPosGoBackGoForward(id) {
	var y=0;
	switch (id) {
	case "tabBut_MatKlr_BC":
		y=660;
		break;
	case "tabBut_MatKlr_L":
		y=990;
		break;
	case "tabBut_Vorm_R":
		y=600;
		break;
	case "tabBut_Vorm_L":
	case "tabBut_Vorm_U":
		y=800;
		break;
	}
	if (y==0) return;
	var el=document.getElementById('GoBackContent');
	if (el>"") {
		el.style.position="absolute";
		el.style.top=""+y+"px";
	}
	el=document.getElementById('GoForwardContent');
	if (el>"") {
		el.style.position="absolute";
		el.style.top=""+y+"px";
	}
}

function tabCntBlockClick(thisCntBlock, btnId, cntId, tabCntContainer, UpdFldRef) {
	if (thisCntBlock.parentNode.className=='tabCntNone') {
		if (thisCntBlock.parentNode.getElementsByTagName('img')[0].className=='tabCntNoneActive') return false;
	} else {
		if (UpdFldRef!='Spoelbak' && UpdFldRef!='Kraan') {
			if (thisCntBlock.getElementsByTagName('div')[0].className=='tabCntBlockActive') return false;
		}
	}
	var id='';

	arrTabCnts=document.getElementById(tabCntContainer).getElementsByTagName('img');
	for (i=0;i<arrTabCnts.length;i++) {
		if (arrTabCnts[i].parentNode.className=='tabCntBlockActive') {
			id=arrTabCnts[i].parentNode.id;
			document.getElementById(id).className='tabCntBlockInactive';
		}
		if (arrTabCnts[i].parentNode.className=='tabCntNone') {
			id=arrTabCnts[i].parentNode.getElementsByTagName('img')[0].id;
			document.getElementById(id).className='tabCntNoneInactive';
		}
	}
	if (btnId>"") {
		if (cntId>"") {
			thisCntBlock.getElementsByTagName('div')[0].className='tabCntBlockActive';
			id='tabCntBlock_'+tabCntContainer+'_'+btnId+'_'+cntId;
			document.getElementsByName(id)[0].focus();
		} else {
			id=thisCntBlock.parentNode.getElementsByTagName('img')[0].id;
			document.getElementById(id).className='tabCntNoneActive';
		}
	}

	switch(UpdFldRef) {
	case 'MatKlr':
		document.getElementsByName('off_materiaal')[0].value=btnId;
		document.getElementsByName('off_kleur')[0].value=cntId;
	  break;
	case 'Spoelbak':
		document.getElementsByName('off_spoelbaktype')[0].value=cntId;
		document.getElementById('off_spoelbaktype'+'_nvt').style.display='none';
		elem_type_txt=document.getElementById('off_spoelbaktype'+'_txt');
		elem_type_txt.innerHTML=document.getElementById('tabCntBlock_'+tabCntContainer+'_all_'+cntId+'_txt').innerHTML;
		elem_type_txt.style.display='inline';
		document.getElementById('off_spoelbaktype'+'_wyz').style.display='inline';
		elem_type_img=document.getElementById('off_spoelbaktype'+'_img');
		elem_type_img.getElementsByTagName('img')[0].src=document.getElementById('tabCntBlock_'+tabCntContainer+'_all_'+cntId+'_img').src;
		elem_type_img.style.display='inline';
		document.getElementById(tabCntContainer).style.display='none';
		document.getElementById('emptyrow_sink_block').style.display='none';
		document.getElementById('off_spoelbaklevering'+'_msg').style.display='none';;
		document.getElementById('off_spoelbaktype'+'_msg').style.display='none';;
	  break;
	case 'Kraan':
		document.getElementsByName('off_kraantype')[0].value=cntId;
		document.getElementById('off_kraantype'+'_nvt').style.display='none';
		elem_type_txt=document.getElementById('off_kraantype'+'_txt');
		elem_type_txt.innerHTML=document.getElementById('tabCntBlock_'+tabCntContainer+'_all_'+cntId+'_txt').innerHTML;
		elem_type_txt.style.display='inline';
		document.getElementById('off_kraantype'+'_wyz').style.display='inline';
		elem_type_img=document.getElementById('off_kraantype'+'_img');
		elem_type_img.getElementsByTagName('img')[0].src=document.getElementById('tabCntBlock_'+tabCntContainer+'_all_'+cntId+'_img').src;
		elem_type_img.style.display='inline';
		document.getElementById(tabCntContainer).style.display='none';
		document.getElementById('emptyrow_watertap_block').style.display='none';
		document.getElementById('off_kraanlevering'+'_msg').style.display='none';;
		document.getElementById('off_kraantype'+'_msg').style.display='none';;
	  break;
	default:
	}
	return false;
} //tabCntBlockClick

//shape and measures
function tabBtnClick_shape(thisTabBtn, tabBtnContainer, tabBtnUpdFrmElm, tabCntContainer) {
	if (thisTabBtn.className=='tabBtnActive') return false;
	var id='';
	var i=0;

	arrTabBtns=document.getElementById(tabBtnContainer).getElementsByTagName('a');
	for (i=0;i<arrTabBtns.length;i++) {
		arrTabBtns[i].className='tabBtnInactive';
	}
	thisTabBtn.className='tabBtnActive';
	document.getElementsByName(tabBtnUpdFrmElm)[0].value=thisTabBtn.name;

	tabBtnSet_shape(thisTabBtn.name, tabCntContainer);

	return false;
} //tabBtnClick_shape

function tabBtnSet_shape(TabBtnName, tabCntContainer) {
	var id='';
	var i=0;

	id=tabCntContainer+'_msg';
	document.getElementById(id).style.display='none';
	for (i=1;i<10;i++) {
		id=tabCntContainer+'_'+i;
		document.getElementById(id).style.display='none';
	}
	if (TabBtnName=='') return;
	id=tabCntContainer+'_msg';
	document.getElementById(id).style.display='block';
	document.getElementById(tabCntContainer).style.display='block';

	id=tabCntContainer+'_msg_txt';
	document.getElementById(id).innerHTML='';
	if (TabBtnName=='R') {
		document.getElementById(id).innerHTML+=sTxtShapeSingle;
	} else {
		document.getElementById(id).innerHTML+=sTxtShapeMulti;
	}
	if (TabBtnName=='L') document.getElementById(id).innerHTML+=sTxtShapeL;
	if (TabBtnName=='E') document.getElementById(id).innerHTML+=sTxtShapeE;
	document.getElementById(id).innerHTML+=sTxtStove;

	var plateXshift=10; //shift all plates to right
	var inpW=50; var inpH=18; //width and height of input field
	var inpStoveW=13; var inpStoveH=12; //width and height of checkbox field
	var inpStoveXshift=-2; var inpStoveHYshift=8; var inpStoveVYshift=2; //excentricity of checkbox widget
	var plateX, plateY, plateW, plateH, orientation, display, anyStove, displayStove; //position and size of plate
	var tmp;
	for (i=1; i<10; i++) {
		display="none";
		anyStove=true;
		switch (i) {
		case 1:
			plateX=40+plateXshift; plateY=inpH; //Left Top absolute position of a plate block
			plateW=160; plateH=60; orientation="H"; //width, height, and orientation (=Horizontal/Vertical) of a plate
			if (TabBtnName!="E") {plateX=10+plateXshift; plateW=220; plateH=40;}
			if (TabBtnName>"") display="inline-block";
			if (TabBtnName=="E") anyStove=false;
			break;
		case 2:
			plateX=390+plateXshift; plateY=inpH;
			plateW=220; plateH=40; orientation="H";
			if (TabBtnName=="L" || TabBtnName=="U") {plateX=10+plateXshift; plateY=inpH+inpH+40+2; plateW=45; plateH=160; orientation="V";}
			if (TabBtnName=="L" || TabBtnName=="U" || TabBtnName=="E") display="inline-block";
			break;
		case 3:
			plateX=420+plateXshift; plateY=inpH+inpH+40+2+40;
			plateW=160; plateH=60; orientation="H";
			if (TabBtnName=="L") {plateX=390+plateXshift; plateY=inpH; plateW=220; plateH=40;}
			if (TabBtnName=="U") {plateX=185+plateXshift; plateY=inpH+inpH+40+2; plateW=45; plateH=160; orientation="V";}
			if (TabBtnName=="L" || TabBtnName=="U" || TabBtnName=="E") display="inline-block";
			if (TabBtnName=="E") anyStove=false;
			break;
		case 4:
			plateX=10+plateXshift; plateY=inpH+300;
			plateW=220; plateH=40; orientation="H";
			if (TabBtnName=="L") {plateX=565+plateXshift; plateY=inpH+inpH+40+2; plateW=45; plateH=160; orientation="V";}
			if (TabBtnName=="L" || TabBtnName=="E") display="inline-block";
			break;
		case 5:
			plateX=10+plateXshift; plateY=inpH+300+inpH+40+2;
			plateW=45; plateH=160; orientation="V";
			if (TabBtnName=="E") display="inline-block";
			break;
		case 6:
			plateX=170+plateXshift; plateY=inpH+300+inpH+40+2+160-120;
			plateW=60; plateH=120; orientation="V";
			if (TabBtnName=="E") {display="inline-block"; anyStove=false;}
			break;
		case 7:
			plateX=390+plateXshift; plateY=inpH+300;
			plateW=220; plateH=40; orientation="H";
			if (TabBtnName=="E") display="inline-block";
			break;
		case 8:
			plateX=565+plateXshift; plateY=inpH+300+inpH+40+2;
			plateW=45; plateH=160; orientation="V";
			if (TabBtnName=="E") display="inline-block";
			break;
		case 9:
			plateX=390+plateXshift; plateY=inpH+300+inpH+40+2+160-120;
			plateW=60; plateH=120; orientation="V";
			if (TabBtnName=="E") {display="inline-block"; anyStove=false;}
			break;
		default:
			alert('Onbekende vorm defintie in js functie "tabBtnSet_shape()"');
		}

		var fld_Stove="off_blad"+i+"_fornuis";
		if (i>1) {
			var fld_L="off_blad"+i+"_lengte";
			var fld_L2="off_blad"+i+"_lengte2";
			var fld_B="off_blad"+i+"_breedte";
			document.getElementsByName(fld_L)[0].value="";
			document.getElementsByName(fld_Stove)[0].checked=false;
			document.getElementsByName(fld_L2)[0].value="";
			document.getElementsByName(fld_B)[0].value="";
		}
		var val_Stove=document.getElementsByName(fld_Stove)[0].checked;
		displayStove=((anyStove==false || val_Stove==false)?"none":"inline-block");

		id=tabCntContainer+'_'+i;
		el=document.getElementById(id);
		el.style.display=display;
		el.style.top=''+plateY+'px';
		el.style.left=''+plateX+'px';
		el.style.height=''+(inpH+2+plateH)+'px';
		el.style.width=''+(plateW+inpW)+'px';

		if (orientation=="H") { //horizontal
			id=tabCntContainer+'_'+i+'_plate';
			el=document.getElementById(id);
			el.style.top=''+(inpH+2+1)+'px';
			el.style.left='0px';
			el.style.height=''+(plateH-2)+'px';
			el.style.width=''+(plateW-2)+'px';

			id=tabCntContainer+'_'+i+'_plateNr';
			el=document.getElementById(id);
			el.style.textAlign='left';
			el.style.verticalAlign='middle';
			el.style.lineHeight='120%';
			el.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+(i==1?'BLAD ':'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')+i;

			tmp=Math.floor(0.5*(plateW-plateH));
			id=tabCntContainer+'_'+i+'_mStoveImage';
			el=document.getElementById(id);
			el.style.display=displayStove;
			el.style.top=''+(0.5*plateH+2)+'px';
			el.style.left=''+tmp+'px';
			el.style.width=''+plateH+'px';
			el.style.height=''+(plateH-4)+'px';

			tmp=Math.floor(0.5*(plateW-inpStoveW));
			id=tabCntContainer+'_'+i+'_mStove';
			el=document.getElementById(id);
			el.style.display=(anyStove?'inline-block':'none');
			el.style.top=''+(plateH+inpStoveH+inpStoveHYshift)+'px';
			el.style.left=''+(tmp+inpStoveXshift)+'px';

			tmp=Math.floor((anyStove==false?0.5:0.2)*(plateW-inpW));
			id=tabCntContainer+'_'+i+'_mL';
			el=document.getElementById(id);
			el.style.top='0px';
			el.style.left=''+tmp+'px';

			tmp=Math.floor(0.8*(plateW-inpW));
			id=tabCntContainer+'_'+i+'_mL2';
			el=document.getElementById(id);
			el.style.display=displayStove;
			el.style.top='0px';
			el.style.left=''+tmp+'px';

			tmp=inpH+2+Math.floor(0.5*(plateH-(inpH+2)));
			id=tabCntContainer+'_'+i+'_mB';
			el=document.getElementById(id);
			el.style.top=''+tmp+'px';
			el.style.left=''+plateW+'px';
		}

		if (orientation=="V") { //vertical
			id=tabCntContainer+'_'+i+'_plate';
			el=document.getElementById(id);
			el.style.top='0px';
			el.style.left='0px';
			el.style.height=''+(plateH-2)+'px';
			el.style.width=''+(plateW-2)+'px';

			id=tabCntContainer+'_'+i+'_plateNr';
			el=document.getElementById(id);
			el.style.textAlign='center';
			el.style.verticalAlign='bottom';
			el.style.lineHeight='620%';
			el.innerHTML=i;

			tmp=Math.floor(0.5*(plateH-plateW));
			id=tabCntContainer+'_'+i+'_mStoveImage';
			el=document.getElementById(id);
			el.style.display=displayStove;
			el.style.top=''+(tmp+2)+'px';
			el.style.left='1px';
			el.style.width=''+(plateW-4)+'px';
			el.style.height=''+(plateW-4)+'px';

			tmp=Math.floor(0.5*(plateH-(inpStoveH+2)));
			id=tabCntContainer+'_'+i+'_mStove';
			el=document.getElementById(id);
			el.style.display=(anyStove?'inline-block':'none');
			el.style.top=''+(tmp+inpStoveVYshift)+'px';
			el.style.left=''+(-inpStoveW+inpStoveXshift)+'px';

			tmp=Math.floor((anyStove==false?0.5:0.2)*(plateH-(inpH+2)));
			id=tabCntContainer+'_'+i+'_mL';
			el=document.getElementById(id);
			el.style.top=''+tmp+'px';
			el.style.left=''+plateW+'px';

			tmp=Math.floor(0.8*(plateH-(inpH+2)));
			id=tabCntContainer+'_'+i+'_mL2';
			el=document.getElementById(id);
			el.style.display=displayStove;
			el.style.top=''+tmp+'px';
			el.style.left=''+plateW+'px';

			tmp=Math.floor(0.5*(plateW-inpW));
			id=tabCntContainer+'_'+i+'_mB';
			el=document.getElementById(id);
			el.style.top=''+plateH+'px';
			el.style.left=''+tmp+'px';
		}
	}
} //tabBtnSet_shape


//shape with/without a stove
function ToggleStoveElem(tabCntContainer) {
	var setDisplay='none';
	id=tabCntContainer+'_mStoveImage';
	el=document.getElementById(id);
	if (el.style.display=='none') {
		setDisplay='inline-block';
	}
	el.style.display=setDisplay;
	id=tabCntContainer+'_mL2';
	el=document.getElementById(id);
	el.style.display=setDisplay;
} //ToggleStoveElem

//thickening
function check_thickening() {
	return true;
	var id='';
	elem_thickness=document.getElementsByName('off_dikte');
	elem_brim=document.getElementsByName('off_randafwerking');
	elem_thickening=document.getElementsByName('off_opdikken');

	if (elem_thickness[0].checked==true && elem_brim[0].checked==true && elem_thickening[0].checked==true) {
		for (i=0;i<elem_thickness.length;i++) {
			elem_thickness[i].disabled=false;
		}
		for (i=0;i<elem_brim.length;i++) {
			elem_brim[i].disabled=false;
		}
		for (i=0;i<elem_thickening.length;i++) {
			elem_thickening[i].disabled=false;
		}
		return true;
	}

	if (elem_thickness[0].checked==false || elem_brim[0].checked==false) {
		for (i=0;i<elem_thickness.length;i++) {
			elem_thickness[i].disabled=false;
		}
		for (i=0;i<elem_brim.length;i++) {
			elem_brim[i].disabled=false;
		}
		for (i=0;i<elem_thickening.length;i++) {
			elem_thickening[i].disabled=true;
		}
		if (elem_thickening[0].checked==false) {
			elem_thickening[0].checked=true;
			alert('Opdikken is alleen mogelijk bij een standaard dikte of randafwerking.');
		}
		return true;
	}

	for (i=0;i<elem_thickness.length;i++) {
		elem_thickness[i].disabled=true;
	}
	if (elem_thickness[0].checked==false) {
		elem_thickness[0].checked=true;
		alert('Bij opdikken is alleen de standaard dikte toegestaan.');
	}
	for (i=0;i<elem_brim.length;i++) {
		elem_brim[i].disabled=true;
	}
	if (elem_brim[0].checked==false) {
		elem_brim[0].checked=true;
		alert('Bij opdikken is alleen de standaard randafwerking toegestaan.');
	}
	for (i=0;i<elem_thickening.length;i++) {
		elem_thickening[i].disabled=false;
	}
	return true;
}

//washbasin
function Sink_hole(thisRadio, tabCntContainer) {
	elem_supply_nvt=document.getElementById('off_spoelbaklevering'+'_nvt');
	elem_supply_inp=document.getElementById('off_spoelbaklevering'+'_inp');
	elem_supply_J=document.getElementById('off_spoelbaklevering'+'_J');
	elem_supply_N=document.getElementById('off_spoelbaklevering'+'_N');
	if (thisRadio.value=='' && thisRadio.checked==true) {
		elem_supply_nvt.style.display='inline';
		elem_supply_inp.style.display='none';
		if (elem_supply_J.checked==true) {
			elem_supply_J.checked=false;
			elem_supply_N.checked=true;
			Sink_supply(elem_supply_N, tabCntContainer);
		}
	} else {
		elem_supply_nvt.style.display='none';
		elem_supply_inp.style.display='inline';
	}
	document.getElementById('off_spoelbaklevering'+'_msg').style.display='none';
	document.getElementById('off_spoelbaktype'+'_msg').style.display='none';;
} //Sink_hole
function Sink_supply(thisRadio, tabCntContainer) {
	var id='';
	var i=0;
	document.getElementById('off_spoelbaklevering'+'_msg').style.display='none';;
	document.getElementById('off_spoelbaktype'+'_msg').style.display='none';;

	elem_type_nvt=document.getElementById('off_spoelbaktype'+'_nvt');
	elem_type_txt=document.getElementById('off_spoelbaktype'+'_txt');
	elem_type_wyz=document.getElementById('off_spoelbaktype'+'_wyz');
	elem_type_img=document.getElementById('off_spoelbaktype'+'_img');
	document.getElementById('off_spoelbaktype'+'_msg').style.display='none';

	id=tabCntContainer+'_all';
	if (thisRadio.value=='J' && thisRadio.checked==true) {
		elem_type_nvt.style.display='none';
		elem_type_txt.style.display='inline';
		if (document.getElementsByName('off_spoelbaktype')[0].value!='') {
			elem_type_wyz.style.display='inline';
			elem_type_img.style.display='inline';
		}

		document.getElementById(tabCntContainer).style.display='block';
		//document.getElementById(id).className='tabCntContainerElem tabCntActive';
		document.getElementById('emptyrow_sink_block').style.display='block';
	} else {
		elem_type_nvt.style.display='inline';
		elem_type_txt.style.display='none';
		elem_type_txt.innerHTML="";
		elem_type_wyz.style.display='none';
		elem_type_img.style.display='none';

		document.getElementById(tabCntContainer).style.display='none';
		//document.getElementById(id).className='tabCntContainerElem tabCntInactive';
		document.getElementById('emptyrow_sink_block').style.display='none';

		arrTabCnts=document.getElementById(tabCntContainer).getElementsByTagName('img');
		for (i=0;i<arrTabCnts.length;i++) {
			if (arrTabCnts[i].parentNode.className=='tabCntBlockActive') {
				id=arrTabCnts[i].parentNode.id;
				document.getElementById(id).className='tabCntBlockInactive';
			}
		}
		document.getElementsByName('off_spoelbaktype')[0].value='';
	}
}
function Sink_wyz(tabCntContainer) {
	var id='';
	document.getElementById(tabCntContainer).style.display='block';
	document.getElementById('emptyrow_sink_block').style.display='block';
	if (document.getElementsByName('off_spoelbaktype')[0].value=='') return false;

	arrTabCnts=document.getElementById(tabCntContainer).getElementsByTagName('img');
	for (i=0;i<arrTabCnts.length;i++) {
		if (arrTabCnts[i].parentNode.className=='tabCntBlockActive') {
			id=arrTabCnts[i].parentNode.id;
			break;
		}
	}
	if (id>"") document.getElementsByName(id)[0].focus();
	return false;
}

//watertap
function Watertap_hole(thisCombo, tabCntContainer) {
	elem_supply_nvt=document.getElementById('off_kraanlevering'+'_nvt');
	elem_supply_inp=document.getElementById('off_kraanlevering'+'_inp');
	elem_supply_J=document.getElementById('off_kraanlevering'+'_J');
	elem_supply_N=document.getElementById('off_kraanlevering'+'_N');
	if (thisCombo.value<'1') {
		elem_supply_nvt.style.display='inline';
		elem_supply_inp.style.display='none';
		if (elem_supply_J.checked==true) {
			elem_supply_J.checked=false;
			elem_supply_N.checked=true;
			Watertap_supply(elem_supply_N, tabCntContainer);
		}
	} else {
		elem_supply_nvt.style.display='none';
		elem_supply_inp.style.display='inline';
	}
	document.getElementById('off_kraanlevering'+'_msg').style.display='none';
	document.getElementById('off_kraantype'+'_msg').style.display='none';;
} //Watertap_hole
function Watertap_supply(thisRadio, tabCntContainer) {
	var id='';
	var i=0;
	document.getElementById('off_kraanlevering'+'_msg').style.display='none';;
	document.getElementById('off_kraantype'+'_msg').style.display='none';;

	elem_type_nvt=document.getElementById('off_kraantype'+'_nvt');
	elem_type_txt=document.getElementById('off_kraantype'+'_txt');
	elem_type_wyz=document.getElementById('off_kraantype'+'_wyz');
	elem_type_img=document.getElementById('off_kraantype'+'_img');
	document.getElementById('off_kraantype'+'_msg').style.display='none';

	id=tabCntContainer+'_all';
	if (thisRadio.value=='J' && thisRadio.checked==true) {
		elem_type_nvt.style.display='none';
		elem_type_txt.style.display='inline';
		if (document.getElementsByName('off_kraantype')[0].value!='') {
			elem_type_wyz.style.display='inline';
			elem_type_img.style.display='inline';
		}

		document.getElementById(tabCntContainer).style.display='block';
		//document.getElementById(id).className='tabCntContainerElem tabCntActive';
		document.getElementById('emptyrow_watertap_block').style.display='block';
	} else {
		elem_type_nvt.style.display='inline';
		elem_type_txt.style.display='none';
		elem_type_txt.innerHTML="";
		elem_type_wyz.style.display='none';
		elem_type_img.style.display='none';

		document.getElementById(tabCntContainer).style.display='none';
		//document.getElementById(id).className='tabCntContainerElem tabCntInactive';
		document.getElementById('emptyrow_watertap_block').style.display='none';

		arrTabCnts=document.getElementById(tabCntContainer).getElementsByTagName('img');
		for (i=0;i<arrTabCnts.length;i++) {
			if (arrTabCnts[i].parentNode.className=='tabCntBlockActive') {
				id=arrTabCnts[i].parentNode.id;
				document.getElementById(id).className='tabCntBlockInactive';
			}
		}
		document.getElementsByName('off_kraantype')[0].value='';
	}
}
function Watertap_wyz(tabCntContainer) {
	var id='';
	document.getElementById(tabCntContainer).style.display='block';
	document.getElementById('emptyrow_watertap_block').style.display='block';
	if (document.getElementsByName('off_kraantype')[0].value=='') return false;

	arrTabCnts=document.getElementById(tabCntContainer).getElementsByTagName('img');
	for (i=0;i<arrTabCnts.length;i++) {
		if (arrTabCnts[i].parentNode.className=='tabCntBlockActive') {
			id=arrTabCnts[i].parentNode.id;
			break;
		}
	}
	if (id>"") document.getElementsByName(id)[0].focus();
	return false;
}
