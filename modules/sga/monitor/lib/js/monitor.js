
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

var MONITOR_PATH = "?redir=modules/sga/monitor/";

var Monitor = function() {

	var self = this;

	this.update = function() {
		var t1 = new Target("ult_senha");
		var t3 = new Target("fila_servicos");
		var t4 = new Target("total_senhas");

		var ajaxList = new AjaxList();
		ajaxList.add(new Ajax(MONITOR_PATH + "ult_senha.php", t1));
		ajaxList.add(new Ajax(MONITOR_PATH + "fila_servicos.php",t3));
		ajaxList.add(new Ajax(MONITOR_PATH + "total_senhas.php", t4));

		ajaxList.loadURLs();
	}

	this.refresh = function() {
		self.update();
		setInterval(self.update, 3000);
        Monitor.checkPageControls(0);
	}

}

/**
 * 
 * @param op
 */
Monitor.checkPageControls = function(op) {
	var select = document.getElementById("goto_page");
	var total = select.options.length;

	var nindex = select.options.selectedIndex + op;
	if (nindex >= 0 && nindex < total)
		select.options.selectedIndex = nindex;

	var index = select.options.selectedIndex;

	document.getElementById("btn_anterior").disabled = false;
	document.getElementById("btn_proximo").disabled = false;

	if (index <= 0)
		document.getElementById("btn_anterior").disabled = "disable";
	if (index >= total - 1)
		document.getElementById("btn_proximo").disabled = "disable";

}

/**
 * 
 * @param op
 */
Monitor.changePage = function(op) {
	Monitor.checkPageControls(op);
	Ajax.simpleLoad(MONITOR_PATH + "change_page.php?page=" + op, "", "GET", "",
			false);
	new Monitor().update();
}

/**
 * Seleciona as senhas do serviço
 * 
 */
Monitor.onServicoSelecionado = function() {
	var select = document.getElementById("servico_cancela_senha");
	if (select != null) {
		var id_servico = select.value;
		var parametro = new Object();
		parametro['id_servico'] = id_servico;
		Ajax.simpleLoad(MONITOR_PATH + "senhas_servico.php", "senhas_servico",
				"POST", Ajax.encodePostParameters(parametro), false);
	}
}

/**
 * Seleciona as senhas canceladas do serviço
 * 
 */
Monitor.onServicoSelecionadoReativar = function() {
	var select = document.getElementById("servico_reativa_senha");
	
	if (select != null) {
		var id_servico = select.value;
		var parametro = new Object();
		parametro['id_servico'] = id_servico;
		Ajax.simpleLoad(MONITOR_PATH + "senhas_canceladas_servico.php", 
				"senhas_servico", "POST", Ajax.encodePostParameters(parametro),
				false, Monitor.onPrioridadeSenha);
	}
}

/**
 * Mostra prioridade que a senha já possui antes de ser cancelada
 */
Monitor.onPrioridadeSenha = function() {
	var select_atend = document.getElementById("id_cancelar_senhas");
	
	var id_atend = select_atend.value;
	
	var parametro = new Object();
	parametro['id_atend'] = id_atend;
	
	Ajax.simpleLoad(MONITOR_PATH + "prioridade_senha.php", "senhas_prioridade",
			"POST", Ajax.encodePostParameters(parametro), false);

}

/**
 * Cria o pop-up com blackout para cancelar senha Método igual no módulo da
 * Monitor, havendo mudanças neste módulo mudar na triagem também
 * 
 */
Monitor.cancelarSenha = function() {

	var p = window.popup("mon_cancelar_senha");

	Ajax.simpleLoad(MONITOR_PATH + "cancelar_senha.php", p.getAttribute("id"), "GET", "", true, Monitor.selecionaRadioButton);
}

/**
 * Seleciona uma opção de cancelar senha e coloca foco no botão de confirmar
 * @param radioButton
 */
Monitor.selecionaRadioButton = function(radioButton) {
	if (radioButton == null) {
		radioButton = document.getElementById("id_radio_servico");
	}
	radioButton.checked = "checked";
	Monitor.onRadioCancelar(radioButton);
	
	var input = document.getElementById('confirmar_cancelar_senha');
	input.focus();
}

/**
 * Cria o pop-up com blackout para gerenciar servicos
 * 
 */
Monitor.gerenciarServicos = function() {
	var p = window.popup("mon_gerenciar_servicos");

	Ajax.simpleLoad(MONITOR_PATH + "gerenciar_servicos.php", p.getAttribute("id"), "GET", "", false);
}

/**
 * Cria o pop-up com blackout para reativar senha Método igual no módulo da
 * triagem, havendo mudanças neste módulo mudar na Monitor também
 * 
 */
Monitor.reativarSenha = function() {

	var p = window.popup("mon_reativar_senha");

	Ajax.simpleLoad(MONITOR_PATH + "reativar_senha.php", p.getAttribute("id"), "GET", "", false, Monitor.seleciona, 'confirmar_reativar_senha');

}

/**
 * Seleciona objeto e coloca foco
 */
Monitor.seleciona = function(id){
	var variavel = document.getElementById(id);
	if(variavel != null){
		variavel.focus();
	}
}

/**
 * Função para confirmar o cancelamento da senha Método igual no módulo da
 * Monitor, havendo mudanças neste módulo mudar na Monitor também
 * 
 */
Monitor.confirmaCancelarSenha = function(elem) {
	var radio_serv = document.getElementById("id_radio_servico");
	var radio_senha = document.getElementById("id_radio_senha");
	var label_select = document.getElementById("id_radio_senha");
	
	
	if (radio_serv.checked == true) {
		var label_servico = document.getElementById("id_label_cancelar_por_servico");
		var label_senha = document.getElementById("id_label_cancelar");
		var select = document.getElementById("id_cancelar_senhas");
		var opcao = document.getElementById("servico_cancela_senha");
		if (opcao.value != -1 && select.value != "") {
			var popup = window.popup("cancel_senha");
			var id_atendimento = select.value;
			if (id_atendimento != "") {
				var parametro = new Object();
				parametro['id_atendimento'] = id_atendimento;
				Ajax.simpleLoad(MONITOR_PATH
				+ "confirma_cancelar_senha.php", popup.getAttribute("id"), "POST",
				Ajax.encodePostParameters(parametro), false);
				window.closePopup(elem);
			}
		}		
        else if(opcao.value == -1){
        	SGA.adverte(label_servico, true);
			
        }else {
        	SGA.adverte(label_senha, true);
        	if(opcao.value != -1){
        		SGA.adverte(label_servico, false);
        	}
		}
	}
    else if (radio_senha.checked == true) {
		var label_cancelar = document.getElementById("label_cancelar_senha");
		var input = document.getElementById("id_id_atendimento");
        
		if (input != null) {
			var popup = window.popup("cancel_senha");
			var id_atendimento = input.value;
			var parametro = new Object();
			parametro['id_atendimento'] = id_atendimento;
			Ajax.simpleLoad(MONITOR_PATH + "confirma_cancelar_senha.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(parametro), false);
			window.closePopup(elem);
		}
        else {
			if (document.getElementById("id_text_senha").value == "Digite uma senha") {
				Monitor.selecionaTexto();
			}
            else {
				document.getElementById("id_button_procurar").focus();
				SGA.adverte(label_cancelar, true);
			}

		}
	}
	
}

/**
 * Função para confirmar o reativamento da senha Método igual no módulo da
 * Monitor, havendo mudanças neste módulo mudar na Monitor também
 * 
 */
Monitor.confirmaReativarSenha = function(elem) {
	var select = document.getElementById("id_cancelar_senhas");
	var select_prio = document.getElementById("list_prio");
	var label_senhas = document.getElementById("id_label_senha_servico");
	var label_prioridades = document.getElementById("id_label_prioridade_servico");
	if (select != null) {
		var id_atendimento = select.value;
		if (id_atendimento != "") {
			SGA.adverte(label_senhas, false);
			if (select_prio.value!=""){
				SGA.adverte(label_prioridades, false);
				var id_prio = select_prio.value;
				
				var parametro = new Object();
				parametro['id_atendimento'] = id_atendimento;
				parametro['id_prio'] = id_prio;
				var popup = window.popup("confirm_reativar");
                Ajax.simpleLoad(MONITOR_PATH + "confirma_reativar_senha.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(parametro), false);
				window.closePopup(elem);
			}else{
				select_prio.focus();
				SGA.adverte(label_prioridades, true);
			}
		}else{
			select.focus();
			SGA.adverte(label_senhas, true);
		}
	}
}

/**
 * 
 * @param select
 */
Monitor.gotoPage = function(select) {
	Monitor.checkPageControls(0);
	page = select.options[select.options.selectedIndex].value;
	Ajax.simpleLoad(MONITOR_PATH + "change_page.php?goto_page=" + page, "",
			"GET", "", false);
	new Monitor().update();
}

/**
 * 
 * @param page
 */
Monitor.showOption = function(page) {
	var popup = window.popup("mon_show_option");
	
	Ajax.simpleLoad(MONITOR_PATH + "show_option.php?page=" + page, popup.getAttribute("id"), "GET", "", true);
}

/**
 * Coleta parametros necessarios para transferir senha
 * @param senha
 * @param servico
 * @param prioridade
 */
Monitor.transfereSenha = function(id_atend, senha, servico, prioridade) {
	var popup = window.popup("mon_transfere_senha");

	Ajax.simpleLoad(MONITOR_PATH + "transferir.php?id_atend="+id_atend+"&senha=" + senha
			+ "&servico=" + servico + "&prioridade=" + prioridade, popup
			.getAttribute("id"), "GET", "", true);
}

/**
 * Efetua transferência após confirmação
 * @param button
 */
Monitor.transferir = function(button) {
	if (document.getElementById("novo_servico").value != "") {
		button.disabled = true;
		var form = $('#transfere_form').get(0);

		var callbackOk = function() {
			window.closePopup(button);
		}
		SGA.executaOperacao(MONITOR_PATH + "transfere.php", "POST", Ajax.encodeFormAsPost(form), callbackOk);
	}

}

/**
 * Metodo para tratar a escolha do usuário para cancelar a senha, por serviço ou
 * senha.
 * @param radio
 */
Monitor.onRadioCancelar = function(radio) {
	if (radio.id == "id_radio_servico") {
		Ajax.simpleLoad(MONITOR_PATH + "cancelar_senha_por_servico.php",
				"id_cancelar_senha", "GET", "", true);
	} else if (radio.id == "id_radio_senha") {
		Ajax.simpleLoad(MONITOR_PATH + "cancelar_senha_por_senha.php",
				"id_cancelar_senha", "GET", "", false, SGA.seleciona, 'id_text_senha');
	}

}

/**
 * Seleciona o texto de um input text. Por default seleciona o "id_text_senha".
 * 
 * @param input - type="text"
 */
Monitor.selecionaTexto = function(textBox) {
	if (textBox == null) {
		textBox = document.getElementById("id_text_senha");
		
	}
	textBox.focus();
	textBox.select();
}

/**
 * Seleciona caixa de texto
 */
Monitor.selecionaTextBox = function(textBox){
	textBox.select();
}

/**
 * Metodo para procurar uma senha, que o usuário digitou, e mostrar na tela as
 * informações da mesma
 * 
 */
Monitor.procuraSenha = function() {
	var num_senha = document.getElementById("id_text_senha").value;
	var label_senha = document.getElementById("label_cancelar_senha");
	
	if (num_senha == "") {
		SGA.seleciona("id_text_senha");
		SGA.adverte(label_senha,true);
	} else {
		SGA.adverte(label_senha,false);
		var parametro = new Object();
		parametro['num_senha'] = Number(num_senha);
		Ajax.simpleLoad(MONITOR_PATH + "procura_senha.php", "id_label_senha","POST", Ajax.encodePostParameters(parametro), true);
	}

}

/**
 * Verificações antes de consultar senha por período
 */
Monitor.procuraConsultarSenha = function() {

	var num_senha = document.getElementById("id_text_senha").value;
	var checkbox = document.getElementById("id_checkbox_periodo");
	var label_senha = document.getElementById("label_cancelar_senha");
	
	if (num_senha == "" ) {
		SGA.seleciona("id_text_senha");
		SGA.adverte(label_senha,true);
	} else {
		SGA.adverte(label_senha,false);
		var i = document.getElementById("id_data_comeco").value;
		var j = document.getElementById("id_data_fim").value;
		var p = new Object();
		p['i'] = i;
		p['j'] = j;
		p['num_senha'] = num_senha;
		var teste = document.getElementById('id_label_senha');
		Ajax.simpleLoad(MONITOR_PATH + "procura_senha_periodo.php",
				"id_label_senha", "POST", Ajax.encodePostParameters(p), true);
	}

}

/**
 * Mostra o popup com blackout para consultar as senhas
 * 
 */
Monitor.consultarSenhas = function() {
	var p = window.popup("mon_consultar_senhas");

	Ajax.simpleLoad(MONITOR_PATH + "consultar_senhas.php", p.getAttribute("id"), "GET", "", false, Monitor.selecionaTexto);
}
