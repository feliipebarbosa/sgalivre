/**
 * 
 * Copyright (C) 2009 DATAPREV - Empresa de Tecnologia e Informações da Previdência Social - Brasil
 *
 * Este arquivo é parte do programa SGA Livre - Sistema de Gerenciamento do Atendimento - Versão Livre
 *
 * O SGA é um software livre; você pode redistribuí­-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como 
 * publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
 *
**/

/**
 * Caso de algum erro de javascript,
 * abre-se um alert com os detalhes do erro.
 *
 */
window.onerror = function(msg, url, line) {
    var error = "";
    error += "Ocorreu um erro no sistema: \n\n";
    error += "Erro  : " + msg + "\n";
    error += "URL   : " + url + "\n";
    error += "Linha : " + line + "\n\n";
    error += "Por favor contacte o seu administrador.";
    window.alert(error);
}

window.removeBodyChild = function(child) {
    document.getElementsByTagName("body")[0].removeChild(child);
}

/**
 * Defaults methods
 *
 */
window.showYesNoDialog = function(onclickok, message, title, onclickcancel) {
	var p = new Object();

    if (typeof title == "undefined") {
		title = "";
	}
    if (typeof onclickok == "undefined") {
		onclickok = "";
	}
    if (typeof onclickcancel == "undefined") {
		onclickcancel = "";
	}
    if (typeof onclickok == "undefined") {
		onclickok = "";
	}
    
	p['onclickok'] = onclickok;	
	p['message'] = message;
	p['title'] = title;
	p['onclickcancel']= onclickcancel;

	var popup = window.popup("yes_no_dialog");

    Ajax.simpleLoad("?dialog=yes_no_dialog", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, SGA.seleciona,'okbutton');
}

window.showErrorDialog = function(message, title, onclickok, onclickcancel) {
	var p = new Object();

    if (typeof title == "undefined") {
		title = "";
	}
    if (typeof onclickok == "undefined") {
		onclickok = "";
	}
    if (typeof onclickcancel == "undefined") {
		onclickcancel = "";
	}

	p['onclickok'] = onclickok;
	p['message'] = message;
	p['title'] = title;
	p['onclickcancel'] = onclickcancel;

	var popup = window.popup("error_dialog");

    Ajax.simpleLoad("?dialog=error_dialog", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, SGA.seleciona,'okbutton');
}

window.showInfoDialog = function(message, title, onclickok) {
	var p = new Object();

    if (typeof title == "undefined") {
		title = "";
	}
    if (typeof onclickok == "undefined") {
		onclickok = "";
	}
	
	p['message'] = message;
	p['title'] = title;
    p['onclickok'] = onclickok;

	var popup = window.popup("info_dialog");

    Ajax.simpleLoad("?dialog=info_dialog", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true);
}

window.closeInputDialog = function(elem, callback) {
	var value = $("#txt_input_dialog").get(0).value;
	if (value == "")
	    return
	
	window.closePopup(elem);
	callback(value);
}

/**
 * Cria uma janela popup ao centro da tela. se precisar usar mais de 
 * um popup ao mesmo tempo, id's diferentes têm que ser usados
 *
 */
window.popup = function(id, id_pai) {
	if (typeof id == "undefined") {
		id = "window_popup";
	}
	
    var popup = document.createElement("div");
    popup.setAttribute("id", id);
    $('body').get(0).appendChild(popup);
    window.makePopup(popup);

    return popup;
}

/*window.setPopup = functi*on(popup) {
    var clss = popup['class'];
    // se nao tem classe
    if (clss == null) {
        popup.setAttribute("class", "window_popup");
    }
    else {
        // gera um array com todas as classes do elemento
        clss = clss.split(" ");

        for (var i = 0; i < clss.length; i++) {
            if (clss[i] == "window_popup") {
                return;
            }
        }
        // classe window_popup nao encontrada entre as atuais
        // adiciona a classe
        clss.push("window_popup");
        
        popup.setAttribute("class", clss.join(" "));
    }
}
*/
window.makePopup = function(popup) {
    //alert("makePopup: "+popup.id);

    var jPopup = $(popup);
    
    jPopup.dialog({
        bgiframe: true,
        modal: true,
        height: 'auto',
        width: 'auto',
        close: function(ev, ui) { $(this).remove(); }
    });

    popup['sga_dialog'] = jPopup;

    window.refreshPopupTitle(popup);
}

window.refreshPopupTitle = function(popup) {
    $(popup).find('.window_popup_title').each(
        function() {
            $(popup).dialog( 'option' , 'title' , this.innerHTML );
            this.parentNode.removeChild(this);
        }
    );

    // Correção para largura da barra de titulo da dialog no IE7
    // BUG: http://dev.jqueryui.com/ticket/4437
    $(popup.parentNode).find('.ui-dialog-titlebar').each(
        function() {
            $(this).width($(popup).width());
        }
    )
}

window.getParentPopup = function(elem) {
    while (elem['sga_dialog'] == null && elem.parentNode != null) {
        elem = elem.parentNode;
    }

    if (elem['sga_dialog'] != null) {
        return elem['sga_dialog'];
    }
    return null;
}

window.closePopup = function(elem) {
    var popup = window.getParentPopup(elem);
    if (popup != null) {
        popup.dialog("close");
        //window.destroyPopup(jPopup);
    }
}

window.destroyPopup = function(jPopup) {
    jPopup.dialog('destroy').remove();
}

window.closePopupById = function(id) {
    window.closePopup($('#'+id).get(0));
}

window.removePopup = function(idPopup) {
	if (idPopup == null) {
		var div = document.getElementById("window_popup");
	}
    else {
		var div = document.getElementById(idPopup);
	}
    if (div != null) 
    	window.removeBodyChild(div);
    return true;
}

window.redir = function(url) {
	window.location = url;
}

window.createLoading = function(parent) {
    if (document.getElementById("window_loading") == null) {
    	if (parent == null) {
    		parent = document.getElementsByTagName("body")[0];
    	}
    	else {
    		parent = document.getElementById(parent);
    	}
    	
    	if (parent != null) {
	        var loading = document.createElement("div");
	        loading.setAttribute("id", "window_loading");
	        loading.innerHTML = "Carregando...";
	        parent.appendChild(loading);
    	}
    }
}
    
window.removeLoading = function() {
    var div = document.getElementById("window_loading");
    if (div != null) {
    	div.parentNode.removeChild(div);
    }
}

/**
 * SGA Object
 *
 */
var SGA = function() {
	
	var self = this;
	var date;
	
	this.refresh = function() {
    	self.updateTime();
    	setInterval(self.updateTime, 1000);
	}
	
	this.updateTime = function() {
    	
    	var dt = document.getElementById("date_time");
    	
    	if (dt != null) {
    		if (self.date == null) {
	    		self.date = new Date(dt.innerHTML * 1000);
    		}
    		
	    	var date = self.date;
	    	var hours = date.getHours();
	    	if (hours < 10) {
	    		hours = "0"+hours;
	    	}
	    	var mins = date.getMinutes();
	    	if (mins < 10) {
	    		mins = "0"+mins;
	    	}
	    	var secs = date.getSeconds();
	    	if (secs < 10) {
	    		secs = "0"+secs;
	    	}
	    	
	    	var day = date.getDate();
	    	if (day < 10) {
	    		day = "0"+day;
	    	}
	    	var mon = date.getMonth() + 1;
	    	if (mon < 10) {
	    		mon = "0"+mon;
	    	}
	    	var year = date.getFullYear();
	    	if (year < 10) {
	    		year = "0"+year;
	    	}
	    	
	    	dt.innerHTML = day+"/"+mon+"/"+year+" "+hours+":"+mins+":"+secs;
	    	
	    	// incrementa em 1 segundo
	    	date.setSeconds(date.getSeconds()+1);
    	}
    }
}

SGA.executaOperacao = function(url, method, params, callbackOk, callbackError) {
    if (typeof callbackError == "undefined") {
        callbackError = function(content) {
            window.showErrorDialog(content);
        }
    }

    var c = function(content) {
        if (content == "true" || content == "") {
        	if (callbackOk != null) {
               
            	callbackOk.call();
            }
        }
        else {
            if (callbackError != null) {
                callbackError(content);
            }
        }
    }

    Ajax.simpleRetrieve(url, method, params, c);
}

var onLoadArray = new Array();
SGA.addOnLoadListener = function(callback) {
    onLoadArray.push(callback);
}

SGA.onLoad = function() {
    for (var i = 0; i < onLoadArray.length; i++) {
        onLoadArray[i].call();
    }
    SGA.loadComponentes();
}

SGA.isInsidePopup = function(elem) {
    return window.getParentPopup(elem) != null;
}

SGA.loadComponentes = function() {
    $('.window_popup').each(function() {
        //alert("CANDIDATE: "+this.id);
        if (!SGA.isInsidePopup(this)) {
            window.makePopup(this);
        }
    });
    $(".date_field").datepicker({ dateFormat: 'dd/mm/yy' });
}

SGA.arrayContains = function(array, elem) {
    for (var i = 0; i < array.length; i++) {
        if (array[i] == elem) {
            return true;
        }
    }
    return false;
}


SGA.setUnidade = function() {
	var p = new Object();
	p['id_uni'] = document.getElementById("lista_unidades").value;
	
	Ajax.simpleLoad('?set_uni', 'geral', 'POST', Ajax.encodePostParameters(p), false);
}

/**
 * Deixa que somente números sejam inseridos no input
 * @param objeto, objeto onde deverão ser desabilitadas as teclas
 * @param evtKeyPress, evento
 * @param selecionar, id do objeto que deverá ser selecionado se o usuario aprterar ENTER(ASCII=13)
 * @author robson
 */
SGA.txtBoxSoNumeros = function(objeto, evtKeyPress){
	var nTecla = SGA.getTecla(objeto, evtKeyPress);
	if(nTecla!=8/*backspace*/ && nTecla!=0/*setas, F1-12*/ && nTecla!=13 /* enter ADICIONAR EXCEÇÕES*/){	
		if (!(nTecla >= 48 && nTecla <= 57 )){
			return false;
	    }
	}
}
/**
 * Retorna a tecla em ASCII que foi pressionada
 * @param objeto, objeto onde foi pressionada uma tecla
 * @param evtKeyPress, evento
 * @return int, numero ASCII da tecla pressionada
 * @author robson
 */
SGA.getTecla = function (objeto, evtKeyPress){
	var nTecla;
	if (document.all) { // Internet Explorer
		nTecla = evtKeyPress.keyCode;
	} else if (objeto) { // Nestcape
		nTecla = evtKeyPress.which;
	}
	return nTecla;
}
/**
 * Deixa que somente caracteres alfa numéricos sejam inseridos no input
 * @param objeto, objeto onde deverão ser desabilitadas as teclas
 * @param evtKeyPress, evento
 * @param selecionar, id do objeto que deverá ser selecionado se o usuario aprterar ENTER(ASCII=13)
 * @author robson
 */
SGA.txtBoxAlfaNumerico = function (objeto, evtKeyPress, selecionar){
	var nTecla = SGA.getTecla(objeto, evtKeyPress);
	if(nTecla == 13/*Enter*/ && selecionar != null){
		selecionar = document.getElementById(selecionar);
		selecionar.focus();
	}else if(nTecla!=8/*backspace*/ && nTecla!=0 && nTecla!=13 && nTecla!=32/*setas, F1-12*/ /*ADICIONAR EXCEÇÕES*/){	
		if (	!( (nTecla >= 48 && nTecla <= 57/*numeros*/) 
					|| (nTecla >= 97 && nTecla <= 122/*a-z*/) 
					||(nTecla >= 65 && nTecla <= 90 /*A-Z*/)
				)
			){
			return false;
	    }
	}
}
/**
 * Deixa que somente caracteres alfa numéricos sejam inseridos no input
 * @param objeto, objeto onde deverão ser desabilitadas as teclas
 * @param evtKeyPress, evento
 * @param selecionar, id do objeto que deverá ser selecionado se o usuario aprterar ENTER(ASCII=13)
 * @author robson
 */
SGA.txtBoxAlfa = function (objeto, evtKeyPress, selecionar){
	var nTecla = SGA.getTecla(objeto, evtKeyPress);
	
	if(nTecla == 13/*Enter*/ && selecionar != null){
		selecionar = document.getElementById(selecionar);
		selecionar.focus();
	}else if(nTecla!=8/*backspace*/ && nTecla!=0/*setas, F1-12*/ /*ADICIONAR EXCEÇÕES*/){	
		if (!(nTecla >= 97 && nTecla <= 122/*a-z*/ || nTecla >= 65 && nTecla <= 90 /*A-Z*/)){
			return false;
	    }
	}
}

/**
 * muda o tipo de caracteres que podem ser aceitos no input de busca
 * @param inputBusca, id do input que guarda o parametro da busca
 * @param select, HTMLSelectElement
 * @author robson
 */
SGA.onSelecionaSearchType = function (inputBusca, idSelect,selecionar){
	var input = document.getElementById(inputBusca);
	var select= document.getElementById(idSelect);
	input.value = "";
	if(select.value == 'login' || select.value == 'codigo'){
		input.onkeypress = function onkeypress(event) {
		    return SGA.txtBoxAlfaNumerico(this,event,selecionar);
		}
	}else{
		input.onkeypress = function onkeypress(event) {
		    return SGA.txtBoxAlfa(this,event,selecionar);
		}
	}
	input.select();
}

/**
 * se bool for verdadeiro, é adicionado um simbolo(default é '*') no label
 * @param label, HTMLLabelElement
 * @param bool, boolean
 * @param simbolo, simbolo a ser adicionado.
 * @author robson
 */
SGA.adverte = function (label,bool,simbolo){
    if(simbolo == null){
        simbolo = "*";
    }
    if (bool) {
        label.innerHTML = ""+simbolo;
    }
    else {
        label.innerHTML = "";
    }
}

SGA.cancelar = function(elem, idPopUp) {
    if (idPopUp == null) {
        idPopUp = "window_popup";
        window.removeBlackout();
    }
    else {
        window.removeBlackout("blackout_"+idPopUp);
    }
    
    window.removePopup(idPopUp);
}

/**
 * Remove o elemento selecionado do select passado como parametro
 * @param idSelect, id do select que será removido o elemento selecionado
 * @author robson
 */
SGA.removerElemSelecionadoSelect = function (idSelect){
	var select = document.getElementById(idSelect);
	if(select.value!=""){
		select.remove(select.selectedIndex);
	}else{
		select.focus();
	}
}
SGA.txtBoxFormat = function(objeto, sMask, evtKeyPress) {
	var i, nCount, sValue, fldLen, mskLen, bolMask, sCod, nTecla;
	if (document.all) { // Internet Explorer
		nTecla = evtKeyPress.keyCode;

	} else if (objeto) { // Nestcape
		nTecla = evtKeyPress.which;
	
	}
	if(nTecla!=8 /*ADICIONAR EXCEÇÕES*/){	

		if (!(nTecla >= 48 && nTecla <= 57 )){
	    	return false;
	    }
		sValue = objeto.value;
		// Limpa todos os caracteres de formatação que
		// já estiverem no campo.
		sValue = sValue.toString().replace("-", "");
		sValue = sValue.toString().replace("-", "");
		sValue = sValue.toString().replace(".", "");
		sValue = sValue.toString().replace(".", "");
		sValue = sValue.toString().replace("/", "");
		sValue = sValue.toString().replace("/", "");
		sValue = sValue.toString().replace(":", "");
		sValue = sValue.toString().replace(":", "");
		sValue = sValue.toString().replace("(", "");
		sValue = sValue.toString().replace("(", "");
		sValue = sValue.toString().replace(")", "");
		sValue = sValue.toString().replace(")", "");
		sValue = sValue.toString().replace(" ", "");
		sValue = sValue.toString().replace(" ", "");
		fldLen = sValue.length;
		mskLen = sMask.length;
		i = 0;
		nCount = 0;
		sCod = "";
		mskLen = fldLen;
		while (i <= mskLen) {
			bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".")
					|| (sMask.charAt(i) == "/") || (sMask.charAt(i) == ":"))
			bolMask = bolMask
					|| ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask
							.charAt(i) == " "))
			if (bolMask) {
				sCod += sMask.charAt(i);
				mskLen++;
			} else {
				sCod += sValue.charAt(nCount);
				nCount++;
			}
			i++;
		}
		objeto.value = sCod;
 	}
}

SGA.seleciona = function (id){
	var variavel = document.getElementById(id);
	if(variavel != null){
		variavel.focus();
	}
}

SGA.alterarConteudo = function(url, onLoad) {
	var func = undefined;
	if (onLoad !== undefined) {
		func = eval(onLoad);
	}
    Ajax.simpleLoad(url, 'template_conteudo', "POST", '', true, func);
}
