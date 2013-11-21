
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

var CONF_PATH = "?redir=modules/sga/configuracao/";
var CONF_GRUPOS_PATH = CONF_PATH + "grupos/";
var CONF_CARGOS_PATH = CONF_PATH + "cargos/";
var CONF_SERVICOS_PATH = CONF_PATH + "servicos/";
var CONF_UNIDADES_PATH = CONF_PATH + "unidades/";

var id_grupo_selecionado = null;
var id_cargo_selecionado = null;

var Configuracao = function() {

    var self = this;

    this.update = function() {
    }

    this.refresh = function() {
        self.update();
    }

}

/**
 * Funções de Configuração Global do Atendimento
 */

/**
 * Reinicia a senha de todas as unidades
 */
Configuracao.reiniciarSenhas = function() {
    var texto = '<p>Reiniciar a senhas irá armazenar TODAS\n\
                 as senhas atuais de TODAS unidades no histórico reiniciando o contador.</p>\n\
                 <p>Senhas que estiverem aguardando atendimento não serão chamadas e \n\
                 atendimentos em progresso serão perdidos.</p>';

    window.showYesNoDialog("Configuracao.executaReiniciarSenhas();", texto, 'Atenção');
}

/**
 * Exibe janela para confirmação de reinicialização de senhas
 */
Configuracao.executaReiniciarSenhas = function() {
    var sucesso = function() {
        window.showInfoDialog('Senhas reinicializadas com sucesso.');
    }

    SGA.executaOperacao(CONF_PATH + 'atendimento/exec_reiniciar_senhas.php', 'GET', '', sucesso);
}

/*
 *
 * Funções relacionadas ao gerenciamento de Grupos
 *
 */
//Configuracao.atualizarGroupControl = function() {
//    Ajax.simpleLoad(CONF_GRUPOS_PATH + "group_control.php", "config_groups_list", "GET", "", true);
//}
Configuracao.onLoadGrupos = function() {
    $("#lista_grupos").treeview({
		collapsed: true,
		persist: "cookie"
	});
    Configuracao.setBotoesGrupoDisabled(true);

}

/**
 *
 * @param id_grupo
 * @param nm_grupo
 */
var grupoBgAnt;
Configuracao.selectGrupo = function(id_grupo, nm_grupo) {
    var elem = document.getElementById('span_grupo_'+id_grupo);
    var elemGrupoSelecionado = document.getElementById('span_grupo_'+id_grupo_selecionado);
    if (elemGrupoSelecionado != null) {
        with (elemGrupoSelecionado.style) {
            border = 'none';
            font = 'normal 11.2px serif';
            background = grupoBgAnt;
        }
    }
    with (elem.style) {
        border = 'thin solid red';
        font = 'bold 14.5px serif';
        grupoBgAnt = background;
        background = 'url(themes/sga.default/imgs/arrow_right.png) left center no-repeat';
    }
    id_grupo_selecionado = id_grupo;
    Configuracao.setBotoesGrupoDisabled(false);
}

/**
 * Habilita/Desabilita botões de editar e remover grupo
 * @param b - boolean
 */
Configuracao.setBotoesGrupoDisabled = function(b) {
    document.getElementById('btn_editar_grupo').disabled = b;
    document.getElementById('btn_remover_grupo').disabled = b;
}

/**
 * Atualiza página de configuração dos grupos
 *
 */
Configuracao.refreshGrupo = function () {
	Ajax.simpleLoad(CONF_GRUPOS_PATH + "group_control.php", "config_groups_list", "GET", "", true, Configuracao.onLoadGrupos);
}

/**
 * Criar novo grupo
 *
 */
Configuracao.novoGrupo = function() {
    var popup = window.popup("config_add_grupo");

    var p = new Object();
    p['id_grupo_pai'] = id_grupo_selecionado;

    Ajax.simpleLoad(CONF_GRUPOS_PATH + "novo_grupo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, 'nm_grupo');
}

/**
 * Editar Grupo
 *
 */
Configuracao.editarGrupo = function() {
    var popup = window.popup("config_edit_grupo");

    var p = new Object();
    p['id_grupo'] = id_grupo_selecionado;

    Ajax.simpleLoad(CONF_GRUPOS_PATH + "editar_grupo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, 'nm_grupo');
}

/**
 *
 *
 */
Configuracao.verificaSelectRemover = function() {
    var popup = window.popup("config_check_remover_grupo");

    var p = new Object();
    p['id_grupo'] = id_grupo_selecionado;

    Ajax.simpleLoad(CONF_GRUPOS_PATH + "view_remover_grupo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, 'cancelbutton');
}

/**
 * Remove uma unidade
 */
Configuracao.removerUni = function (idUni){
	var popup = window.popup("remov_unidade");
	var p = new Object();
	p['id_uni'] = idUni;
	Ajax.simpleLoad(CONF_UNIDADES_PATH + "remover_unidade.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true);

}

/**
 * Depois de confirmação para remover grupo, vai pra classe remover_grupo para executar tal ação
 *
 */
Configuracao.removerGrupo = function() {
	var popup = window.popup("remov_grupo");
	var p = new Object();
	var form = document.getElementById("form_remov_grupo");
    p['id_grupo'] = id_grupo_selecionado;
    Ajax.simpleLoad(CONF_GRUPOS_PATH + "remover_grupo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.refreshGrupo);
    window.closePopup(form);
}

/**
 * Verificações realizadas antes de salvar um grupo, depois vai pra classe salvar_grupo.php
 * @param button
 */
Configuracao.salvarGrupo = function(confirmado) {
    if (typeof confirmado == "undefined") {
    	confirmado = 0;
    }
	var form = document.getElementById('novo_grupo_form');
    var nm_grupo = document.getElementById('nm_grupo');
	var label = document.getElementById('id_label_nm_grupo');
    if(nm_grupo.value != ""){
    	SGA.adverte(label,false);

    	var p = new Object();
	    var id_grupo_pai = document.getElementById("id_grupo_pai");
	    if (id_grupo_pai != null) {
	    	p['id_grupo_pai'] = id_grupo_pai.value;
	    }
	    p['nm_grupo'] = nm_grupo.value;
	    p['desc_grupo'] = document.getElementById("desc_grupo").value;
	    p['confirmado'] = confirmado;

	    var e = document.getElementById("id_grupo");
	    if (e != null) {
	    	p['id_grupo'] = e.value;

	    }
	    var popup = window.popup("conf_save_grup");
	    Ajax.simpleLoad(CONF_GRUPOS_PATH + "salvar_grupo.php",popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p),false, Configuracao.refreshGrupo);
    }
    else {
    	SGA.adverte(label,true);
    	nm_grupo.focus();
    }
}

/**
 *  Funções relacionadas a criação de cargos
 *
 * @param
 */
Configuracao.onLoadCargos = function() {
    $("#lista_cargos").treeview({
		collapsed: true,
		persist: "cookie"
	});
    Configuracao.setBotoesCargoDisabled(true);
}

/**
 *
 * @param id_cargo
 * @param nm_grupo
 */
var cargoBgAnt;
Configuracao.selectCargo = function(id_cargo, nm_grupo) {
    var elem = document.getElementById('span_cargo_'+id_cargo);
    var elemCargoSelecionado = document.getElementById('span_cargo_'+id_cargo_selecionado);
    if (elemCargoSelecionado != null) {
        with (elemCargoSelecionado.style) {
            border = 'none';
            font = 'normal 11.2px serif';
            background = cargoBgAnt;
        }
    }
    with (elem.style) {
        border = 'thin solid red';
        font = 'bold 14.5px serif';
        cargoBgAnt = background;
        background = 'url(themes/sga.default/imgs/arrow_right.png) left center no-repeat';
    }
    id_cargo_selecionado = id_cargo;
    Configuracao.setBotoesCargoDisabled(false);
}

/**
 * Abilita/Desabilita botões de editar e remover cargo
 * @param b - boolean
 */
Configuracao.setBotoesCargoDisabled = function(b) {
    document.getElementById('btn_editar_cargo').disabled = b;
    document.getElementById('btn_remover_cargo').disabled = b;
}

/**
 * Novo Cargo
 * @param
 */
Configuracao.novoCargo = function() {
    var popup = window.popup("config_add_cargo");

    var p = new Object();
    p['id_cargo_pai'] = id_cargo_selecionado;

    Ajax.simpleLoad(CONF_CARGOS_PATH + "novo_cargo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, "nm_cargo");
}

/**
 * Editar cargo
 *
 */
Configuracao.editarCargo = function() {
    	var popup = window.popup("config_edit_cargo");

    	var p = new Object();
	    p['id_cargo'] = id_cargo_selecionado;

	    Ajax.simpleLoad(CONF_CARGOS_PATH + "editar_cargo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, 'nm_cargo');
}

/**
 * Verificações realizadas antes de salvar um cargo, depois vai pra classe salvar_cargo.php
 *
 */
Configuracao.salvarCargo = function() {
	var form = document.getElementById('frm_view_cargo');
	var label= document.getElementById('id_label_nm_cargo');
	if (form.nm_cargo.value!=""){
		SGA.adverte(label,false);
		if (Configuracao.verificaCheckBox(form)){
			Ajax.simpleLoad(CONF_CARGOS_PATH + "salvar_cargo.php", '', "POST", Ajax.encodeFormAsPost(form), false, Configuracao.refreshCargo);
		}else{
			var div = document.getElementById("adverte_modulos");
			div.innerHTML = "Nenhum módulo selecionado - Campo obrigatório";
			div.style.color = "red";
		}
		window.closePopup(form);
	}else{
		SGA.adverte(label,true);
		form.nm_cargo.focus();
	}
}

/**
 *
 * @param form
 */
Configuracao.verificaCheckBox = function (form){
	var elem;
	var bool = false;

	for (var i = 0; i < form.elements.length; i++) {
		elem = form.elements[i];
		if (elem.checked) {
			return (bool = true);
		}
	}
}

/**
 * Questiona se quer prosseguir com remoção do cargo
 *
 */
Configuracao.removerCargo = function() {
	var sel = document.getElementById('cargos_list');

    window.showYesNoDialog('Configuracao.confirmaRemoverCargo()','Deseja realmente remover este cargo?','Remover Cargo');

}

/**
 * Método realizado depois de confirmação para remover cargo
 *
 */
Configuracao.confirmaRemoverCargo = function (){

	var p = new Object();
    p['id_cargo'] = id_cargo_selecionado;
    var popup = window.popup("config_remov_macro");

    Ajax.simpleLoad(CONF_CARGOS_PATH + "remover_cargo.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), false, Configuracao.refreshCargo);
}

/**
 * Atualiza a lista de cargos
 *
 */
Configuracao.refreshCargo = function (){
	Ajax.simpleLoad(CONF_CARGOS_PATH + "atualizar_lista.php", "ajax_select_cargos_list", "GET", "", false, Configuracao.onLoadCargos);
}

/**
 * Funções referentes a Serviços
 *
 */
Configuracao.novoMacroServico = function() {
	var popup = window.popup("config_view_macro");

    Ajax.simpleLoad(CONF_SERVICOS_PATH + "view_macro_servico.php", popup.getAttribute("id"), "GET", "", true, Configuracao.seleciona, 'nm_serv');

}

/**
 * Seleciona um objeto (botao, caixa de texto) e coloca foco
 * @param
 */
Configuracao.seleciona = function (id){
	var variavel = document.getElementById(id);
	if(variavel != null){
		variavel.focus();
		variavel.select();
	}
}

/**
 * Verificações antes de editar um macrosservico, vai para a classe view_macro_servico.php
 *
 */
Configuracao.editarMacroServico = function() {
    var select_macro = document.getElementById('macro_serv_list');
    if (select_macro.value != "") {
        var popup = window.popup("config_view_macro");

        var p = new Object();
        p['id_serv'] = document.getElementById('macro_serv_list').value;

        Ajax.simpleLoad(CONF_SERVICOS_PATH + "view_macro_servico.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, 'nm_serv');
    }
    else {
        select_macro.focus();
    }
}

/**
 * Verificações antes de criar/editar um macrosserviço, depois vai pra classe salvar_servico.php
 * @param input
 * @param array_serv
 * @param array_id
 */
Configuracao.salvarMacroServico = function(input, array_serv, array_id) {
    var id_serv = document.getElementById('id_serv');
    var nm_serv = document.getElementById('nm_serv');
    var textBox = document.getElementById('desc_serv');
    var label = document.getElementById("id_label_desc_macro");
    var label_nm_serv = $('#id_label_nm_macro').get(0);
    var old_status = document.getElementById('old_status');
    var bool = true;
    var arrayServ = array_serv.split(",");
    var arrayId   = array_id.split(",");

    if (nm_serv.value==""){
            SGA.adverte(label_nm_serv, true);
            SGA.adverte(label, false);
            nm_serv.select();
            bool = false;
    }
    else if(textBox.value == ""){
        SGA.adverte(label_nm_serv, false);
        SGA.adverte(label,true);
        nm_serv.select();
        bool = false;
    }
    else if(input == 'Criar'){
        for (i = 0; i < arrayServ.length; i++){
            if(arrayServ[i]==nm_serv.value){
                    bool= false;
            }
        }
        if (bool) {
            SGA.adverte(label_nm_serv, false);
        }
        else {
            SGA.adverte(label_nm_serv, true, 'Serviço já existe');
            nm_serv.select();
        }
    }
    else  if(input == 'Editar') {
        for(i=0; i< arrayId.length; i++){
            if (arrayServ[i]==nm_serv.value){
                if(arrayId[i] == id_serv.value) {
                    bool = true;
                }
                else {
                    SGA.adverte(label_nm_serv, true, 'Serviço já existe');
                    nm_serv.select();
                    bool= false;
                }
            }
        }
        if(bool) {
            SGA.adverte(label_nm_serv, false);
        }
        else {
            SGA.adverte(label_nm_serv, true, 'Serviço já existe');
            nm_serv.select();
        }
    }

    if (bool) {
        if (document.forms['frm_view_servico'].stat_serv.checked || old_status.value == 0) {
                Configuracao.confirmado_salvar_servico();
        }
        else {
            window.showYesNoDialog('Configuracao.confirmado_salvar_servico();', '<p>Confirma desativação de macrosserviços?</p><p>ATENÇÃO: Todos os subserviços deste macrosserviço também serão desativados.</p>');
        }
    }
}

/**
 * Confirma a ação de salver serviço
 */
Configuracao.confirmado_salvar_servico = function() {
    var form = $('#frm_view_servico').get(0);
    var callbackOk = function() {
    	window.closePopup(form);
        Configuracao.refreshServicos();
        window.showInfoDialog('O Serviço foi salvo com sucesso.');
    }
    SGA.executaOperacao(CONF_SERVICOS_PATH + "salvar_servico.php", "POST", Ajax.encodeFormAsPost(form), callbackOk);
}

/**
 * Método para questionar se prossegue com a remoção macrosservico
 * @param
 */
Configuracao.removerMacroServico = function() {
	var select_macro = document.getElementById('macro_serv_list');
	if(select_macro.value != ""){
		window.showYesNoDialog('Configuracao.confirmaRemoverMacroServico()','Deseja realmente remover este serviço?','Remover macrosserviço');
	}else{
		select_macro.focus();
	}
}

/**
 * Método realizado caso haja confirmação para remover macrosservico
 * @param
 */
Configuracao.confirmaRemoverMacroServico = function(){
	var p = new Object();
	var popup = window.popup("config_remov_macro");
	p['id_serv'] = document.getElementById('macro_serv_list').value;

    Ajax.simpleLoad(CONF_SERVICOS_PATH + "remover_servico.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), false, Configuracao.refreshServicos);
}

/**
 * Exibe a lista de subserviços do macro.
 *
 */
Configuracao.onSelecionaMacro = function() {
    var p = new Object();
    p['id_macro'] = document.getElementById('macro_serv_list').value;
    Ajax.simpleLoad(CONF_SERVICOS_PATH + "list_sub_servicos.php", 'sub_serv_content', "POST", Ajax.encodePostParameters(p), true);
}

/**
 * Verificações feitas antes de criar um subservico, depois vai pra classe view_sub_servico.php
 *
 */
Configuracao.novoSubServico = function() {
	var select_macro = document.getElementById('macro_serv_list');
	if(select_macro.value != ""){
	    var p = new Object();
	    var popup = window.popup("config_add_sub_serv");

	    p['id_macro'] = select_macro.value;
	    Ajax.simpleLoad(CONF_SERVICOS_PATH + "view_sub_servico.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true,Configuracao.seleciona, 'nm_serv');
	}else{
		select_macro.focus();
	}
}

/**
 * Verificações feitas antes de editar um subservico, depois vai pra classe view_sub_servico.php
 *
 */
Configuracao.editarSubServico = function() {
	var select_macro = document.getElementById('macro_serv_list');
	var select_sub = document.getElementById('sub_serv_list');
	if(!(select_macro.value == "" || select_sub.value== "")){
		var popup = window.popup("config_edit_sub_serv");

	    var p = new Object();
	    p['id_serv'] = document.getElementById('sub_serv_list').value;
	    p['id_macro'] = select_macro.value;
	    Ajax.simpleLoad(CONF_SERVICOS_PATH + "view_sub_servico.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.seleciona, 'nm_serv');
	}else{
		select_sub.focus();
	}
}

/**
 * Verificações feitas antes de salvar um subservico, depois vai para classe salvar_servico.php
 *
 */
Configuracao.salvarSubServico = function(macros_status,macrosIds) {
//	btn.enabled = false;
	var status = macros_status.split(',');
	var ids = macrosIds.split(',');
//	for (var i=0; i< macros.length; i++){
//		alert(macros[i]);
//	}

	var selectServ = document.getElementById('id_macro');
	var selectLabel = document.getElementById('label_macro')
	var textBoxNome = document.getElementById('nm_serv');
	var labelNome = document.getElementById("id_label_nm_sub");
	var textBoxDesc = document.getElementById('desc_serv');
	var labelDesc = document.getElementById('id_label_desc_sub');
	var old_status = document.getElementById('old_status');
	if (selectServ.value != ""){
		SGA.adverte(selectLabel,false);
		if (textBoxNome.value != ""){
			SGA.adverte(labelNome,false);
			if(textBoxDesc.value != ""){
				SGA.adverte(labelDesc,false);

				for (var i=0; i< ids.length; i++){
					if (ids[i] == selectServ.value){
						var stat_macro = status[i];
						break;
					}
				}

				var form = document.getElementById('frm_view_servico');

				//se for ativar o subservico
				if(document.forms['frm_view_servico'].stat_serv.checked){
					//verifica se o macro servico tambem esta ativado
					if(stat_macro != 0){
						Ajax.simpleLoad(CONF_SERVICOS_PATH + "salvar_servico.php", '', "POST", Ajax.encodeFormAsPost(form), false, Configuracao.refreshSubServico);
						window.closePopup(form);
					}else{
						window.showInfoDialog("Macrosserviço está desativado. Não é possível ativar este subserviço.");
					}
				}else if(stat_macro == 0){
						//se o macro servico já está desativado, então é só uma alteração
						Ajax.simpleLoad(CONF_SERVICOS_PATH + "salvar_servico.php", '', "POST", Ajax.encodeFormAsPost(form), false, Configuracao.refreshSubServico);
						window.closePopup(form);

					}else if (old_status.value == 0){
						Ajax.simpleLoad(CONF_SERVICOS_PATH + "confirma_salvar_servico.php", '', "POST", Ajax.encodeFormAsPost(form), false, Configuracao.refreshSubServico);
						window.closePopup(form);
					}else{
						// se o macro servico está ativado, então quer desativar o subservico por isso necesssita de confirmação
						window.showYesNoDialog('Configuracao.confirmado_salvar_servico();', '<p>Confirma desativação de subserviços?</p><p>ATENÇÃO: Se houver senhas na fila para este serviço as mesmas serão perdidas.</p>');
					}
			}else{
				SGA.adverte(labelDesc,true);
				textBoxDesc.select();
		//		btn.enabled = true;
			}
		}else{
			SGA.adverte(labelNome,true);
			textBoxNome.select();
		}
	}else{
		SGA.adverte(selectLabel,true);
		selectServ.focus();
	}
}

/**
 * Método para questionar se prossegue com a remoção subservico
 * @param
 */
Configuracao.removerSubServico = function() {
	var select_macro = document.getElementById('macro_serv_list');
	var select_sub = document.getElementById('sub_serv_list');
	if(!(select_macro.value == "" || select_sub.value == "")){
		window.showYesNoDialog('Configuracao.confirmaRemoverSubServico()','Deseja realmente remover este sub serviço?','Remover sub serviço');
	}else{
		select_sub.focus();
	}
}

/**
 * Método realizado caso haja confirmação para remover subservico
 * @param
 */
Configuracao.confirmaRemoverSubServico = function(){
	var p = new Object();
	p['id_serv'] = document.getElementById('sub_serv_list').value;
	var popup = window.popup("config_remov_sub");
    Ajax.simpleLoad(CONF_SERVICOS_PATH + "remover_servico.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), false, Configuracao.refreshSubServico);
}

/**
 * Atualiza lista de subserviços
 * @param
 */
Configuracao.refreshSubServico = function() {
	Configuracao.onSelecionaMacro();
}

/**
 * Atualiza lista de macroserviços
 *
 */
Configuracao.refreshServicos = function() {

	Ajax.simpleLoad(CONF_SERVICOS_PATH + "refresh_servicos.php", "serv_config", "GET", '', false);
	window.closePopup(this);
}

/**
 * Altera conteudo da página passada por parâmetro
 * @param url, onLoad
 */
Configuracao.alterarConteudo = function(url, onLoad) {
	var func = undefined;
	if (onLoad !== undefined) {
		func = eval(onLoad);
	}
    Ajax.simpleLoad(CONF_PATH + url, 'template_conteudo', "POST", '', true, func);
}

/**
 * Seleciona o texto de um input text
 * @param idTextBox
 */
Configuracao.selecionaTexto = function (idTextBox){
	var textBox;
	if(idTextBox==null){
		textBox = document.getElementById('mensagem');

	}else{
		textBox = document.getElementById(idTextBox);

	}

	if (textBox != null) {
		textBox.select();

	}
	textBox.focus();
}

/**
 * Funções referentes a unidades
 *
 */
Configuracao.criarUnidade = function() {
	
	var popup = window.popup("config_add_unidade");

	Ajax.simpleLoad(CONF_UNIDADES_PATH + "view_unidade.php", popup.getAttribute("id"), "POST", "", true,Configuracao.seleciona,'cod_uni_novo');
}


/**
 * Função do botão buscar unidade, lista todas unidades se não for passado nada por parâmetro
 * @param idInput
 * @param idselectBusca
 */
Configuracao.buscarUnidade = function (idInput,idselectBusca){
	var busca = document.getElementById(idInput).value;
	var select = document.getElementById(idselectBusca);
	var tipoBusca = select.value;

//	if(busca == ""){
//		document.getElementById(idInput).value = 'Listar todas unidades';
//	}else{
		var p = new Object();
	    p['search_input'] = busca;
	    p['search_type'] = tipoBusca;

	   Configuracao.limpaInfoUnidade();
	   window.createLoading("conteudo_resultado_unidade");
	   Ajax.simpleLoad(CONF_UNIDADES_PATH + "buscar_unidade.php", "conteudo_resultado_unidade", "POST", Ajax.encodePostParameters(p), true);
//	}
}

/**
 * Função realizada quando uma unidade é selecionada, mostra informações da mesma
 * @param elem
 */
Configuracao.onSelecionaUnidade = function(elem) {
	if (elem.value != "") {
		var div = document.getElementById('config_uni_info');
		div.style.display = 'block';

		var p = new Object();
		p['id_uni'] = elem.value;

		window.createLoading("config_uni_info");
		Ajax.simpleLoad(CONF_UNIDADES_PATH + "info_unidade.php", "config_uni_info", "POST", Ajax.encodePostParameters(p), true);
	}
	else {
		//SS == Sem Seleçao, quando nenhuma unidade foi selecionada
		Configuracao.limpaInfoUnidade(elem);
	}
}

/**
 * Limpa tag do html com as informações de uma unidade
 * @param elem
 */
Configuracao.limpaInfoUnidade = function(elem) {
	var div = document.getElementById("config_uni_info");
    if (div.value != "") {
    	div.innerHTML = "";
    }
    if(elem == null || elem.value == ""){
		div.style.display = 'none';
	}
}

/**
 * Método para dar refresh nas informações da unidade
 *
 */
Configuracao.refreshDebug = function(){
	var elem =document.getElementById("select_resultado_unidades");
		var p = new Object();
		if (elem != null){
			p['id_uni'] = elem.value;
		}else{
			var id_uni = document.getElementById("id_uni").value;
			p['id_uni'] = id_uni;
			var div = document.getElementById("edit_uni_info");
			div.innerHTML = "";
//			div.style.display = 'block';
		}
		window.createLoading("config_uni_info");
		Ajax.simpleLoad(CONF_UNIDADES_PATH + "info_unidade.php", "edit_uni_info", "POST", Ajax.encodePostParameters(p), true);
}

/**
 * Método que faz verificações necessárias antes de criar/editar uma unidade
 * @param alterar
 * @param array_uni
 * @param oldCodUni
 */
Configuracao.salvarUnidade = function(alterar, array_uni, oldCodUni) {
	if(alterar == null){
		alterar = true;
	}
	var bool = true;
	var p = new Object();
	var input;
	var codUni, nmUni, idgrupo,novo,label, codNovo;
	var labelCod,labelNm,labelGrupo;
	var aux = array_uni.split(",");
	var form = document.getElementById("nova_uni");
	//var teste = aux.split("-");
	//var codUnidades = codsUni.split(",");


	input = document.getElementById("id_uni");
	label= "id_label_"
	if(alterar){
		novo = "";
		label += "editar_";
	}else{
		novo = "_novo";
		label += "criar_";
	}
	labelCod=document.getElementById(label+"cod");
	labelNm=document.getElementById(label+"nm");
	labelGrupo=document.getElementById(label+"grupo");
	codUni = document.getElementById("cod_uni"+novo);
	nmUni = document.getElementById("nm_uni"+novo);
	idGrupo = document.getElementById("id_grupo"+novo);

	var codexiste = false;
	if(codUni.value != ""){
		if(codUni.value != oldCodUni){
			for(i=0;i<aux.length;i++){
				//quando estiver criando uma nova unidade, a mesma ainda não possui id
				if(input != null){
					if (aux[i]!= input.value){
						if (aux[i]==codUni.value ){
							Configuracao.selecionaTexto('cod_uni'+novo);
							SGA.adverte(labelCod,true,'Código já existe');
							bool = false;
							break;
						}
					}
				}
			}
		}
		if (bool){
			SGA.adverte(labelCod,false);
		}
		if(nmUni.value != "" && nmUni.value != "digite um nome"){
			SGA.adverte(labelNm,false);
//			if(idGrupo.value != ""){
//				SGA.adverte(labelGrupo,false);
//			}else{
//				idGrupo.focus();
//				SGA.adverte(labelGrupo,true);
//				bool = false;
//			}
		}else{
			if (!codexiste){
//			nmUni.value = "digite um nome";
			Configuracao.selecionaTexto('nm_uni'+novo);
			SGA.adverte(labelNm,true);
			bool = false;
			}
		}

	}else{
		Configuracao.selecionaTexto('cod_uni'+novo);
		SGA.adverte(labelCod,true);
		bool = false;
	}

	if (bool) {
		var popup = window.popup("salvar_Uni");
		p['cod_uni'] = codUni.value;
		p['nm_uni'] = nmUni.value;
		p['id_grupo'] = parseInt(idGrupo.value);

		if (alterar) {
			p['id_uni'] = input.value;
			Ajax.simpleLoad(CONF_UNIDADES_PATH + "salvar_unidade.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Configuracao.refreshDebug);
		}
        else {
        	window.closePopup(form);
			Ajax.simpleLoad(CONF_UNIDADES_PATH + "salvar_unidade.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true);
		}
	}
}


/**
 * Funções referentes a Configurar Mensagem Padrão
 *
 */
Configuracao.alteraMsg = function() {
		var msg = document.getElementById('mensagem').value;
		if(msg!="Não há mensagem global"){
			var op;
			if(document.forms['id_form_alterar'].radio_aplicar_msg_todas_unidades[0].checked){
				op = 0; // opcao para alterar a mensagem em todas as unidades
			}else{
				op = 1; // opcao para NAO alterar a mensagem em todas as unidades
			}


			var p = new Object();
			p['msg'] = msg;
			p['op'] = op;
			Ajax.simpleLoad(CONF_PATH + "triagem/altera_msg.php",'',"POST",Ajax.encodePostParameters(p), true, Configuracao.atualizaConfMsg);
			window.showYesNoDialog('Configuracao.atualizaConfMsg','Deseja realmente alterar a mensagem?','Confirmar Alteração');
		}else{
			document.getElementById('mensagem').focus();
		}
}

/**
 * Atualiza tela de Configuração de mensagem global
 *
 */
Configuracao.atualizaConfMsg = function (){
	Configuracao.alterarConteudo('triagem/index.php');
}

/**
 * Seleciona caixa de texto com mensagem exibida na triagem
 * @param msg - String com a mensagem
 */
Configuracao.padrao = function(msg) {
	if(msg != ""){
		document.getElementById('mensagem').value = msg;
		Configuracao.selecionaTexto();
	}
	else {
		document.getElementById('mensagem').value = "Não há mensagem global";
		Configuracao.selecionaTexto();
	}
}

/**
 * Selecionar ou não todas as unidades para receber a mensagem global.
 * @param radioButton
 */
Configuracao.SelecionaRadio = function (radioButton){
	if (radioButton == null){
		radioButton = document.getElementById("id_radio_nao_todas_unidades");
	}
	radioButton.checked = "checked";
//	Configuracao.onRadioCancelar(radioButton);

}

/**
 * Modifica status e modifica a cor no select da unidade, quando clicar no botão ativar/desativar
 * @param id_uni
 * @param stat_uni
 */
Configuracao.modificaStatusUni = function (id_uni,stat_uni){
	var uni = document.getElementById('select_resultado_unidades');
	var cor;
	var popup = window.popup("stat_uni");
	if(stat_uni == 1){
		stat_uni = 0;
	}else{
		stat_uni = 1;
	}

	//red = desativado
	//black = ativado
	cor = (stat_uni == 0)? 'red':'black';
	if(uni != null){
		for (var i=0;i<uni.length;i++)
	    {
			if(uni.options[i].value == id_uni){
				uni.options[i].style.color = cor;
			}
	    }
	}

	var p = new Object();
	p['id_uni'] = id_uni;
	p['stat_uni'] = stat_uni;


	Ajax.simpleLoad(CONF_UNIDADES_PATH + "alterar_status.php",popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), false, Configuracao.refreshDebug);
}