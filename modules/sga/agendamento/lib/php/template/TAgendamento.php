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
		
		Template::display_page_title('Criar Agendamento'); 		
		?>

		</br>
								
		<div id="conteudo_servicos">
		</br>	

		<SCRIPT LANGUAGE="JavaScript"> 


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
 			
 			if(month < 10){
 				var mes = ('0'+(month+1));
 			}else{
 				var mes = (month+1);
 			}
 
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
  						if(day < 10){
 							var dia = '0'+day;
 						}else{
 							var dia = day;
 						}
  						var data = (year+'-'+mes+'-'+dia).toString();
  						
  						if( today==Calendar.getDate() ){
  							cal += highlight_start + '<a onclick="Agendamento.buscarAgenda(\''+data+'\');">'+day+'<\a>' + highlight_end + TD_end;
  						}else{
  							cal += TD_start + '<a onclick="Agendamento.buscarAgenda(\''+data+'\');">'+day+'<\a>' + TD_end;
  						}	
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
		</div>
		<div id = "carrega_agenda"></div>		 
			
		</br>
		</br>
		<?
	}

	public static function display_agenda_dia($dia){
	?>	

		<form id="frm_criar_agendamento" name="frm_criar_agendamento" method="post" action="" onsubmit="Agendamento.criarAgendamento(); return false;" >						
			<? 
			$id_uni = SGA::get_current_user()->get_unidade()->get_id();
			$usuario_logado = SGA::get_current_user()->get_id();
			$agendas = DB::getInstance()->get_agendas_disponiveis($dia, null, $id_uni, null, $usuario_logado); ?>
			<input type="hidden" id="dia" value="<? echo $dia ?>" />
			
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
				<? foreach($agendas as $agenda){ 
					$usu_name = DB::getInstance()->get_usuario_by_id($agenda->get_id_usu()); 
					$uni_name = DB::getInstance()->get_unidade($agenda->get_id_uni()); 		

					if($usuario_logado == $agenda->get_id_cliente()){
						$sim = "checked='checked'";
					}
				?>
					
					<tr style="border: solid #d1d1d1 1px;">
						<td style="width:30px; text-align:center;" ><input type="radio" name="agendamento" id="<? echo $agenda->get_id(); ?>" value="<? echo $agenda->get_id(); ?>" <?php echo $sim;?>/></td>
						<td style="width:80px; text-align:center;" > <? echo date('d/m/Y', strtotime($agenda->get_dia())); ?> </td>
						<td style="width:50px; text-align:center;"> <? echo date('H:i', strtotime($agenda->get_hora())); ?> </td>
						<td style="width:80px; text-align:center;"> <? echo $usu_name->get_nome(); ?> </td>
						<td style="width:100px; text-align:center;"> <? echo $uni_name->get_nome(); ?> </td>
					</tr>
			<?
				$sim = null; 
				} ?>	
				
			</table>	
			<br>
			<br>
			<div>
				<?php
					Template::display_action_button("Confirmar", "images/tick.png", "Agendamento.criarAgendamento();",'button','',true,'Clique para confirmar a criação do agendamento.');
            		Template::display_action_button("Voltar", "images/cross.png", "Agendamento.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
				?>
			</div>
		</form>	
		
		<?php
	}

}

?>