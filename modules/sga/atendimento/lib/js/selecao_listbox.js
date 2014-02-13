
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

function valida_form() 
	{
	/*
	Desabilitado temporariamente para reparo posterior, em fun��o da urg�ncia de utiliza��o do amigo Paulo Rocha. 10/01/2006
	*/	
	for (i=0;i<document.forma.elements.length;i++)
		{ 
		if (document.forma.elements[i].name == "list2" && document.forma.elements[i].length <= 0 && document.forma.elements[i].cs_situacao[1].checked) 
			{
			alert("Voc� deve selecionar ao menos uma rede.");
			return false; 
			}
		else if (document.forma.elements[i].name == "list4" && document.forma.elements[i].length <= 0) 
			{
			alert("Voc� deve selecionar ao menos um sistema operacional.");
			return false; 
			}
		else if (document.forma.elements[i].name == "list9" && document.forma.elements[i].length <= 0) 
			{
			alert("Voc� deve selecionar ao menos um aplicativo.");
			return false; 
			}	
		else return true;
		}

	return true;
	}


function verifica_status() {
   if (document.forma.elements.cs_situacao[1].checked) {
       document.forma.elements['list1[]'].disabled=false;
       document.forma.elements['list2[]'].disabled=false;
       document.forma.elements['B1'].disabled=false;
       document.forma.elements['B2'].disabled=false;
   }
   else { 
       document.forma.elements['list1[]'].disabled=true;
       document.forma.elements['list2[]'].disabled=true;
       document.forma.elements['B1'].disabled=true;
       document.forma.elements['B2'].disabled=true;
   }

}
function copia(fbox,tbox) 
	{
//	tbox.options.length=0;

	for(var i=0; i<fbox.options.length; i++) 
		{
		if(fbox.options[i].selected && fbox.options[i].value != "")
			{
			var no = new Option();
			no.value = fbox.options[i].value;
			no.text = fbox.options[i].text;
			tbox.options[tbox.options.length] = no;
			}
		}
	ordena(tbox);
	}

function ordena(box)
	{
	for (i=box.options.length-1; i>=0; i--)
		{
		for (j=0; j<box.options.length-1; j++)
			{
			if (box.options[j].text > box.options[j + 1].text) 
				{
				Ttext = box.options[j].text;
				Tvalue = box.options[j].value;				
				box.options[j].text = box.options[j + 1].text;
				box.options[j].value = box.options[j + 1].value;				
				box.options[j + 1].text = Ttext;
				box.options[j + 1].value = Tvalue;				
				}
			}				
		}
	return box;
	}

function exclui(fbox,tbox) {
	for(var i=0; i<tbox.options.length; i++) 
	{
		for(var j=0; j<fbox.options.length; j++) 
		{	
			if(fbox.options[j].selected && fbox.options[j].value != "")
				{
				if (tbox.options[i].value == fbox.options[j].value && tbox.options[i].text == fbox.options[j].text) 
					{
					tbox.options[i].value = "";
					tbox.options[i].text = "";				
			   		}
				}
		}				
	}
 BumpUp(tbox);
 ordena(tbox); 	
}


function move(fbox,tbox) {
	for(var i=0; i<fbox.options.length; i++) {
		if(fbox.options[i].selected && fbox.options[i].value != "") {
				var no = new Option();
				no.value = fbox.options[i].value;
				no.text = fbox.options[i].text;
				tbox.options[tbox.options.length] = no;
				fbox.options[i].value = "";
				fbox.options[i].text = "";
		   }
	}
 BumpUp(fbox);
 ordena(tbox); 	 
}


function BumpUp(box)  {
	for(var i=0; i<box.options.length; i++) {
	   if(box.options[i].value == "")  {
			for(var j=i; j<box.options.length-1; j++)  {
				box.options[j].value = box.options[j+1].value;
				box.options[j].text = box.options[j+1].text;
			}
			var ln = i;
			break;
	   }
	}

	if(ln < box.options.length)  {
		box.options.length -= 1;
		BumpUp(box);
    }
}

function SelectAll(combo)
	{
	var seleciona = true;
  	for (var i=0;i<combo.options.length;i++) 
  		{
		if (combo.name=='list8[]')
			{		
			var texto = combo.options[i].text;
			if ((texto.indexOf("Data/Hora"))!=-1)
				{
				seleciona = false;
				}
			}
    	combo.options[i].selected=seleciona;
		seleciona = true;
   		}
	}

// 	As fun��es abaixo s�o para uso da sele��o de crit�rios para relat�rio patrimonial
function Preenche_Condicao_VAZIO(p_campo)
	{
	for (i=0;i<window.document.forms.length;i++)
		{
		for (j=0;j<window.document.forms[i].elements.length;j++)
			{
			if (window.document.forms[i].elements[j].name == p_campo)
				{
				window.document.forms[i].elements[j].value = "<VAZIO>";
				window.document.forms[i].elements[j].disabled = true;					
				}
			}
		}		
	}

function Verifica_Condicoes_Seta_Campo(p_campo)
	{
	for (i=0;i<window.document.forms.length;i++)
		{
		for (j=0;j<window.document.forms[i].elements.length;j++)
			{
			if (window.document.forms[i].elements[j].name == p_campo && window.document.forms[i].elements[j].value == "<VAZIO>")
				{
				window.document.forms[i].elements[j].value = '';
				window.document.forms[i].elements[j].disabled = false;										
				}
			}
		}		
	SetaCampo(p_campo);								
	}

function Verifica_Selecao(p_campo,p_campo_selecao)
	{
	if (p_campo.value == '')
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo_selecao)
					{
					window.document.forms[i].elements[j].value = '';
					}
				}
			}		
		}
	}


function Valida_Form_Pesquisa(p_argumento)
	{
	var v_conteudo = '';
	var v_tamanho = 0;
	v_tamanho = p_argumento.length;
	for (i=0;i<window.document.forms.length;i++)
		{
		for (j=0;j<window.document.forms[i].elements.length;j++)
			{
			if (window.document.forms[i].elements[j].name.substring(0,v_tamanho) == p_argumento && 
				window.document.forms[i].elements[j].value != '')
				{
				v_conteudo = v_conteudo + window.document.forms[i].elements[j].value;
				}
			}
		}

	if (v_conteudo == "")
		{
		alert("� necess�rio informar ao menos uma condi��o para pesquisa!");
		return false;
		}

	return true;
	}
