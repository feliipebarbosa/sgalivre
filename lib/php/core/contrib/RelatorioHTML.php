<?php

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

require_once('Relatorio.php');

class RelatorioHTML extends Relatorio
{
	public static function rgb2html($r, $g=-1, $b=-1)
	{
		if (is_array($r) && sizeof($r) == 3)
		    list($r, $g, $b) = $r;

		$r = intval($r); $g = intval($g);
		$b = intval($b);

		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));

		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return '#'.$color;
	}

	public function output()
	{
		$cor = FALSE;
		$titulo = $this->getTitulo();
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<title><?php echo $titulo;?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		</head>

		<body bgcolor="#FFFFFF" topmargin="5">
		<div style="padding: 10px;">
		<table border="0" align="left" cellpadding="0" cellspacing="10" bordercolor="#999999">
		  <tr> 
			<td rowspan="5"><img class="img_logo_horizontal" src="themes/sga.default/imgs/logo_horizontal_simples.png"></td>
			<td rowspan="5"></td>
			<td> </td>
		  </tr>
		  <tr>
		  <tr width="100%">
			<td nowrap><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo $titulo;?></strong></font></td>	
		  </tr>
		  <tr> 
			<td height="2" bgcolor="#333333"></td>
		  <tr> 
			<td>
				<p align="left">
					<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<?php echo $this->getSubTitulo();?>
					</font>
				</p>
			</td>
		  </tr>
		  </tr>
		  <tr cellpadding="10"> 
			<td></td>
		  </tr>
		</table>
		<br>
		<br>
		<br>
		<!-- <font size="1" face="Verdana, Arial, Helvetica, sans-serif"></tr>Exportar:&nbsp;<a href="?formato=pdf">PDF</a>&nbsp;|&nbsp;<a href="?formato=ods">ODS</a>&nbsp;|&nbsp;<a href="?formato=csv">CSV</a></font> -->
		<br>
		<br>

        <?php
        $graidleCounter = time();
		if(sizeof($this->getComponentes()) > 0){
        foreach ($this->getComponentes() as $componente)
		{
			if ($componente instanceof Tabela)
			{
				echo '<table width="'.$componente->getWidth().'%" cellpadding="2" cellspacing="3" border="0" bordercolor="#999999" bordercolordark="#e1e1e1">';

				// titulo
				echo '<tr><td nowrap align="center" style="font-size: 13px; font-weight: bold; color: #003366; font-family: Verdana,Arial,Sans-serif;" bgcolor="#'.$componente->getTitleBgColor().'" colspan="'.$componente->getColsCount().'">'.$componente->getTitulo().'</tr>';
				// cabeçalho
                $cabecalho = $componente->getCabecalho();
                if ($cabecalho)
                {
                    echo '<tr>';
                    foreach ($cabecalho as $itemCabecalho)
                    {
                        echo '<td nowrap align="center" style="font-size: 11.5px; font-weight: bold; color: #003366; font-family: Verdana,Arial,Sans-serif;" bgcolor="#f1f1f1">'.$itemCabecalho.'</td>';
                    }
                    echo '</tr>';
                }
				//Data
				$i = 0;
				foreach ($componente->getData() as $row)
				{
					
					$j = 0;
					$bgcolor = 'bgcolor='.( $i % 2 ? '"#E9F0F8"' : '"F7FAFD"' );
					echo "<tr $bgcolor>";
                    $width = $componente->getColWidth($j);
                    $align = $componente->getAlign();
                    if (!$width)
                        $width = floor(100 / count($row));
					foreach ($row as $cell)
					{
						$bgcolor = "";
						$color = $componente->getCellBgColor($i, $j);
						if ($color) {
							$bgcolor = 'bgcolor="'.RelatorioHTML::rgb2html($color).'"';
						}
						echo '<td nowrap align="'.$align.'"  width="'.$width.'%" ', $bgcolor, ' style="font-size:12px;Font Family: Verdana,Arial,Sans-serif;">';
                        
                        $fontColor = $componente->getCellFontColor($i, $j);
                        if ($fontColor)
                        {
                            echo '<font color="'.RelatorioHTML::rgb2html($fontColor).'">'.$cell.'</font>';
                        }
                        else
                        {
                            echo $cell;
                        }
						echo '&nbsp;</td>';
						
						$j++;
					}
					echo '</tr>';
					$cor = !$cor;
					
					$i++;
				}
				
				echo '</table>';
				
			}
			else if ($componente instanceof Label)
            {
				echo '<h1>'.$componente->getTexto().'</h1>';
			}
            else if ($componente instanceof Graidle)
            {
                $_SESSION['graidle'][$graidleCounter] = $componente;
				echo '<img src="?redir=lib/php/core/contrib/rel_graidle_img.php&id_graidle='.$graidleCounter.'" />';
                $graidleCounter++;
			}
			else if ($componente instanceof Separador)
            {
				echo '<br/><br/>';
			}
		}
		
	}else{
		?>
		
		<div style="font-size:20px; Font Family:Verdana,bold,Arial,Sans-serif; text-align:center;">
            Não há atendimentos no período.
		</div>
		<?php
		
	}
	?>
			<br/>
			<div style="font-size:10px; Font Family:Verdana,Arial,Sans-serif">
                Tempo de Processamento: <?php echo Relatorio::get_seconds_as_time($this->getTimeSinceStart());?><br/>
                SGA Livre - Desenvolvido pela Dataprev
			</div>
		</div>
		</body>
		</html>
		<?php
	}
	
}
?>
