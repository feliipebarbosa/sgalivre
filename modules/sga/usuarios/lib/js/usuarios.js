
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

var USUARIOS_PATH = "?redir=modules/sga/usuarios/";

var id_grupo_selecionado = null;
var usuario_atual = null;

var Usuarios = function() {

    var self = this;

    this.update = function() {
    }
    
    this.refresh = function() {
        Usuarios.onLoadGrupos();
        Usuarios.selectGrupo(1);
        $("#commentForm").validate();
        self.update();
    }

}

var Usuario = function() {
    var self = this;

    // lotacoes a serem adicionadas
    this.lotacoesAdd = new Object();

    // lotacoes a serem removidas
    this.lotacoesDel = new Object();

    // Lista de serviços a serem adicionados
    this.uniServAdd = new Object();
    
    // Lista de serviços a serem removidos
    this.uniServDel = new Object();

    this.adicionarLotacao = function(id_grupo, id_cargo) {
        var key = id_grupo;

        if (self.lotacoesDel[key] != null) {
            delete self.lotacoesDel[key];
        }
        else {
            self.lotacoesAdd[key] = id_cargo;
        }
    }

    this.getLotacoesAdicionadas = function() {
        return self.lotacoesAdd;
    }

    this.removerLotacao = function(id_grupo, id_cargo) {
        var key = id_grupo;

        if (self.lotacoesAdd[key] != null) {
            delete self.lotacoesAdd[key];
        }
        else {
            self.lotacoesDel[key] = id_cargo;
        }
    }

    this.getLotacoesRemovidas = function() {
        return self.lotacoesDel;
    }

    this.adicionarServico = function(id_uni, elem) {
        
        // tenta remover da lista dos que serão removidos
        var removido = self.removerServicoLista(self.uniServDel, id_uni, elem.value);
        
         // se nao encontrou na memória, guardar para adicionar no banco
        if (!removido) {
            this.addOuCria(self.uniServAdd, id_uni, elem);
        }
    }

    this.getAllServicosAdicionados = function() {
        return self.uniServAdd;
    }

    this.getServicosAdicionados = function(id_uni) {
        return self.uniServAdd[id_uni];
    }

    this.removerServico = function(id_uni, elem) {
        // tenta remover da lista dos que serão adicionados
        var removido = self.removerServicoLista(self.uniServAdd, id_uni, elem.value);


        // se nao encontrou na memória, marcar para remoção do banco
        if (!removido) {
            this.addOuCria(self.uniServDel, id_uni, elem);
        }
    }

    this.getAllServicosRemovidos = function() {
        return self.uniServDel;
    }

    this.getServicosRemovidos = function(id_uni) {
        return self.uniServDel[id_uni];
    }

    this.removerServicoLista = function(lista, id_uni, id_serv) {
        if (lista[id_uni] != null) {
            for (var i = 0; i < lista[id_uni].length; i++) {
                if (lista[id_uni][i].value == id_serv) {
                    lista[id_uni].splice(i, 1); // remove elemento
                    return true;
                }
            }
        }
        return false;
    }

    this.addOuCria = function(obj, key, elem) {
        if (obj[key] == null) {
            obj[key] = new Array();
        }
         obj[key].push(elem);
    }
}

/**
 * Carrega os grupos do usuario
 */
Usuarios.onLoadGrupos = function() {
    $("#lista_grupos").treeview({
		collapsed: true,
		persist: "cookie"
	});
}
/**
 * Altera o estilo de um grupo selecionado
 * @param id_grupo
 * 
 */
var grupoBgAnt;
Usuarios.selectGrupo = function(id_grupo) {
    var elem = $('#span_grupo_'+id_grupo).get(0);
    var elemGrupoSelecionado = $('#span_grupo_'+id_grupo_selecionado).get(0);
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
}

/**
 *Chama arquivo php exibir_cargos_lotacao passando o id do grupo ocomo parametro 
 * 
 */
Usuarios.onSelectGrupoLotacao = function() {
    var selGrupo = $('#usuario_id_grupo').get(0);
    if (selGrupo.selectedIndex > 0) {
        var p = new Object();
        
        p['id_grupo'] = selGrupo.value;

        Ajax.simpleLoad(USUARIOS_PATH+'exibir_cargos_lotacao.php', 'select_cargo_lotacao', 'POST', Ajax.encodePostParameters(p), true);
    }
}

/**
 * Salvar lotação adicionada ou editada
 *
 * @param elem Elemento contido na popup, utilizado para fechar a poup referente
 * @param id_grupo (Opcional) Somente se está lotação acabou de ser editada, se refere ao ID do grupo anterior desta lotação
 * @param id_cargo (Opcional) Somente se está lotação acabou de ser editada, se refere ao ID do cargo anterior desta lotação
 */
Usuarios.salvarLotacao = function(elem, id_grupo, id_cargo) {
	var sel_grupo = $("#usuario_id_grupo").get(0);
	var sel_cargo = $("#usuario_id_cargo").get(0);
    var sel_lotacoes = $('#select_grupos_usuario').get(0);
    var lbl_grupo = $("#lbl_grupo").get(0);
    var lbl_cargo = $("#lbl_cargo").get(0);
    
    // grupo não selecionado
    if (sel_grupo.selectedIndex == 0) {
       // window.showErrorDialog("Você deve selecionar um grupo.");
    	SGA.adverte(lbl_grupo,true);
    }
    else if (sel_cargo.selectedIndex == 0) { // Cargo não selecionado
        //window.showErrorDialog("Você deve selecionar um cargo.");
    	SGA.adverte(lbl_cargo, true);
    	SGA.adverte(lbl_grupo,false);
    }
    else {
        if (id_grupo != null && id_cargo != null) {
            var anterior = id_grupo+';'+id_cargo;

            for (var i = 0; i < sel_lotacoes.length; i++) {
                if (sel_lotacoes.item(i).value == anterior) {
                    sel_lotacoes.remove(i);
                    usuario_atual.removerLotacao(id_grupo, id_cargo);
                }
            }
        }

        // adiciona novo item no select
        var text = sel_grupo.item(sel_grupo.selectedIndex).text+" - "+sel_cargo.item(sel_cargo.selectedIndex).text
        sel_lotacoes.options[sel_lotacoes.options.length] = new Option(text, sel_grupo.value+';'+sel_cargo.value);


        usuario_atual = new Usuario();
        usuario_atual.adicionarLotacao(sel_grupo.value, sel_cargo.value);

        // Atualiza o seletor de unidades para incluir alguma possivel unidade dentro do novo grupo
        Usuarios.refreshSelectUnidadeServicos();

        window.closePopup(elem);
    }
}

/**
 * Chama o arquivo php editar_lotacao para adicionar uma locacao 
 * @idUsu
 */

Usuarios.adicionarLotacao = function(idUsu) {
    var p = new Object();
    if (typeof(idUsu)!="undefined"){
    	p['id_usu'] = idUsu;
    }
    p['id_grupo_selecionado'] = id_grupo_selecionado;
    var popup = window.popup("usu_view_lotacao");

    Ajax.simpleLoad(USUARIOS_PATH + "editar_lotacao.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Usuarios.seleciona, 'save_grupo_user');
}

/**
 * Chama o arquivo php editar_lotacao para editars uma lotação
 */
Usuarios.editarLotacao = function() {
	var sel = $("#select_grupos_usuario").get(0);
	if (sel.value != '') {
		var p = new Object();

        var parts = sel.value.split(';');
		p['id_grupo'] = parts[0];
		p['id_cargo'] = parts[1];

		var popup = window.popup("usu_view_lotacao");

		Ajax.simpleLoad(USUARIOS_PATH + "editar_lotacao.php", popup.getAttribute("id"), "POST", Ajax.encodePostParameters(p), true, Usuarios.seleciona ,'save_grupo_user');
	}
}
/*
 * Remove uma lotacao do select de gruposD
 * 
 */
Usuarios.removerLotacao = function() {
    var sel_lotacoes = document.getElementById("select_grupos_usuario");
    var nSel = 0;
    for(var i = sel_lotacoes.length-1; i >= 0; i--){
		if(sel_lotacoes.options[i].selected){
			nSel++;
		}
    }
    if (nSel == sel_lotacoes.length) {
    	sel_lotacoes.focus();
    }
    else {
    	for(var i = sel_lotacoes.length-1; i >= 0; i--){
    		if(sel_lotacoes.options[i].selected){
    			SGA.removerElemSelecionadoSelect('select_grupos_usuario');
    		}
    	}
    }
 // Atualiza o seletor de unidades para incluir alguma possivel unidade dentro do novo grupo
    Usuarios.refreshSelectUnidadeServicos();
}

/**
 * Abre um popup para editar o usuário que acabou de ser criado
 */
Usuarios.editaNovoUsuario = function (idUsu){
	window.removeBlackout();
	Usuarios.mostraUsu(idUsu);
}
/**
 * cria o popup para alterar a senha do usuário
 *
 * @author robson
 */
Usuarios.alterarSenha = function() {
	var popup = window.popup('usu_alterar_senha');

	Ajax.simpleLoad(USUARIOS_PATH + "alterar_senha.php", popup.getAttribute("id"), "POST", '', true, Usuarios.seleciona, 'nova_senha');
}

/**
 * Executa os tratamentos necessarios para a alteração de senhas e
 * chama um popup de confirmação para alterar a senha
 *
 * @author robson
 */
Usuarios.confirmaAlterarSenha = function() {
	var novaSenha = $('#nova_senha').get(0);
	var confirmarNovaSenha = $('#confirmar_nova_senha').get(0);
	var labelSenhaNova = $('#id_senha_nova').get(0);
	var labelSenhaConfirma = $('#id_senha_confirma').get(0);
	var bool = true;
	
	if (novaSenha.value == confirmarNovaSenha.value	&& novaSenha.value != "" && novaSenha.value.length >=6) {
		SGA.adverte(labelSenhaNova,false);
		SGA.adverte(labelSenhaConfirma,false);
	} else {
		if(novaSenha.value == "" || novaSenha.value.length <6){
			SGA.adverte(labelSenhaNova,true);
			if(bool){
				novaSenha.select();
			}
		}else if(novaSenha.value != confirmarNovaSenha.value){
			SGA.adverte(labelSenhaNova,false);
			SGA.adverte(labelSenhaConfirma,true);
			if(bool){
				confirmarNovaSenha.select();
			}
		}
		bool = false;
	}

	if(bool){
		window.showYesNoDialog('Usuarios.alterarSenhaUsu();',
				'Deseja realmente alterar a senha?',
				'confirma alteração de senha');
	}
}

/**
 * Altera a senha do usuário se a senha atual fornecida for válida
 *
 */
Usuarios.alterarSenhaUsu = function() {
	var novaSenha = $('#nova_senha').get(0).value;
	var idUsu = $('#input_id_usuario').get(0).value;

	var p = new Object();
	p['id_usu'] = idUsu;
	p['nova_senha'] = novaSenha;

    var callbackOk = function() {
        window.closePopupById('usu_alterar_senha');
        window.showInfoDialog("Senha alterada com sucesso.");
    }

    SGA.executaOperacao(USUARIOS_PATH + "confirmar_alterar_senha.php", "POST", Ajax.encodePostParameters(p), callbackOk);
}

/*
 *chama o arquivo php info_usuario para exibir a janela de criação de usuario 
 */
Usuarios.criarUsuario = function() {
    usuario_atual = new Usuario();

    Usuarios.limpaInfoUsuario();
	var popup = window.popup("usu_view_user");

	Ajax.simpleLoad(USUARIOS_PATH + "info_usuario.php", popup.getAttribute("id"), "POST", "", true, Usuarios.seleciona,'nm_user');
}
/**
 * colocar foco num determinado campo
 * @param id
 */
Usuarios.seleciona = function (id) {
	var variavel = $('#'+id).get(0);
    if (variavel != null) {
        variavel.focus();
    }
}

/**
 * chama o arquivo php buscar_usuario 
 * para buscar uma conta de usuario de acordo com as informaçoes passadas por parametro e 
 * de acordo com os grupos que o usuario que realiza a busca tem acesso 
 */
Usuarios.buscarUsuario = function(idInput, idselectBusca) {
	var busca = $('#search_input').get(0).value;
	var tipoBusca = $('#search_type').get(0).value;
    
    var p = new Object();
    
    p['search_input'] = busca;
    p['search_type'] = tipoBusca;
    p['id_grupo'] = id_grupo_selecionado;

    Usuarios.limpaInfoUsuario();
    
    Ajax.simpleLoad(USUARIOS_PATH + "buscar_usuario.php","conteudo_resultado_usuarios", "POST", Ajax.encodePostParameters(p), true);
}

/**
 * Seleciona um usuario do select de usuarios resultado da busca
 */
Usuarios.onSelecionaUsuario = function() {
    var elem = $('#select_resultado_usuarios').get(0);

    // elem eh null se existe apenas um usuario
	if (elem != null && elem.value != "") {
		Usuarios.mostraUsu(elem.value);
	}
    else {
		Usuarios.limpaInfoUsuario();
	}
}

/**
 * Chama o arquivo php info_usuario.php para mostrar as informacoes do usuario selecionado
 */
Usuarios.mostraUsu = function(idUsu) {
	var p = new Object();
    
	p['id_usu'] = idUsu;
    p['id_grupo'] = id_grupo_selecionado;

    usuario_atual = new Usuario();

	Ajax.simpleLoad(USUARIOS_PATH + "info_usuario.php",	"config_user_info", "POST", Ajax.encodePostParameters(p), true);
}

/**
 * Limpa no html as informacoes do usuario
 */
Usuarios.limpaInfoUsuario = function() {
    usuario_atual = new Usuario();
    
	var div = $("#config_user_info").get(0);
	if (div != null) {
		div.innerHTML = "";
	}else{
		//Necessario para quando se tem somente um usuário.
		var div1 = $("#editar_usuario").get(0);
		if(div1 != null)
			div1.innerHTML = "";
	}
}

/**
 * Seleciona os serviços de uma unidade e os mostra num jump menu
 */
Usuarios.onSelecionaUnidadeServicos = function() {
    var selUnidade = $('#usu_id_uni_serv').get(0);
    var inputUsu = $('#input_id_usuario').get(0);
    
    // se estivermos criando o usuario inputUsu sera null
    // nesse caso não precisamos/podemos buscar serviços do usuario no banco
    if (inputUsu != null && selUnidade.selectedIndex > 0) {
        var p = new Object();

        p['id_uni'] = selUnidade.value;
        p['id_usu'] = inputUsu.value;

        Ajax.simpleLoad(USUARIOS_PATH + 'uni_serv.php', 'serv_list', 'POST', Ajax.encodePostParameters(p), true, Usuarios.updateUnidadeServicos);
    }
    else {
        var select_serv = $('#select_servicos_atendidos').get(0);
        select_serv.options.length = 0;
        Usuarios.updateUnidadeServicos();
    }
}

/**
 *  Atualiza o seletor de unidades para incluir alguma possivel unidade dentro do novo grupo
 */

Usuarios.refreshSelectUnidadeServicos = function() {
    var sel_lotacoes = $("#select_grupos_usuario").get(0);

    var p = new Object();

    var ids_grupo = new Array();
    for (var i = 0; i < sel_lotacoes.length; i++) {
        var parts = sel_lotacoes.item(i).value.split(";");
        ids_grupo.push(parts[0]);
    }
    p['ids_grupo[]'] = ids_grupo;
    p['id_grupo_filtro'] = id_grupo_selecionado;
    
    Ajax.simpleLoad(USUARIOS_PATH + "exibir_select_unidades.php", "div_select_unidades", "POST", Ajax.encodePostParameters(p), true);
}

/**
 * Atualiza os serviços por unidade
 */
Usuarios.updateUnidadeServicos = function() {
    var id_uni = $('#usu_id_uni_serv').get(0).value;
    var select_uni_serv = $('#select_servicos_atendidos').get(0);
    
    var servsAdd = usuario_atual.getServicosAdicionados(id_uni);
    var servsDel = usuario_atual.getServicosRemovidos(id_uni);

    var servicos = new Array();
    var id_serv;
    
    for (var i = 0; i < select_uni_serv.length; i++) {
        var encontrado = false;
        id_serv = select_uni_serv.item(i).value;

        // verifica se o servico deve ser removido
        for (var j = 0; servsDel != null && j < servsDel.length; j++) {
            if (id_serv == servsDel[j].value) {
                select_uni_serv.remove(i);
                encontrado = true;
            }
        }

        // se nao foi removido
        if (!encontrado) {
            servicos.push(id_serv);
        }
    }

    for (var i = 0; servsAdd != null && i < servsAdd.length; i++) {
        if (!SGA.arrayContains(servicos, servsAdd[i].value)) {
            select_uni_serv.add(servsAdd[i], null);
        }
    }
}

/**
 * Cria o popup para Adicionar serviços na lista de serviços que podem ser atendidos pelo usuário
 * 
 */
Usuarios.adicionarServ = function() {
	var select = $('#select_servicos_atendidos').get(0);
    var selUnidade = $('#usu_id_uni_serv').get(0);

    var parametros = new Object();
    
	parametros['id_servicos[]'] = new Array();
	for ( var i = 0; i < select.length; i++) {
		parametros['id_servicos[]'][i] = select.item(i).value;
	}
    parametros['id_uni'] = selUnidade.value;

	var p = window.popup("usu_view_serv");
    
	Ajax.simpleLoad(USUARIOS_PATH + "servico_usu.php",  p.getAttribute("id"), "POST",Ajax.encodePostParameters(parametros), false, Usuarios.seleciona, 'confirmar_novo_servico');
}

/**
 * Adiciona serviços na lista de serviços que podem ser atendidos pelo usuário
 * 
 */
Usuarios.adicionarServUsu = function(elem) {
	var origem = $('#select_serv_uni').get(0);
	var destino = $('#select_servicos_atendidos').get(0);
    var id_uni = $('#usu_id_uni_serv').get(0).value;
	if (origem != null) {
		var parametros = new Object();
		parametros['id_serv[]'] = new Array();
        var opt;
		if (origem.item(origem.selectedIndex).text == 'Todos') {
			for(var i=1;i<origem.options.length;i++){
				//destino.options[destino.options.length] = new Option(origem.item(i).text,parseInt(origem.item(i).value));
                opt = new Option(origem.item(i).text,parseInt(origem.item(i).value));
                destino.options[destino.options.length] = opt;
                usuario_atual.adicionarServico(id_uni, opt);
			}
		}
        else {
        	//destino.options[destino.options.length] = new Option(origem.item(origem.selectedIndex).text,parseInt(origem.item(origem.selectedIndex).value));
            opt = new Option(origem.item(origem.selectedIndex).text,parseInt(origem.item(origem.selectedIndex).value));
            destino.options[destino.options.length] = opt;
            usuario_atual.adicionarServico(id_uni, opt);
		}
        Usuarios.updateUnidadeServicos();
	}
    window.closePopup(elem);
}

/**
 * Remove os serviços selecionados
 * @param elem
 */
Usuarios.removerServ = function(elem) {
    var select = document.getElementById("select_servicos_atendidos");
    var id_uni = $('#usu_id_uni_serv').get(0).value;
    if(select.value != ""){
    	for(var i = select.length-1; i >= 0; i--){
    		if(select.options[i].selected){
    			usuario_atual.removerServico(id_uni, select.item(select.selectedIndex));
    			SGA.removerElemSelecionadoSelect('select_servicos_atendidos');
    		}
    	}
    }else{
    	select.focus();
    }
}

/**
 * Retorna um array contendo os valores do select passado como parâmetro,
 * desconsidernado o primeiro valor
 * 
 * @param idSelect,
 *            id do select de onde vão ser copiados os dados
 * @param desconsiderar,
 * @return Array()
 * @author robson
 */
Usuarios.preencheVetorSelect = function(idSelect, desconsiderar, inteiro) {
	if(typeof inteiro == 'undefined'){
		inteiro = true;
	}
	var select = document.getElementById(idSelect);
	var vetor = new Array();
	var aux = 0;
	if (desconsiderar > 0) {
		aux = 1;
	}
	for ( var i = desconsiderar; i < select.length; i++) {
		/**
		 * o i tem que começar em 1 para desconsiderar o 1º elemento do select e
		 * o indice do vetor tem que ser i-1 porque em JS o vetor começa em 0
		 * (zero)
		 * 
		 * @author robson
		 */
		if(inteiro){
			vetor[i - aux] = parseInt(select.item(i).value);
		}else{
			vetor[i - aux] = select.item(i).value;
		}
	}
	return vetor;
}

/**
 * Edita ou Cria um usuario
 * @param elem
 * @param loguns_usu
 * 
 */
Usuarios.editarUsuario = function(elem, logins_usu) {
	var textLogin = $("#login_usu").get(0);
	var textNome = $("#nm_user").get(0);
	var textUltNome = $("#ult_nm_user").get(0);
    var sel_lotacoes = $("#select_grupos_usuario").get(0);
    var logins = logins_usu.split(",");
    var bool = true;
    var labelLogin = $("#id_label_editar_mat").get(0);
    var labelNome = $("#id_label_edit_nome").get(0);
    var labelUltNome = $("#id_label_edit_ult_nome").get(0);
    
    var arrayInputs = new Array(textLogin, labelLogin, textNome, labelNome, textUltNome, labelUltNome);
    
    for ( i = 0; i < arrayInputs.length-1; i += 2) {
    	if (arrayInputs[i].value == "" && i%2 == 0) {
    		SGA.adverte(arrayInputs[i+1], true);
    		bool = false;
    	}else{
    		SGA.adverte(arrayInputs[i+1], false);
    	}
    }
    //var select_Usu = $('#select_resultado_usuarios').get(0);
    if(bool){
	    if(sel_lotacoes.length <= 0) {
	        window.showErrorDialog("Um usuário precisa estar lotado em pelo menos um grupo.", "ERRO");
	        return;
	    }
    
	    // preparações de parametros POST
	    var p = new Object();
	    for (var i = 0; i < sel_lotacoes.length; i++) {
	        var parts = sel_lotacoes.item(i).value.split(";");
	        p['lotacoes['+parts[0]+']'] = parts[1];
	    }
	
	    var uniServAdd = usuario_atual.getAllServicosAdicionados();
	    for (k in uniServAdd) {
	        p['servicos_add['+k+'][]'] = Usuarios.getArrayIds(uniServAdd[k]);
	    }
	
	    var uniServDel = usuario_atual.getAllServicosRemovidos();
	    for (k in uniServDel) {
	        p['servicos_del['+k+'][]'] = Usuarios.getArrayIds(uniServDel[k]);
	    }
	    
	    var idUsu = $("#input_id_usuario").get(0);
	    var lgUsu = $("#input_lg_usuario").get(0);
	    var criando = idUsu == null;
	    
	    if (criando) {
	        // criando usuario
	    	var labelSenha1 = $("#id_label_senha").get(0);
	        var labelSenha2 = $("#id_label_senha2").get(0);
	        var senha = $("#senha_usu").get(0).value;
	        var confirmaSenha = $("#senha_usu2").get(0).value;
	        
	        p['senha_usu'] = senha;
	        p['senha_usu2'] = confirmaSenha;
	        for(i=0;i<logins.length;i++){
	    		if (logins[i] == textLogin.value){
	    			SGA.adverte(labelLogin,true,'Login já existe');
	    			bool = false;
	    			break;
	    		}
	    	}
	        if(senha.length < 6 ){
	        	bool = false;
	        	SGA.adverte(labelSenha1, true);
	        }else{
	        	SGA.adverte(labelSenha1, false);
	        }
	        if(senha != confirmaSenha){
	        	bool = false;
	        	SGA.adverte(labelSenha2, true);
	        }else{
	        	SGA.adverte(labelSenha2, false);
	        }
	    }
	    else {
	        //  editando usuario
	        p['id_usu'] = idUsu.value;
	        for(i=0;i<logins.length;i++){
	    		if (logins[i] == textLogin.value && logins[i] != lgUsu.value){
	    			SGA.adverte(labelLogin,true,'Login já existe');
	    			bool = false;
	    			break;
	    		}
	    	}
	    }
	
	    
	    
	    p['login_usu'] = textLogin.value;
	    p['nm_usu'] = textNome.value;
	    p['ult_nm_usu'] = textUltNome.value;
	
	    var callbackOk = function() {
	        if (criando) {
	            window.closePopup(elem);
	            window.showInfoDialog("Usuário criado com sucesso.");
	        }
	        else {
	            Usuarios.onSelecionaUsuario();
	            window.showInfoDialog("Usuário editado com sucesso.");
	        }
	    }
    
	    if(bool){
	    	SGA.executaOperacao(USUARIOS_PATH + "gravar_config_usu.php", "POST", Ajax.encodePostParameters(p), callbackOk);
	    }
    }
    
}

/**
 * Retorna um array com ids dos serviços removidos ou adicionados
 * @param options
 * @return ret
 */
Usuarios.getArrayIds = function(options) {
    var ret = new Array();

    if (options != null) {
        for (var i = 0; i < options.length; i++) {
            ret.push(options[i].value);
        }
    }
    return ret;
}

/**
 * Modifica o status do usuario
 * @param id_usu
 * @param stat_usu
 */
Usuarios.modificaStatus = function(id_usu,stat_usu) {
	var usu = $('#select_resultado_usuarios').get(0);
	var cor;
	var popup = window.popup('id_status');
	if (stat_usu == 1) {
		stat_usu = 0;
	}
    else {
		stat_usu = 1;
	}
	
	cor = (stat_usu == 0) ? 'red':'black';
	if(usu != null){
		for (var i=0;i<usu.length;i++)
	    {
			if(usu.options[i].value == id_usu){
				usu.options[i].style.color = cor;
			}
	    }
	}
	
	var p = new Object();
	p['id_usu'] = id_usu;
	p['stat_usu'] = stat_usu;


	Ajax.simpleLoad(USUARIOS_PATH + "alterar_status.php",popup.getAttribute('id'), "POST", Ajax.encodePostParameters(p), false, Usuarios.onSelecionaUsuario);
}
