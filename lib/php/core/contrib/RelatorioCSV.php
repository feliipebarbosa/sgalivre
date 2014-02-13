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

class RelatorioCSV extends Relatorio
{

    public function output()
	{
		$buffer = "";
		//Data
		foreach ($this->getBody() as $row)
		{
			$line = array();
			foreach ($row as $cell)
			{
				$line[] = '"'.str_replace('"', '""', iconv('utf-8' ,'iso-8859-1', $this->relstrip($cell))).'"';
			}
			$buffer .= implode(",", $line) . "\r\n";
		}

		if (ob_get_contents())
		{
			die('Saída já iniciada, impossível emitir CSV: ['.ob_get_contents().']');
		}
		if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
		{
			header('Content-Type: application/force-download');
		}
		else
		{
			header('Content-Type: application/octet-stream');
		}		
		if (headers_sent())
		{
			die('Saída já iniciada, impossível emitir CSV.');
		}
		header('Content-Length: '.strlen($buffer));
		header('Content-disposition: attachment; filename="relatorio.csv"');
		echo $buffer;
	}
}
?>
