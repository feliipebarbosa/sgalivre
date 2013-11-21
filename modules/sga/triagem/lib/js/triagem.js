
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

var TRIAG_PATH = "?redir=modules/sga/triagem/";

var Triagem = function() {

    var self = this;
    
    this.update = function() {
        var t1 = new Target("conteudo_triagem");
    	
        var ajaxList = new AjaxList();
        ajaxList.add(new Ajax(TRIAG_PATH + "atualiza_conteudo_triagem.php", t1));
        ajaxList.loadURLs();
    }
    
    this.refresh = function() {
        self.update();
        setInterval(self.update, 3000);
    }

}

Triagem.changePage = function(page) {
    /*
    Ajax.simpleLoad(TRIAG_PATH + "change_page.php?page=" + page, "", "GET", "", false);
    Ajax.simpleLoad(TRIAG_PATH + "content_loader.php", "conteudo", "GET", "", true);
    */
 	
    window.createLoading();
    
    var t1 = new Target("");
    var t2 = new Target("conteudo");
    
    var ajaxList = new AjaxList();
    ajaxList.add(new Ajax(TRIAG_PATH + "change_page.php?page=" + page,t1));
    ajaxList.add(new Ajax(TRIAG_PATH + "content_loader.php", t2));
    
    ajaxList.loadURLs();
}
/*
Triagem.distribuir = function(button,select_serv,select_prio,id_serv,id_prio) {   
    window.blackout();
   	button.blur();
   	
   	var select1 = document.getElementById(select_serv);
	var select2 = document.getElementById(select_prio);
	
	document.getElementById(id_serv).value = select1.options[select1.options.selectedIndex].value;
	document.getElementById(id_prio).value = select2.options[select2.options.selectedIndex].value;
   	
    param = Triagem.getTriagemParametros();
    Ajax.simpleLoad(TRIAG_PATH + "acoes/distribuir_senha.php?" + param, "num_senha", "GET", "", true);
    document.getElementById("client_name").value = "";
    document.getElementById("client_ident").value = "";
}
*/

/**
 * Coleta todas as informções necessárias para emitir a senha (servico, prioridade, nome_cliente, id_cliente)
 * e depois envia para a classe distribuir_senha para gravar os dados no banco de dados.
 * @param id_servico
 * @param id_prio
 * @param status_imp
 */
Triagem.distribuir = function(id_servico, id_prio, status_imp) {
   	
    var p = new Object();
    
    p['id_servico'] = id_servico;
    p['id_prio'] = id_prio;
    p['client_name'] = document.getElementById("client_name").value;
    p['client_ident'] = document.getElementById("client_ident").value;

    var callbackOk = function() {

        //1 se impressão está ativa
        if (status_imp == 1) {

            var testwindow = window.open(TRIAG_PATH + "acoes/imprimir_senha.php", "mywindow", "location=0,status=0,scrollbars=0,width=300,height=170");
            testwindow.moveTo(0,0);
        }
    
        location.href = '?mod=sga.triagem';
    }

    SGA.executaOperacao(TRIAG_PATH + "acoes/distribuir_senha.php", "POST", Ajax.encodePostParameters(p), callbackOk)

   
}
/**
 * 
 * @param select
 * 
 */

//Triagem.exibeSubServico = function(select){
//	var id = select.options[select.options.selectedIndex].value;
//	for(i=0;i<select.length;i++){
//		var pos = select.options[i].value;
//		var tmp = document.getElementById("ul"+pos);
//		if(tmp!=null)
//			document.getElementById("ul"+pos).style.display="none";
//	}
//	var tmp = document.getElementById("ul"+id);
//	if(tmp!=null)
//		document.getElementById("ul"+id).style.display="block";
//}
//Triagem.showOption = function (page){
//    var popup = window.popup("tgm_show_option");
//    
//    Ajax.simpleLoad(TRIAG_PATH + "show_option.php?page=" + page, popup.getAttribute("id"), "GET", "", true);
//}

/**
 * cria o pop-up com blackout para cancelar senha
 * Método igual no módulo de monitor, havendo mudanças neste módulo mudar no monitor também
 * @param none
 * @return none
 */
Triagem.cancelarSenha = function() {
    var p = window.popup("tgm_cancelar_senha");
	
    Ajax.simpleLoad(TRIAG_PATH + "acoes/cancelar_senha.php",  p.getAttribute("id"), "GET", "", false, Triagem.selecionaRadioButton);
}

/**
 * cria o pop-up com blackout para reativar senha
 * Método igual no módulo de monitor, havendo mudanças neste módulo mudar no monitor também
 * @param none
 * @return none
 */
Triagem.reativarSenha = function() {
	
    var p = window.popup("tgm_reativar_senha");
	
    Ajax.simpleLoad(TRIAG_PATH + "acoes/reativar_senha.php",  p.getAttribute("id"), "GET", "", false, Triagem.seleciona, 'confirmar_reativar_senha');
}

Triagem.seleciona = function(id){
    var variavel = document.getElementById(id);
	if(variavel != null){
    variavel.focus();
}
}

/**
 * função para confirmar o cancelamento da senha
 * Método igual no módulo de monitor, havendo mudanças neste módulo mudar no monitor também
 * @param none
 * @return none
 */
Triagem.confirmaCancelarSenha = function(elem) {
    var radio_serv = document.getElementById("id_radio_servico");
    var radio_senha = document.getElementById("id_radio_senha");
    if(radio_serv.checked == true){
        var select =  document.getElementById("id_cancelar_senhas");
        var label1 = document.getElementById("label_cancel_senha");
        if(select != null){
            SGA.adverte(label1, false);
			
            var id_atendimento = select.value;
            var label2 = document.getElementById("label2_cancel_senha");
            if(id_atendimento != ""){
                SGA.adverte(label2, false);
                var popup = window.popup("cancel_serv");
                var parametro = new Object();
                parametro['id_atendimento'] = id_atendimento;
                Ajax.simpleLoad(TRIAG_PATH + "acoes/confirma_cancelar_senha.php",  popup.getAttribute("id"), "POST", Ajax.encodePostParameters(parametro), false);
                window.closePopup(elem);
            }else{
                SGA.adverte(label2, true);
            }
			
        }else{
            SGA.adverte(label1, true);
        }
    }
    else if(radio_senha.checked == true){
        var input = document.getElementById("id_id_atendimento");
        if(input != null) {
            var id_atendimento = input.value;

            var p = new Object();
            p['id_atendimento'] = id_atendimento;
            var popup = window.popup("cancel_serv");
            Ajax.simpleLoad(TRIAG_PATH + "acoes/confirma_cancelar_senha.php",  popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), false);
            window.closePopup(elem);
        }
        else {
            if (document.getElementById("id_text_senha").value == "Digite uma senha") {
                Triagem.selecionaTexto();
            }
            else {
                document.getElementById("id_button_procurar").focus();
            }
			
        }
    }
}

/**
 * função para confirmar o reativamento da senha
 * Método igual no módulo de monitor, havendo mudanças neste módulo mudar no monitor também
 * @param none
 * @return none
 */
Triagem.confirmaReativarSenha = function(elem) {
    var select =  document.getElementById("id_cancelar_senhas");
    var select_prio = document.getElementById("list_prio");
    var label_senhas = document.getElementById("id_label_senha_servico");
    var label_prioridades = document.getElementById("id_label_prioridade_servico");
    if(select != null){
        var id_atendimento = select.value;
        if(id_atendimento != ""){
            SGA.adverte(label_senhas, false);
            if (select_prio.value!=""){
                SGA.adverte(label_prioridades, false);
                var id_prio = select_prio.value;
                var parametro = new Object();
                parametro['id_atendimento'] = id_atendimento;
                parametro['id_prio'] = id_prio;
                var popup = window.popup("conf_reativar");
				
                Ajax.simpleLoad(TRIAG_PATH + "acoes/confirma_reativar_senha.php",  popup.getAttribute('id'), "POST", Ajax.encodePostParameters(parametro), false);
                
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
 * seleciona as senhas do serviço
 * @param none
 */
Triagem.onServicoSelecionado = function() {
    var select =  document.getElementById("servico_cancela_senha");
    if(select != null){
        var id_servico = select.value;
        var parametro = new Object();
        parametro['id_servico'] = id_servico;
        Ajax.simpleLoad(TRIAG_PATH + "senhas_servico.php",  "senhas_servico", "POST", Ajax.encodePostParameters(parametro), false);
    }
}

/**
 * seleciona as senhas canceladas do serviço
 * @param none
 */
Triagem.onServicoSelecionadoReativar = function() {
    var select =  document.getElementById("servico_reativa_senha");
    if(select != null){
        var id_servico = select.value;
        var parametro = new Object();
        parametro['id_servico'] = id_servico;
        Ajax.simpleLoad(TRIAG_PATH + "senhas_canceladas_servico.php",  "senhas_servico", "POST",
            Ajax.encodePostParameters(parametro), false, Triagem.onPrioridadeSenha);
    }
}

/**
 * carrega prioridade do atendimento selecionado
 */
Triagem.onPrioridadeSenha = function() {
    var select_atend =  document.getElementById("id_cancelar_senhas");
	
    var id_atend = select_atend.value;
    var parametro = new Object();
    parametro['id_atend'] = id_atend;
		
    Ajax.simpleLoad(TRIAG_PATH + "prioridade_senha.php",  "senhas_prioridade", "POST", Ajax.encodePostParameters(parametro), false);
	
		
}
/**
 * Popup pra confirmar prioridade
 * @param none
 * @return none
 */
Triagem.confirmarPrioridade = function(id_servico) {

    var popup = window.popup("tgm_confirmar_prioridade");

    var id_prio;
	
    //verificar qual prioridade foi selecionada se senha for do tipo prioridade
    for (var i = 0; i < document.forms['id_prio_sel'].id_prioridade.length; i++) {
        if (document.forms['id_prio_sel'].id_prioridade[i].checked) {
            id_prio = document.forms['id_prio_sel'].id_prioridade[i].value;
            var p = new Object();
            p['id_servico'] = id_servico;
            p['id_prio'] = id_prio;
			
            Ajax.simpleLoad(TRIAG_PATH + "acoes/confirmar_prioridade.php",  popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), false, Triagem.seleciona, 'confirmar_prioridade');
        }
    }
		
	
}

/**
 * carrega a popup com os subserviços do serviço selecionado
 * @param id_mestre
 */

Triagem.subServico = function (id_mestre) {
    var p = window.popup("tgm_show_sub_serv");

    p.focus();
	
    Ajax.simpleLoad(TRIAG_PATH + "acoes/mostra_sub_serv.php?id_mestre="+id_mestre,  p.getAttribute("id"), "GET", "", false);
}

Triagem.onRadioCancelar = function (radio){
    if (radio.id == "id_radio_servico"){
        Ajax.simpleLoad(TRIAG_PATH + "acoes/cancelar_senha_por_servico.php", "id_cancelar_senha", "GET", "", true);
    }else if (radio.id == "id_radio_senha"){
        Ajax.simpleLoad(TRIAG_PATH + "acoes/cancelar_senha_por_senha.php", "id_cancelar_senha", "GET", "", false, SGA.seleciona, 'id_text_senha');
    }
	
}
/**
 * Seleciona um RadioButton
 * @param radioButton
 * @author rafael e robson
 */
Triagem.selecionaRadioButton = function(radioButton){
    if (radioButton == null){
        radioButton = document.getElementById("id_radio_servico");
    }
    radioButton.checked = "checked";
    Triagem.onRadioCancelar(radioButton);
	
    var input = document.getElementById('confirmar_cancelar_senha');
    input.focus();
}

/**
 * Seleciona o texto de um input text
 * @author robson
 */
Triagem.selecionaTexto = function (textBox){
    if(textBox == null){
        textBox = document.getElementById("id_text_senha");
    }
    textBox.value = "Digite uma senha";
    textBox.select();
    textBox.focus();

}

/**
 * Executa tratamento de erro na popup canclar senha por senha 
 * chama o arquivo php procura_senha
 */
Triagem.procuraSenha = function (){
    var num_senha = document.getElementById("id_text_senha").value;
    var label_senha = document.getElementById("label_cancelar_senha");

    if(num_senha == ""){
        SGA.seleciona("id_text_senha");
        SGA.adverte(label_senha,true);
    }else{
        SGA.adverte(label_senha,false);
        var parametro = new Object();
        parametro['num_senha'] = Number(num_senha);
        Ajax.simpleLoad(TRIAG_PATH + "acoes/procura_senha.php", "id_label_senha", "POST", Ajax.encodePostParameters(parametro), true);
    }
}

/**
 * Seleciona um TextBox
 * @param textBox
 */
Triagem.selecionaTextBox = function(textBox){
    textBox.select();
}