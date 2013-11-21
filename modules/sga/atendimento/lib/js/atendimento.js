
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

var ATEND_PATH = "?redir=modules/sga/atendimento/";

var TEXTO_CHAMAR_PROXIMO = null;
var DELAY_CHAMAR_PROXIMO = 5; // segundos

var valorDelayChamada = 0;

var Atendimento = function() {

    var self = this;
    var date;

    this.update = function() {

        var ajax = new Ajax(ATEND_PATH + "atend_fila.php",new Target("fila"));

        var ajaxList = new AjaxList();
        ajaxList.add(ajax);

        ajaxList.loadURLs();
    }

    this.refresh = function() {
        self.update();
        setInterval(self.update, 3000);
    }
}

/**
 * Chama classe para fazer validação do guichê
 * @param value - valor do guichê
 */
Atendimento.setGuiche = function(value) {
	Ajax.simpleLoad(ATEND_PATH + "set_guiche.php?guiche=" + value, "geral", "GET", "", true);
}

/**
 * Seleciona objeto (botao, caixa de texto) e coloca foco
 *
 */
Atendimento.seleciona = function (input){
	if(input != null){
		input.select();
		input.focus();
	}
}

/**
 * Muda página
 *
 */
Atendimento.changePage = function(page) {
    window.createLoading("conteudo");

    var t1 = new Target("");
    var t2 = new Target("conteudo");

    var ajaxList = new AjaxList();
    ajaxList.add(new Ajax(ATEND_PATH + "change_page.php?page=" + page,t1));
    ajaxList.add(new Ajax(ATEND_PATH + "content_loader.php", t2));

    ajaxList.loadURLs();
}

/**
 * Função do botão chamar próximo, ir para classe proximo.php
 * @param button
 */
Atendimento.proximo = function(button) {

	valorDelayChamada = DELAY_CHAMAR_PROXIMO;
	button.disabled = true;
    Ajax.simpleLoad(ATEND_PATH + "atender/proximo.php", "conteudo", "GET", "", true, Atendimento.delayChamarProximo);

}

/**
 * Delay antes de apertar o botao de chamar o próximo de novo
 *
 */
Atendimento.delayChamarProximo = function() {

	valorDelayChamada--;
	var btnChama = document.getElementById("btn_chamar_proximo");
	if (btnChama != null) {
		btnChama.disabled = true;

		if (TEXTO_CHAMAR_PROXIMO == null) {
			TEXTO_CHAMAR_PROXIMO = btnChama.innerHTML.replace('</div>', '</DIV>');
		}
		if (valorDelayChamada >= 0) {
			setTimeout(Atendimento.delayChamarProximo, 1000);
			btnChama.innerHTML = TEXTO_CHAMAR_PROXIMO.replace('</DIV>', '('+(valorDelayChamada + 1)+')</DIV>');
		}
		else {
			btnChama.innerHTML = TEXTO_CHAMAR_PROXIMO+'</div>';
			btnChama.disabled = false;
		}
	}
}

/**
 * Função do botão iniciar atendimento, ir para classe iniciar.php
 *
 */
Atendimento.iniciar = function(button) {
    button.disabled = true;
    Ajax.simpleLoad(ATEND_PATH + "atender/iniciar.php", "conteudo", "GET", "", true);

}

/**
 * Função do botão encerrar atendimento, ir para classe encerrar.php
 *
 */
Atendimento.encerrar = function(button, exibirRedirecionar) {
    button.disabled = true;

    Ajax.simpleLoad(ATEND_PATH + "atender/encerrar.php?redirecionar="+exibirRedirecionar, "conteudo", "GET", "", true);
}

Atendimento.toggleRedirecionar = function() {
    var check_redir = $("#id_check_redirecionar").get(0);
    var span_redir = $("#id_span_redirecionar").get(0);

    if (check_redir.checked) {
        span_redir.className = "";
    }
    else {
        span_redir.className = "invisible";
    }
}

Atendimento.naoCompareceu = function(button) {
    window.showYesNoDialog('Atendimento.ConfirmacaoCompareceu()','Deseja realmente cancelar o atendimento?','Não Compareceu');
}

/**
 * Função do botão cliente não compareceu, ir para classe nao_compareceu.php
 *
 */
Atendimento.ConfirmacaoCompareceu = function() {
    Ajax.simpleLoad(ATEND_PATH + "atender/nao_compareceu.php", "conteudo", "GET", "", false, Atendimento.atendimentoEncerrado);
}

/**
 * Função do botão para o erro de triagem, ir para classe erro_triagem.php
 *
 */
Atendimento.erroTriagem = function(button) {
	button.disabled = true;

    Ajax.simpleLoad(ATEND_PATH + "atender/erro_triagem.php", "conteudo", "GET", "", true);
}

/**
 * Confirmar erro de triagem, ir para classe confirma_erro_triagem.php
 *
 */
Atendimento.confirmaErroTriagem = function (){
	var select =  document.getElementById("servico_erro_triagem");

	if(select.value != '') {
		var id_servico = select.value;
		var parametro = new Object();
		parametro['id_servico'] = id_servico;
		Ajax.simpleLoad(ATEND_PATH + "confirma_erro_triagem.php",  "conteudo", "POST", Ajax.encodePostParameters(parametro), false);
	}

}

/**
 * Cancelar erro de triagem, ir para classe cancela_erro_triagem.php
 * @param
 */
Atendimento.cancelarErroTriagem = function(){

    Ajax.simpleLoad(ATEND_PATH + "cancela_erro_triagem.php",  "conteudo", "POST", "", false);

}


/**
 * Elimina a duplicidade de elementos entre os serviços já selecionados
 * e os serviços selecionáveis
 *
 */
Atendimento.eliminaDuplicidade = function() {
	var servicos_selecionados = document.getElementById("list_servico_atendido[]");
	var sub_servicos_selecionaveis = document.getElementById("list_sub_servico");

	if(servicos_selecionados.length>0){
		for(var j=0;j< servicos_selecionados.length;j++){
			for(var i=0;i< sub_servicos_selecionaveis.length;i++){
				servicos_selecionados = document.getElementById("list_servico_atendido[]");
				sub_servicos_selecionaveis = document.getElementById("list_sub_servico");

				if (servicos_selecionados.item(j).value == sub_servicos_selecionaveis.item(i).value){
					sub_servicos_selecionaveis.remove(i);
				}
			}
		}
	}
}

/**
 * Seleciona os sub serviços do serviço mestre
 *
 */
Atendimento.onServicoSelecionado = function() {
	var select =  document.getElementById("list_servico_mestre");
	if(select != null){
		var id_servico = select.value;
		var parametro = new Object();
		parametro['id_servico'] = id_servico;

		Ajax.simpleLoad(ATEND_PATH + "sub_servico.php",  "id_sub_servico", "POST", Ajax.encodePostParameters(parametro), false, Atendimento.eliminaDuplicidade);

	}
}

/**
 * Adiciona no jump_menu o serviço que foi atendido
 *
 */
Atendimento.adicionaServicoAtendido = function() {
	var select =  document.getElementById("list_sub_servico");
	//var destino = document.getElementById("list_servico_atendido");
	var htmlSelect = document.getElementById("list_servico_atendido[]");

	var idx = select.selectedIndex;
        if(idx>=0)
            var option = select.item(idx);
	if (select != null && idx >= 0 && select[0].text != "Selecione um serviço") {

		if(htmlSelect[0] != null && htmlSelect[0].text == "Selecione um subserviço"){
			htmlSelect[0] = null;
		}
		option.ondblclick = Atendimento.removeItem;

                var item = select.item(idx);
                select.options[idx] = null;
		htmlSelect.options[htmlSelect.options.length] = new Option(item.text, item.value);
		select.focus();


	}
	else{
		if(select[0]==null){
			var aux = document.createElement('option');
			aux.text = "Selecione um serviço";
                        select.options[select.options.length] = aux;

		}else {
			select.focus();

		}

	}
}

/**
 * Remove o item selecionado do jump menu
 *
 */
Atendimento.removeItem = function() {
	var htmlSelect = document.getElementById("list_servico_atendido[]");
	if(htmlSelect.options.length>0  && htmlSelect[0].text != "Selecione um subserviço"){
		htmlSelect.remove(htmlSelect.selectedIndex);
		Atendimento.onServicoSelecionado();

	}else{
		if (htmlSelect[0] == null){
			var aux = document.createElement('option');
			aux.text = "Selecione um subserviço";
			try{
                            htmlSelect.add(aux, null);
                        }catch(ex){
                            htmlSelect.add(aux);
                        }
		}else{
			htmlSelect.focus();
		}
	}
}

/**
 * Confirma o encerramento do atendimento e adiciona os
 * itens para as estatísticas
 * Se for o caso tambem redireciona o atendimento
 */
Atendimento.confirmaEncerra = function() {
    var select_servs =  document.getElementById("list_servico_atendido[]");
    var check_serv_redir = $("#id_check_redirecionar").get(0);
    var select_serv_redir = $("#servico_erro_triagem").get(0);
    var form = $("#id_form_encerra_atendimento").get(0);

    if (select_servs.length > 0 && select_servs[0].text != "Selecione um subserviço") {
        // se está redirecionando
        if (check_serv_redir.checked && select_serv_redir.selectedIndex <= 0) {
            select_serv_redir.focus();
        }
        else {
            var servicos_atendidos = new Array();
            for (var i = 0; i < select_servs.length; i++) {
                servicos_atendidos.push(select_servs.item(i).value);
            }

            var p = new Object();

            p['list_servico_atendido[]'] = servicos_atendidos;
            p['check_redirecionar'] = check_serv_redir.checked;
            p['servico_erro_triagem'] = select_serv_redir.value;

            Ajax.simpleLoad(ATEND_PATH + "confirma_encerrar_atend.php", "conteudo", "POST", Ajax.encodePostParameters(p), true, Atendimento.atendimentoEncerrado);
        }
    }
    else {
        select_servs.focus();
    }
}