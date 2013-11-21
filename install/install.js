
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

var INSTALL_PATH = "?inst_redir=";

var DB_DATA = new Object();

var Install = function() {

    var self = this;
    
    this.refresh = function() {
    	
    }
}

Install.nextStep = function() {
	Ajax.simpleLoad(INSTALL_PATH + "next", "install_popup", "GET", "", true);
}

Install.prevStep = function() {
	Ajax.simpleLoad(INSTALL_PATH + "prev", "install_popup", "GET", "", true);
}

Install.setLicense = function() {
	var checkLicense = document.getElementById('check_license');
	
	// desativa o botao next temporariamente
	document.getElementById('btn_next').disabled = true;
	
	var p = new Object();
	p['accepted'] = (checkLicense.checked ? 'true' : 'false');
	
	Ajax.simpleLoad(INSTALL_PATH + "set_license", "install_navigation", "POST", Ajax.encodePostParameters(p), true);
}

Install.testDB = function() {
	var result = document.createElement("div");
	result.setAttribute("id", 'db_test_result');
	with (result.style) {
		visibility = 'hidden';
	}
	document.getElementsByTagName("body")[0].appendChild(result);

    var p = new Object();
    p['db_host'] = document.getElementById("db_host").value;
	p['db_port'] = document.getElementById("db_port").value;
	p['db_user'] = document.getElementById("db_user").value;
	p['db_pass'] = document.getElementById("db_pass").value;
	p['db_name'] = document.getElementById("db_name").value;

    var show = document.getElementById('db_show_result');
    show.innerHTML = "Testando...";

	Ajax.simpleLoad(INSTALL_PATH + "test_db", "db_test_result", "POST", Ajax.encodePostParameters(p), true, Install.onTestDBResult);
}

Install.onTestDBResult = function() {
	var res = document.getElementById('db_test_result');
    var show = document.getElementById('db_show_result');
	if (res.innerHTML == 'true') {
		Install.setDatabase();
		document.getElementById('btn_next').disabled = false;
        show.innerHTML = "Banco de Dados testado com sucesso!";
	}
	else {
		show.innerHTML = res.innerHTML;
	}
	Install.setDatabaseFieldsDisabled(false);
	res.parentNode.removeChild(res);
}

Install.onChangeDBData = function() {
	var btn = document.getElementById('btn_next');
	var ret = Install.checkDatabaseData();
	//alert('RET: '+ret);
	if (ret) {
		document.getElementById('btn_next').disabled = false;
	}
	else {
		document.getElementById('btn_next').disabled = true;
	}
}

Install.setDatabase = function() {
	DB_DATA['db_host'] = document.getElementById("db_host").value;
	DB_DATA['db_port'] = document.getElementById("db_port").value;
	DB_DATA['db_user'] = document.getElementById("db_user").value;
	DB_DATA['db_pass'] = document.getElementById("db_pass").value;
	DB_DATA['db_name'] = document.getElementById("db_name").value;
}

Install.carregarDadosDB = function() {
    if (typeof DB_DATA[db_host] != "undefined") {
        document.getElementById("db_host").value = DB_DATA['db_host'];
        document.getElementById("db_port").value = DB_DATA['db_port'];
        document.getElementById("db_user").value = DB_DATA['db_user'];
    	document.getElementById("db_pass").value = DB_DATA['db_pass'];
        document.getElementById("db_name").value = DB_DATA['db_name'];
    }
}

Install.setDatabaseFieldsDisabled = function(b) {
	document.getElementById("db_host").disabled = b;
	document.getElementById("db_port").disabled = b;
	document.getElementById("db_user").disabled = b;
	document.getElementById("db_pass").disabled = b;
	document.getElementById("db_name").disabled = b;
}

Install.checkDatabaseData = function() {
	if 
	((DB_DATA['db_host'] == document.getElementById("db_host").value) &&
	 (DB_DATA['db_port'] == document.getElementById("db_port").value) &&
	 (DB_DATA['db_user'] == document.getElementById("db_user").value) &&
	 (DB_DATA['db_pass'] == document.getElementById("db_pass").value) &&
	 (DB_DATA['db_name'] == document.getElementById("db_name").value))
	{
		return true;
	}
	return false;
}

Install.checkAdmin = function() {
    var form = document.getElementById("frm_usu_admin");
    window.createLoading("set_admin_result")
    Ajax.simpleRetrieve(INSTALL_PATH + "set_admin", "POST", Ajax.encodeFormAsPost(form), Install.adminResult);
}

Install.adminResult = function(content) {
    if (content == "true") {
        document.getElementById("set_admin_result").innerHTML = "OK";
        Install.nextStep();
    }
    else {
        document.getElementById("set_admin_result").innerHTML = content;
    }
}

Install.instalar = function() {
    document.getElementById('btn_install_final').disabled = true;
    document.getElementById('btn_prev').disabled = true;
    window.createLoading("display_install_loading");
    Ajax.simpleLoad(INSTALL_PATH + "do_install", "install_popup", "GET", "", true);
}