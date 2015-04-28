<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TAgenda
 *
 * @author Felipe
 */
class TAgendamento extends Template{
    
	/**
	 * MÃ©todo para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		SGA::_include("modules/sga/agendamento/content_loader.php");
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/agendamento.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/agendamento.js"></script>' . "\n";
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela da agenda
	 * @param $user
	 */
	public static function display_agendamento(Usuario $user) {
		$topo = array('TAgendamento', 'display_agendamento_topo');
		$menu = array('TAgendamento', 'display_agendamento_menu');
		$conteudo = array('TAgendamento', 'display_agendamento_content');
		Template::display_template_padrao($topo, $menu, $conteudo);

	}

	/**
	 * Constrói o topo da tela da agenda
	 */
	public static function display_agendamento_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela da agenda
	 */
	public static function display_agendamento_menu() {
		$usuario = SGA::get_current_user();
		TAgendamento::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Mostra conteúdo do método agenda
	 */
	public static function display_agendamento_content() {
		TAgendamento::display_conteudo();
		SGA::_include("modules/sga/agendamento/content_loader.php");
		       
	}

	public static function display_conteudo() {
        $id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$serv = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
		
		Template::display_page_title('Criar Agendamento'); 
		$horas = array('07:00'=>'07:00','08:00'=>'08:00','09:00'=>'09:00','10:00'=>'10:00','11:00'=>'11:00','12:00'=>'12:00','13:00'=>'13:00','14:00'=>'14:00','15:00'=>'15:00','16:00'=>'16:00','17:00'=>'17:00','18:00'=>'18:00','19:00'=>'19:00');
		?>
		
		<label style="font-weight:bold;">Unidade: </label><select> <option> Cariacica </option>
										 <option>Vitória</option>
								</select>
		</br>
		</br>
		</br>
								
		<div id="conteudo_servicos">
		</br>	

		<SCRIPT LANGUAGE="JavaScript">

			$(document).ready(function(){
				$("#carrega_agenda").toggle();
			})

			function carrega_agenda(){
				$('#carrega_agenda').show();	
				//$('#carrega_agenda').load($(this).attr('href'));
			}

			<!-- Begin
			var day_of_week = new Array('Dom','Seg','Ter','Qua','Qui','Sex','Sab');
			var month_of_year = new Array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
 
			var Calendar = new Date();
 
			var year = Calendar.getFullYear();   // Retorna o ano
			var month = Calendar.getMonth();    // Retorna mes (0-11)
			var today = Calendar.getDate();     // Retorna dias (1-31)
			var weekday = Calendar.getDay();   // Retorna dias (1-31)
 
			var DAYS_OF_WEEK = 7;    // "constant" para o numero de dias na semana
			var DAYS_OF_MONTH = 31;    // "constant" para o numero de dias no mes
			var cal;    // Usado para imprimir na tela
 
			Calendar.setDate(1);    // Comecar o calendario no dia '1'
			Calendar.setMonth(month);    // Comecar o calendario com o mes atual
 
 
			var TR_start = '<TR>';
			var TR_end = '</TR>';
			var highlight_start = '<TD WIDTH="30"><TABLE CELLSPACING=0 BORDER=1 BGCOLOR=DEDEFF BORDERCOLOR=CCCCCC><TR><TD WIDTH=20><B><CENTER>';
			var highlight_end   = '</CENTER></TD></TR></TABLE></B>';
			var TD_start = '<TD WIDTH="30"><CENTER>';
			var TD_end = '</CENTER></TD>';
 
			cal =  '<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=0 BORDERCOLOR=BBBBBB><TR><TD>';
			cal += '<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>' + TR_start;
			cal += ' <TD COLSPAN="' + DAYS_OF_WEEK + '" BGCOLOR="#EFEFEF"><CENTER> << <B>';
			cal += month_of_year[month]  + '   ' + year + '</B> >>' + TD_end + TR_end;
			cal += TR_start;
 
			for(index=0; index < DAYS_OF_WEEK; index++)
			{
 
				if(weekday == index)
					cal += TD_start + '<B>' + day_of_week[index] + '</B>' + TD_end;
				else
					cal += TD_start + day_of_week[index] + TD_end;
			}
 
			cal += TD_end + TR_end;
			cal += TR_start;
 
			for(index=0; index < Calendar.getDay(); index++)
				cal += TD_start + '  ' + TD_end;
 
			for(index=0; index < DAYS_OF_MONTH; index++)
			{
				if( Calendar.getDate() > index ){
  					week_day =Calendar.getDay();
  					if(week_day == 0)
  						cal += TR_start;
  					if(week_day != DAYS_OF_WEEK){
  						var day  = Calendar.getDate();
  						if( today==Calendar.getDate() )
  							cal += highlight_start + '<a onclick="carrega_agenda();" id="data">'+day+'<\a>' + highlight_end + TD_end;
  						else
  							cal += TD_start + '<a onclick="carrega_agenda();">'+day+'<\a>' + TD_end;
  					}
  					if(week_day == DAYS_OF_WEEK)
  						cal += TR_end;
  				}
  				Calendar.setDate(Calendar.getDate()+1);
			}
			cal += '</TD></TR></TABLE></TABLE>';
 
			//  MOSTRAR CALENDARIO
			document.write(cal);
			//  End -->
		</SCRIPT>

		</br>
		</br>
		<div id = "carrega_agenda">
		<form id="frm_criar_agendamento" method="post" action="" onsubmit="Agendamento.criar_agendamento(); return false;">						
			<? $agendas = DB::getInstance()->get_agendas('2014-11-04', null, $id_uni, null); ?>
			<pre>
				<? print_r($id_uni); ?>
				<? print_r($agendas); ?>
			</pre>
			
			<table class="agendamento">
				<thead>
					<tr style="padding-button:30px;">
						<td style="width:30px; text-align:center; font-weight:bold; padding-button:30px;"> </td>
						<td style="width:80px; text-align:center; font-weight:bold; padding-button:30px;"> Dia/Mês/Ano</td>
						<td style="width:50px; text-align:center; font-weight:bold; padding-button:30px;"> Hora</td>
						<td style="width:80px; text-align:center; font-weight:bold; padding-button:30px;" > Atendente</td>
						<td style="width:80px; text-align:center; font-weight:bold; padding-button:30px;" > Unidade</td>
					</tr>		
				</thead>
				<? foreach($agendas as $agenda){ ?>
					
					<tr style="border: solid #d1d1d1 1px;">
						<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="1" value="1" /></td>
						<td style="width:80px; text-align:center;" > <?  ?> </td>
						<td style="width:50px; text-align:center;"> 08:00 </td>
						<td style="width:80px; text-align:center;"> Kamila </td>
						<td style="width:80px; text-align:center;"> Cariacica </td>
					</tr>
				<? } ?>	
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 08:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 09:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 09:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 10:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 10:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 11:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 11:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 13:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 13:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 14:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 14:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 15:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 15:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 16:00 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="2" value="2" /></td>
					<td style="width:80px; text-align:center;" > 29/07/2014 </td>
					<td style="width:50px; text-align:center;"> 16:30 </td>
					<td style="width:80px; text-align:center;"> Kamila </td>
					<td style="width:80px; text-align:center;"> Cariacica </td>
				</tr>



			</table>	
			<br>
			<br>
			<div>
				<?php
					Template::display_action_button("Confirmar", "images/tick.png", "Agendamento.criar_agendamento();",'button','',true,'Clique para confirmar a criação do agendamento.');
            		Template::display_action_button("Voltar", "images/cross.png", "Agendamento.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
				?>
			</div>
		</form>	
		</div>
		</div>
		<?php
	}

}

?>