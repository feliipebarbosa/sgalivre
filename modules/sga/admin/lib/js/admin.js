
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

var ADM_PATH = "?redir=modules/sga/admin/";
var ADM_USUARIOS_PATH = ADM_PATH + "usuarios/";
var ADM_SERVICOS_PATH = ADM_PATH + "servicos/";
var ADM_MONITOR_PATH = ADM_PATH + "monitor/";

var Admin = function() {

	var self = this;

	this.update = function() {
	}

	this.refresh = function() {
		self.update();
	}

}

/**
 * Altera Conteudo da página passada por parâmetro
 * @param url
 * @param bool
 */
Admin.alterarConteudo = function(url, bool) {
	if (bool){

		Ajax.simpleLoad(ADM_PATH + url, 'template_conteudo', "POST", "", true,
			Admin.selecionaTexto);

	}else{
		Ajax.simpleLoad(ADM_PATH + url, 'template_conteudo', "POST", "", true);
	}
}

/**
 * Mensagem de confirmação para a ação de reiniciar as senhas da unidade em uso.
 */
Admin.reiniciarSenhas = function() {
    var texto = '<p>Reiniciar Senhas armazenará TODAS\n\
                 as senhas atuais da unidade no histórico, reiniciando o contador.</p>\n\
                 <p>As senhas que estiverem aguardando atendimento não serão chamadas e \n\
                 atendimentos em progresso serão perdidos.</p>';

    window.showYesNoDialog("Admin.executaReiniciarSenhas();", texto, 'ATENÇÃO');
}

/**
 * Reinicia as senhas da unidade em uso.
 */
Admin.executaReiniciarSenhas = function() {
    var sucesso = function() {
        window.showInfoDialog('Senhas reinicializadas com sucesso.');
    }

    SGA.executaOperacao(ADM_PATH + 'atendimento/exec_reiniciar_senhas.php', 'GET', '', sucesso);
}
/**
 * Método para alterar mensagem exibida na senha
 *
 */
Admin.alteraMsg = function() {
	var msg_nova = document.getElementById('mensagem').value;
	var msg_local = document.getElementById('msg_local').value;

	if(msg_nova != "Não há mensagem global"){
		window.showYesNoDialog("Admin.confirmaAlterarMsg('"+msg_nova+"')" ,'Deseja realmente alterar a mensagem?','Confirmar Alteração',"Admin.local('"+msg_local+"')");
	}else{
		document.getElementById('mensagem').focus();
	}
}

/**
 * Confirmação para alterar mensagem
 * @param String com mensagem da senha
 */
Admin.confirmaAlterarMsg = function(msg) {
	var p = new Object();

	p['msg'] = msg;

	Ajax.simpleLoad(ADM_PATH + "triagem/altera_msg.php", '', "POST", Ajax.encodePostParameters(p), true, Admin.atualizaConfMsg);

}

/**
 * Atualiza página de alterar mensagem
 *
 */
Admin.atualizaConfMsg = function() {
	Admin.alterarConteudo('triagem/index.php',true);
}

/**
 * Selecionar caixa de texto onde está a mensagem
 * @param msg - String com mensagem da senha
 */
Admin.padrao = function(msg) {
	if (msg != "") {
		document.getElementById('mensagem').value = msg;
		Admin.selecionaTexto();
	} else {
		document.getElementById('mensagem').value = "Não há mensagem global";
		Admin.selecionaTexto();
	}
}

/**
 * Selecionar caixa de texto onde está a mensagem
 * @param msg - String com mensagem da senha
 */
Admin.local = function(msg) {
	document.getElementById('mensagem').value = msg;
	Admin.selecionaTexto();
}

/**
 * Selecionar objeto (botao, caixa de texto) e colocar foco
 * @param id - id do objeto
 */
Admin.seleciona = function (id) {
	var variavel = document.getElementById(id);
	if(variavel != null){
		variavel.focus();
	}
}

/**
 * Seleciona o texto de um input text
 * @param idTextBox
 */
Admin.selecionaTexto = function(idTextBox) {
	if (idTextBox == null) {
		var textBox = document.getElementById('mensagem');
	} else {
		var textBox = document.getElementById(idTextBox);
	}
	if (textBox != null) {
		textBox.select();
	}
}

/**
 * Cria o pop-up com blackout para criar um novo servico
 *
 */
Admin.novoServico = function() {

	var p = window.popup("adm_view_servico");

	Ajax.simpleLoad(ADM_MONITOR_PATH + "novo_servico.php",  p.getAttribute("id"), "GET", "", false, Admin.seleciona, 'id_text_novo');
}

/**
 * Cria um novo serviço na unidade
 *
 * @param (Boolean) - criar, se verdadeiro cria um novo serviço senão o serviço é alterado
 */
Admin.criar = function(elem, stat_macro, siglas, editar) {
	var textNomeServ = document.getElementById('id_text_novo').value;
	var textSigla = document.getElementById('id_text_sigla').value;
	var selectServicos = $('#id_select_novo_servico').get(0);
	var selServico = selectServicos.item(selectServicos.selectedIndex).value;
	var statusServ = document.getElementById('id_checkbox_novo').checked;
	var labelNome= document.getElementById('id_label_novo_nm_serv');
	var labelSigla= document.getElementById('id_label_novo_sigla_serv');
	var criar = !$('#id_select_novo_servico').get(0).disabled;
	var bool = true;

	if (textNomeServ != "Digite o nome do novo serviço" && textNomeServ != "") {
		SGA.adverte(labelNome,false);
	} else {
		bool = false;
		SGA.adverte(labelNome,true);
		Admin.selecionaTexto('id_text_novo');
	}
	if (textSigla != "") {
		SGA.adverte(labelSigla,false);
	} else {
		SGA.adverte(labelSigla,true);
		if(bool){
			Admin.selecionaTexto('id_text_sigla');
			bool = false;
		}
	}
	if (!(selectServicos.selectedIndex >= 0) || selectServicos.value.indexOf("id_macro") >= 0){
			bool = false;
			selectServicos.focus();
	}
	if(bool){
		if (!statusServ) {
			if(stat_macro != 0){
				window.showYesNoDialog("Admin.salvarServ('"+textSigla+"', '"+textNomeServ+"', "+selServico+", "+statusServ+", "+criar+" );",
					'Ao desativar este serviço, as senhas ainda não atendidas serão perdidas.', 'ATENÇÂO');
			}else{
				Admin.salvarServ(textSigla, textNomeServ, selServico,  statusServ, criar);
			}
		}
		else {
			Admin.salvarServ(textSigla, textNomeServ, selServico,  statusServ, criar);
		}
	}
}

/**
 * Salva um novo serviço criado.
 *
 * @param textSigla - Sigla do serviço
 * @param textNomeServ - nome do serviço
 * @param selServico - Serviço global selecionado
 * @param statusServ - Status do serviço
 * @param criar - boolean para verificar se a lista com serviços está habilitada
 */
Admin.salvarServ = function(textSigla, textNomeServ, selServico,  statusServ, criar) {
	var parametros = new Object();
	/**
	 * é necessário o .toUpperCase() porque a propriedade do CSS não
	 * modifica realmente o .value do input text
	 */
	parametros['id_text_sigla'] = textSigla.toUpperCase();
	parametros['id_text_novo'] = textNomeServ;
	parametros['novo_servico'] = selServico;
	parametros['status_serv'] = statusServ;
	parametros['criar'] = criar;

	var sucesso = function() {
        window.closePopupById('adm_view_servico');
        Admin.alterarConteudo('monitor/gerenciar_servicos.php', false);
    }

    SGA.executaOperacao(ADM_MONITOR_PATH + "criar_novo_servico.php", "POST", Ajax.encodePostParameters(parametros), sucesso);

}


/**
 * Remove um determinado serviço da unidade atual
 *
 * @param idServ
 */
Admin.removerServicoUni = function(idServ) {
	var popup = window.popup("exclui_serv")
	var parametros = new Object();
	parametros['id_serv']=idServ;
	Ajax.simpleLoad(ADM_MONITOR_PATH + "remover_servico_uni.php",  popup.getAttribute("id"), "POST", Ajax.encodePostParameters(parametros), false,Admin.alterarConteudo, 'monitor/gerenciar_servicos.php');
}

/**
 * Mostra a tela de novo serviço adaptada para alterar serviço
 *
 * @param id
 * @param nome
 * @param sigla
 * @param status
 */
Admin.alterarServ = function(id, nome, sigla, status) {
	var parametros = Object();
	parametros['id_serv'] = parseInt(id);
	parametros['nome_serv'] = nome;
	parametros['sigla_serv'] = sigla;
	parametros['status_serv'] = status;

	var p = window.popup("adm_view_servico");

	Ajax.simpleLoad(ADM_MONITOR_PATH + "novo_servico.php",  p.getAttribute("id"), "POST",Ajax.encodePostParameters(parametros), false, Admin.seleciona, 'id_text_novo');
}

/**
 * Desmembra os subserviços do macro
 */
Admin.especializarServ = function() {
    var select = document.getElementById('id_select_novo_servico');

    var btn_especializar =  document.getElementById('btn_especializarServ').getAttribute("name") ;
    if (select != null) {
                    if (btn_especializar == "Especializar subserviço")
                            Ajax.simpleLoad(ADM_MONITOR_PATH + "sub_servicos.php", "servicos_uni",
                                            "POST", '', false);
                    else{
                            Ajax.simpleLoad(ADM_MONITOR_PATH + "servicos_macro.php", 'servicos_uni', "POST", '', false);
                    }
    }
    var btnEspecializarServ = $('#btn_especializarServ');

    if (btn_especializar == "Especializar subserviço") {

        btnEspecializarServ.hide();
        btnEspecializarServ.val("Cancelar");
        btnEspecializarServ.attr("name", "Cancelar");

        $("#cancelarNovoServ").get(0).onclick = Admin.especializarServ;
    }
    else{
        btnEspecializarServ.show();
        btnEspecializarServ.attr("value", "Especializar subserviço");
        btnEspecializarServ.attr("name", "Especializar subserviço");

        var func = function() {
            window.closePopupById('id_select_novo_servico');
        }

        $("#cancelarNovoServ").get(0).onclick = func;
    }
}

/**
 *
 */
Admin.preencheInput = function (){
	var select = document.getElementById('id_select_novo_servico');
	if(select.value.indexOf("id_macro") < 0){
		document.getElementById("id_text_novo").value = select.options[select.selectedIndex].text;
		document.getElementById("id_text_novo").select();
	}
}

/**
 * Alterar status da impressão
 */
Admin.alteraImp = function() {
	var p = new Object();
	var status;
	if (document.getElementById('bt_sim').checked) {
		status = 1; // status 1 impressão ativada
	} else {
		status = 0; // status 0 impressão não está ativada
	}

	p['status_imp'] = status;
	Ajax.simpleLoad(ADM_PATH + "triagem/altera_imp.php", '', "POST", Ajax
			.encodePostParameters(p), false, Admin.atualizaConfMsg);
}