var _cmNodeProperties={prefix:"",mainFolderLeft:"",mainFolderRight:"",mainItemLeft:"",mainItemRight:"",folderLeft:"",folderRight:"",itemLeft:"",itemRight:"",mainSpacing:0,subSpacing:0,delay:500,zIndexStart:1000,zIndexInc:5,subMenuHeader:null,subMenuFooter:null,offsetHMainAdjust:[0,0],offsetVMainAdjust:[0,0],offsetSubAdjust:[0,0],clickOpen:1,effect:null};var _cmIDCount=0;var _cmIDName="cmSubMenuID";var _cmTimeOut=null;var _cmCurrentItem=null;var _cmNoAction=new Object();var _cmNoClick=new Object();var _cmSplit=new Object();var _cmMenuList=new Array();var _cmItemList=new Array();var _cmFrameList=new Array();var _cmFrameListSize=0;var _cmFrameIDCount=0;var _cmFrameMasking=true;/*@cc_on
	@if (@_jscript_version >= 5.6)
		if (_cmFrameMasking)
		{
			var v = navigator.appVersion;
			var i = v.indexOf ("MSIE ");
			if (i >= 0)
			{
				if (parseInt (navigator.appVersion.substring (i + 5)) >= 7)
					_cmFrameMasking = false;
			}
		}
	@end
@*/var _cmClicked=false;var _cmHideObjects=0;function cmClone(A){var B=new Object();for(v in A){B[v]=A[v]}return B}function cmAllocMenu(G,F,B,A,C){var D=new Object();D.div=G;D.menu=F;D.orient=B;D.nodeProperties=A;D.prefix=C;var E=_cmMenuList.length;_cmMenuList[E]=D;return E}function cmAllocFrame(){if(_cmFrameListSize>0){return cmGetObject(_cmFrameList[--_cmFrameListSize])}var A=document.createElement("iframe");var B=_cmFrameIDCount++;A.id="cmFrame"+B;A.frameBorder="0";A.style.display="none";A.src="javascript:false";document.body.appendChild(A);A.style.filter="alpha(opacity=0)";A.style.zIndex=99;A.style.position="absolute";A.style.border="0";A.scrolling="no";return A}function cmFreeFrame(A){_cmFrameList[_cmFrameListSize++]=A.id}function cmNewID(){return _cmIDName+(++_cmIDCount)}function cmActionItem(J,I,H,D,A){_cmItemList[_cmItemList.length]=J;var F=_cmItemList.length-1;H=(!H)?"null":("'"+H+"'");var E=D.nodeProperties.clickOpen;var G=(E==3)||(E==2&&I);var C="this,"+I+","+H+","+A+","+F;var B;if(G){B=' onmouseover="cmItemMouseOver('+C+',false)" onmousedown="cmItemMouseDownOpenSub ('+C+')"'}else{B=' onmouseover="cmItemMouseOverOpenSub ('+C+')" onmousedown="cmItemMouseDown ('+C+')"'}return B+' onmouseout="cmItemMouseOut ('+C+')" onmouseup="cmItemMouseUp ('+C+')"'}function cmNoClickItem(D,B,A,E,G){_cmItemList[_cmItemList.length]=D;var C=_cmItemList.length-1;A=(!A)?"null":("'"+A+"'");var F="this,"+B+","+A+","+G+","+C;return' onmouseover="cmItemMouseOver ('+F+')" onmouseout="cmItemMouseOut ('+F+')"'}function cmNoActionItem(A){return A[1]}function cmSplitItem(prefix,isMain,vertical){var classStr="cm"+prefix;if(isMain){classStr+="Main";if(vertical){classStr+="HSplit"}else{classStr+="VSplit"}}else{classStr+="HSplit"}return eval(classStr)}function cmDrawSubMenu(G,J,B,N,D,E,A){var K='<div class="'+J+'SubMenu" id="'+B+'" style="z-index: '+D+';position: absolute; top: 0px; left: 0px;">';if(N.subMenuHeader){K+=N.subMenuHeader}K+='<table summary="sub menu" id="'+B+'Table" cellspacing="'+N.subSpacing+'" class="'+J+'SubMenuTable">';var I="";var M;var L;var C;var H;var F;for(H=5;H<G.length;++H){M=G[H];if(!M){continue}if(M==_cmSplit){M=cmSplitItem(J,0,true)}M.parentItem=G;M.subMenuID=B;C=(M.length>5);L=C?cmNewID():null;K+='<tr class="'+J+'MenuItem"';if(M[0]!=_cmNoClick){K+=cmActionItem(M,0,L,E,A)}else{K+=cmNoClickItem(M,0,L,E,A)}K+=">";if(M[0]==_cmNoAction||M[0]==_cmNoClick){K+=cmNoActionItem(M);K+="</tr>";continue}F=J+"Menu";F+=C?"Folder":"Item";K+='<td class="'+F+'Left">';if(M[0]!=null){K+=M[0]}else{K+=C?N.folderLeft:N.itemLeft}K+='</td><td class="'+F+'Text">'+M[1];K+='</td><td class="'+F+'Right">';if(C){K+=N.folderRight;I+=cmDrawSubMenu(M,J,L,N,D+N.zIndexInc,E,A)}else{K+=N.itemRight}K+="</td></tr>"}K+="</table>";if(N.subMenuFooter){K+=N.subMenuFooter}K+="</div>"+I;return K}function cmDraw(B,C,H,P,L){var J=cmGetObject(B);if(!L){L=P.prefix}if(!L){L=""}if(!P){P=_cmNodeProperties}if(!H){H="hbr"}var A=cmAllocMenu(B,C,H,P,L);var F=_cmMenuList[A];if(!P.delay){P.delay=_cmNodeProperties.delay}if(!P.clickOpen){P.clickOpen=_cmNodeProperties.clickOpen}if(!P.zIndexStart){P.zIndexStart=_cmNodeProperties.zIndexStart}if(!P.zIndexInc){P.zIndexInc=_cmNodeProperties.zIndexInc}if(!P.offsetHMainAdjust){P.offsetHMainAdjust=_cmNodeProperties.offsetHMainAdjust}if(!P.offsetVMainAdjust){P.offsetVMainAdjust=_cmNodeProperties.offsetVMainAdjust}if(!P.offsetSubAdjust){P.offsetSubAdjust=_cmNodeProperties.offsetSubAdjust}F.cmFrameMasking=_cmFrameMasking;var M='<table summary="main menu" class="'+L+'Menu" cellspacing="'+P.mainSpacing+'">';var K="";var E;if(H.charAt(0)=="h"){M+="<tr>";E=false}else{E=true}var I;var O;var N;var D;var G;for(I=0;I<C.length;++I){O=C[I];if(!O){continue}O.menu=C;O.subMenuID=B;M+=E?"<tr":"<td";M+=' class="'+L+'MainItem"';D=(O.length>5);N=D?cmNewID():null;M+=cmActionItem(O,1,N,F,A)+">";if(O==_cmSplit){O=cmSplitItem(L,1,E)}if(O[0]==_cmNoAction||O[0]==_cmNoClick){M+=cmNoActionItem(O);M+=E?"</tr>":"</td>";continue}G=L+"Main"+(D?"Folder":"Item");M+=E?"<td":"<span";M+=' class="'+G+'Left">';M+=(O[0]==null)?(D?P.mainFolderLeft:P.mainItemLeft):O[0];M+=E?"</td>":"</span>";M+=E?"<td":"<span";M+=' class="'+G+'Text">';M+=O[1];M+=E?"</td>":"</span>";M+=E?"<td":"<span";M+=' class="'+G+'Right">';M+=D?P.mainFolderRight:P.mainItemRight;M+=E?"</td>":"</span>";M+=E?"</tr>":"</td>";if(D){K+=cmDrawSubMenu(O,L,N,P,P.zIndexStart,F,A)}}if(!E){M+="</tr>"}M+="</table>"+K;J.innerHTML=M}function cmDrawFromText(H,C,B,E){var F=cmGetObject(H);var G=null;for(var D=F.firstChild;D;D=D.nextSibling){if(!D.tagName){continue}var A=D.tagName.toLowerCase();if(A!="ul"&&A!="ol"){continue}G=cmDrawFromTextSubMenu(D);break}if(G){cmDraw(H,G,C,B,E)}}function cmDrawFromTextSubMenu(H){var D=new Array();for(var G=H.firstChild;G;G=G.nextSibling){if(!G.tagName||G.tagName.toLowerCase()!="li"){continue}if(G.firstChild==null){D[D.length]=_cmSplit;continue}var F=new Array();var E=G.firstChild;var C=false;for(;E;E=E.nextSibling){if(!E.tagName){continue}if(E.className=="cmNoClick"){F[0]=_cmNoClick;F[1]=getActionHTML(E);C=true;break}if(E.className=="cmNoAction"){F[0]=_cmNoAction;F[1]=getActionHTML(E);C=true;break}var A=E.tagName.toLowerCase();if(A!="span"){continue}if(!E.firstChild){F[0]=null}else{F[0]=E.innerHTML}E=E.nextSibling;break}if(C){D[D.length]=F;continue}if(!E){continue}for(;E;E=E.nextSibling){if(!E.tagName){continue}var A=E.tagName.toLowerCase();if(A=="a"){F[1]=E.innerHTML;F[2]=E.href;F[3]=E.target;F[4]=E.title;if(F[4]==""){F[4]=null}}else{if(A=="span"||A=="div"){F[1]=E.innerHTML;F[2]=null;F[3]=null;F[4]=null}}break}for(;E;E=E.nextSibling){if(!E.tagName){continue}var A=E.tagName.toLowerCase();if(A!="ul"&&A!="ol"){continue}var B=cmDrawFromTextSubMenu(E);for(i=0;i<B.length;++i){F[i+5]=B[i]}break}D[D.length]=F}return D}function getActionHTML(C){var A="<td></td><td></td><td></td>";var B;for(B=C.firstChild;B;B=B.nextSibling){if(B.tagName&&B.tagName.toLowerCase()=="table"){break}}if(!B){return A}for(B=B.firstChild;B;B=B.nextSibling){if(B.tagName&&B.tagName.toLowerCase()=="tbody"){break}}if(!B){return A}for(B=B.firstChild;B;B=B.nextSibling){if(B.tagName&&B.tagName.toLowerCase()=="tr"){break}}if(!B){return A}return B.innerHTML}function cmGetMenuItem(D){if(!D.subMenuID){return null}var A=cmGetObject(D.subMenuID);if(D.menu){var E=D.menu;A=A.firstChild.firstChild.firstChild.firstChild;var B;for(B=0;B<E.length;++B){if(E[B]==D){return A}A=A.nextSibling}}else{if(D.parentItem){var E=D.parentItem;var C=cmGetObject(D.subMenuID+"Table");if(!C){return null}A=C.firstChild.firstChild;var B;for(B=5;B<E.length;++B){if(E[B]==D){return A}A=A.nextSibling}}}return null}function cmDisableItem(B,C){if(!B){return }var A=cmGetMenuItem(B);if(!A){return }if(B.menu){A.className=C+"MainItemDisabled"}else{A.className=C+"MenuItemDisabled"}B.isDisabled=true}function cmEnableItem(B,C){if(!B){return }var A=cmGetMenuItem(B);if(!A){return }if(B.menu){menu.className=C+"MainItem"}else{menu.className=C+"MenuItem"}B.isDisabled=true}function cmItemMouseOver(D,L,K,A,F,N){if(!N&&_cmClicked){cmItemMouseOverOpenSub(D,L,K,A,F);return }clearTimeout(_cmTimeOut);if(_cmItemList[F].isDisabled){return }var E=_cmMenuList[A].prefix;if(!D.cmMenuID){D.cmMenuID=A;D.cmIsMain=L}var B=cmGetThisMenu(D,E);if(!B.cmItems){B.cmItems=new Array()}var C;for(C=0;C<B.cmItems.length;++C){if(B.cmItems[C]==D){break}}if(C==B.cmItems.length){B.cmItems[C]=D}if(_cmCurrentItem){if(_cmCurrentItem==D||_cmCurrentItem==B){var M=_cmItemList[F];cmSetStatus(M);return }var H=_cmMenuList[_cmCurrentItem.cmMenuID];var J=H.prefix;var I=cmGetThisMenu(_cmCurrentItem,J);if(I!=B.cmParentMenu){if(_cmCurrentItem.cmIsMain){_cmCurrentItem.className=J+"MainItem"}else{_cmCurrentItem.className=J+"MenuItem"}if(I.id!=K){cmHideMenu(I,B,H)}}}_cmCurrentItem=D;cmResetMenu(B,E);var M=_cmItemList[F];var G=cmIsDefaultItem(M);if(G){if(L){D.className=E+"MainItemHover"}else{D.className=E+"MenuItemHover"}}cmSetStatus(M)}function cmItemMouseOverOpenSub(E,I,H,A,G){clearTimeout(_cmTimeOut);if(_cmItemList[G].isDisabled){return }cmItemMouseOver(E,I,H,A,G,true);if(H){var D=cmGetObject(H);var B=_cmMenuList[A];var C=B.orient;var F=B.prefix;cmShowSubMenu(E,I,D,B)}}function cmItemMouseOut(D,B,A,F,C){var E=_cmMenuList[F].nodeProperties.delay;_cmTimeOut=window.setTimeout("cmHideMenuTime ()",E);window.defaultStatus=""}function cmItemMouseDown(E,B,A,F,C){if(_cmItemList[C].isDisabled){return }if(cmIsDefaultItem(_cmItemList[C])){var D=_cmMenuList[F].prefix;if(E.cmIsMain){E.className=D+"MainItemActive"}else{E.className=D+"MenuItemActive"}}}function cmItemMouseDownOpenSub(F,B,A,G,D){if(_cmItemList[D].isDisabled){return }_cmClicked=true;cmItemMouseDown(F,B,A,G,D);if(A){var C=cmGetObject(A);var E=_cmMenuList[G];cmShowSubMenu(F,B,C,E)}}function cmItemMouseUp(E,K,J,A,G){if(_cmItemList[G].isDisabled){return }var L=_cmItemList[G];var I=null,H="_self";if(L.length>2){I=L[2]}if(L.length>3&&L[3]){H=L[3]}if(I!=null){_cmClicked=false;window.open(I,H)}var D=_cmMenuList[A];var F=D.prefix;var C=cmGetThisMenu(E,F);var B=(L.length>5);if(!B){if(cmIsDefaultItem(L)){if(E.cmIsMain){E.className=F+"MainItem"}else{E.className=F+"MenuItem"}}cmHideMenu(C,null,D)}else{if(cmIsDefaultItem(L)){if(E.cmIsMain){E.className=F+"MainItemHover"}else{E.className=F+"MenuItemHover"}}}}function cmMoveSubMenu(obj,isMain,subMenu,menuInfo){var orient=menuInfo.orient;var offsetAdjust;if(isMain){if(orient.charAt(0)=="h"){offsetAdjust=menuInfo.nodeProperties.offsetHMainAdjust}else{offsetAdjust=menuInfo.nodeProperties.offsetVMainAdjust}}else{offsetAdjust=menuInfo.nodeProperties.offsetSubAdjust}if(!isMain&&orient.charAt(0)=="h"){orient="v"+orient.charAt(1)+orient.charAt(2)}var mode=String(orient);var p=subMenu.offsetParent;var subMenuWidth=cmGetWidth(subMenu);var horiz=cmGetHorizontalAlign(obj,mode,p,subMenuWidth);if(mode.charAt(0)=="h"){if(mode.charAt(1)=="b"){subMenu.style.top=(cmGetYAt(obj,p)+cmGetHeight(obj)+offsetAdjust[1])+"px"}else{subMenu.style.top=(cmGetYAt(obj,p)-cmGetHeight(subMenu)-offsetAdjust[1])+"px"}if(horiz=="r"){subMenu.style.left=(cmGetXAt(obj,p)+offsetAdjust[0])+"px"}else{subMenu.style.left=(cmGetXAt(obj,p)+cmGetWidth(obj)-subMenuWidth-offsetAdjust[0])+"px"}}else{if(horiz=="r"){subMenu.style.left=(cmGetXAt(obj,p)+cmGetWidth(obj)+offsetAdjust[0])+"px"}else{subMenu.style.left=(cmGetXAt(obj,p)-subMenuWidth-offsetAdjust[0])+"px"}if(mode.charAt(1)=="b"){subMenu.style.top=(cmGetYAt(obj,p)+offsetAdjust[1])+"px"}else{subMenu.style.top=(cmGetYAt(obj,p)+cmGetHeight(obj)-cmGetHeight(subMenu)+offsetAdjust[1])+"px"}}/*@cc_on
		@if (@_jscript_version >= 5.5)
			if (menuInfo.cmFrameMasking)
			{
				if (!subMenu.cmFrameObj)
				{
					var frameObj = cmAllocFrame ();
					subMenu.cmFrameObj = frameObj;
				}

				var frameObj = subMenu.cmFrameObj;
				frameObj.style.zIndex = subMenu.style.zIndex - 1;
				frameObj.style.left = (cmGetX (subMenu) - cmGetX (frameObj.offsetParent)) + 'px';
				frameObj.style.top = (cmGetY (subMenu)  - cmGetY (frameObj.offsetParent)) + 'px';
				frameObj.style.width = cmGetWidth (subMenu) + 'px';
				frameObj.style.height = cmGetHeight (subMenu) + 'px';
				frameObj.style.display = 'block';
			}
		@end
	@*/if(horiz!=orient.charAt(2)){orient=orient.charAt(0)+orient.charAt(1)+horiz}return orient}function cmGetHorizontalAlign(F,G,E,C){var H=G.charAt(2);if(!(document.body)){return H}var A=document.body;var B;var D;if(window.innerWidth){B=window.pageXOffset;D=window.innerWidth+B}else{if(A.clientWidth){B=A.clientLeft;D=A.clientWidth+B}else{return H}}if(G.charAt(0)=="h"){if(H=="r"&&(cmGetXAt(F)+C)>D){H="l"}if(H=="l"&&(cmGetXAt(F)+cmGetWidth(F)-C)<B){H="r"}return H}else{if(H=="r"&&(cmGetXAt(F,E)+cmGetWidth(F)+C)>D){H="l"}if(H=="l"&&(cmGetXAt(F,E)-C)<B){H="r"}return H}}function cmShowSubMenu(obj,isMain,subMenu,menuInfo){var prefix=menuInfo.prefix;if(!subMenu.cmParentMenu){var thisMenu=cmGetThisMenu(obj,prefix);subMenu.cmParentMenu=thisMenu;if(!thisMenu.cmSubMenu){thisMenu.cmSubMenu=new Array()}thisMenu.cmSubMenu[thisMenu.cmSubMenu.length]=subMenu}var effectInstance=subMenu.cmEffect;if(effectInstance){effectInstance.showEffect(true)}else{var orient=cmMoveSubMenu(obj,isMain,subMenu,menuInfo);subMenu.cmOrient=orient;var forceShow=false;if(subMenu.style.visibility!="visible"&&menuInfo.nodeProperties.effect){try{effectInstance=menuInfo.nodeProperties.effect.getInstance(subMenu,orient);effectInstance.showEffect(false)}catch(e){forceShow=true;subMenu.cmEffect=null}}else{forceShow=true}if(forceShow){subMenu.style.visibility="visible";/*@cc_on
				@if (@_jscript_version >= 5.5)
					if (subMenu.cmFrameObj)
						subMenu.cmFrameObj.style.display = 'block';
				@end
			@*/}}if(!_cmHideObjects){_cmHideObjects=2;try{if(window.opera){if(parseInt(navigator.appVersion)<9){_cmHideObjects=1}}}catch(e){}}if(_cmHideObjects==1){if(!subMenu.cmOverlap){subMenu.cmOverlap=new Array()}cmHideControl("IFRAME",subMenu);cmHideControl("OBJECT",subMenu)}}function cmResetMenu(D,C){if(D.cmItems){var B;var E;var A=D.cmItems;for(B=0;B<A.length;++B){if(A[B].cmIsMain){if(A[B].className==(C+"MainItemDisabled")){continue}}else{if(A[B].className==(C+"MenuItemDisabled")){continue}}if(A[B].cmIsMain){E=C+"MainItem"}else{E=C+"MenuItem"}if(A[B].className!=E){A[B].className=E}}}}function cmHideMenuTime(){_cmClicked=false;if(_cmCurrentItem){var B=_cmMenuList[_cmCurrentItem.cmMenuID];var A=B.prefix;cmHideMenu(cmGetThisMenu(_cmCurrentItem,A),null,B);_cmCurrentItem=null}}function cmHideThisMenu(thisMenu,menuInfo){var effectInstance=thisMenu.cmEffect;if(effectInstance){effectInstance.hideEffect(true)}else{thisMenu.style.visibility="hidden";thisMenu.style.top="0px";thisMenu.style.left="0px";thisMenu.cmOrient=null;/*@cc_on
			@if (@_jscript_version >= 5.5)
				if (thisMenu.cmFrameObj)
				{
					var frameObj = thisMenu.cmFrameObj;
					frameObj.style.display = 'none';
					frameObj.style.width = '1px';
					frameObj.style.height = '1px';
					thisMenu.cmFrameObj = null;
					cmFreeFrame (frameObj);
				}
			@end
		@*/}cmShowControl(thisMenu);thisMenu.cmItems=null}function cmHideMenu(E,D,C){var B=C.prefix;var F=B+"SubMenu";if(E.cmSubMenu){var A;for(A=0;A<E.cmSubMenu.length;++A){cmHideSubMenu(E.cmSubMenu[A],C)}}while(E&&E!=D){cmResetMenu(E,B);if(E.className==F){cmHideThisMenu(E,C)}else{break}E=cmGetThisMenu(E.cmParentMenu,B)}}function cmHideSubMenu(D,C){if(D.style.visibility=="hidden"){return }if(D.cmSubMenu){var A;for(A=0;A<D.cmSubMenu.length;++A){cmHideSubMenu(D.cmSubMenu[A],C)}}var B=C.prefix;cmResetMenu(D,B);cmHideThisMenu(D,C)}function cmHideControl(D,E){var J=cmGetX(E);var I=cmGetY(E);var K=E.offsetWidth;var H=E.offsetHeight;var G;for(G=0;G<document.all.tags(D).length;++G){var F=document.all.tags(D)[G];if(!F||!F.offsetParent){continue}var B=cmGetX(F);var A=cmGetY(F);var C=F.offsetWidth;var L=F.offsetHeight;if(B>(J+K)||(B+C)<J){continue}if(A>(I+H)||(A+L)<I){continue}if(F.style.visibility=="hidden"){continue}E.cmOverlap[E.cmOverlap.length]=F;F.style.visibility="hidden"}}function cmShowControl(A){if(A.cmOverlap){var B;for(B=0;B<A.cmOverlap.length;++B){A.cmOverlap[B].style.visibility=""}}A.cmOverlap=null}function cmGetThisMenu(D,C){var B=C+"SubMenu";var A=C+"Menu";while(D){if(D.className==B||D.className==A){return D}D=D.parentNode}return null}function cmTimeEffect(C,A,B){window.setTimeout('cmCallEffect("'+C+'",'+A+")",B)}function cmCallEffect(D,A){var C=cmGetObject(D);if(!C||!C.cmEffect){return }try{if(A){C.cmEffect.showEffect(false)}else{C.cmEffect.hideEffect(false)}}catch(B){}}function cmIsDefaultItem(A){if(A==_cmSplit||A[0]==_cmNoAction||A[0]==_cmNoClick){return false}return true}function cmGetObject(A){if(document.all){return document.all[A]}return document.getElementById(A)}function cmGetWidth(B){var A=B.offsetWidth;if(A>0||!cmIsTRNode(B)){return A}if(!B.firstChild){return 0}return B.lastChild.offsetLeft-B.firstChild.offsetLeft+cmGetWidth(B.lastChild)}function cmGetHeight(B){var A=B.offsetHeight;if(A>0||!cmIsTRNode(B)){return A}if(!B.firstChild){return 0}return B.firstChild.offsetHeight}function cmGetX(B){if(!B){return 0}var A=0;do{A+=B.offsetLeft;B=B.offsetParent}while(B);return A}function cmGetXAt(B,C){var A=0;while(B&&B!=C){A+=B.offsetLeft;B=B.offsetParent}if(B==C){return A}return A-cmGetX(C)}function cmGetY(A){if(!A){return 0}var B=0;do{B+=A.offsetTop;A=A.offsetParent}while(A);return B}function cmIsTRNode(B){var A=B.tagName;return A=="TR"||A=="tr"||A=="Tr"||A=="tR"}function cmGetYAt(B,D){var C=0;if(!B.offsetHeight&&cmIsTRNode(B)){var A=B.parentNode.firstChild;B=B.firstChild;C-=A.firstChild.offsetTop}while(B&&B!=D){C+=B.offsetTop;B=B.offsetParent}if(B==D){return C}return C-cmGetY(D)}function cmSetStatus(A){var B="";if(A.length>4){B=(A[4]!=null)?A[4]:(A[2]?A[2]:B)}else{if(A.length>2){B=(A[2]?A[2]:B)}}window.defaultStatus=B}function cmGetProperties(B){if(B==undefined){return"undefined"}if(B==null){return"null"}var C=B+":\n";var A;for(A in B){C+=A+" = "+B[A]+"; "}return C}