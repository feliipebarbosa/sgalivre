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

abstract class Relatorio
{
	private $m_titulo;
	private $m_subTitulo;
	private $m_componentes = array();
    private $m_startTime;
    private $m_endTime;

    public function  __construct() {
        $this->m_startTime = time();
    }

	/**
	 * Altera o titulo do Relatório
	 * 
	 * @param $titulo O novo titulo do relatório
	 * @return void
	 */
	public function setTitulo($titulo)
	{
		$this->m_titulo = $titulo;
	}

	/**
	 * Obtem o titulo do relatório.
	 * 
	 * @return string o titulo do relatório.
	 */
	public function getTitulo()
	{
		return $this->m_titulo;
	}
	
	/**
	 * Altera o subtitulo do Relatório
	 * 
	 * @param $titulo O novo subtitulo do relatório
	 * @return void
	 */
	public function setSubTitulo($titulo)
	{
		$this->m_subTitulo = $titulo;
	}

	/**
	 * Obtem o subtitulo do relatório.
	 * 
	 * @return string o subtitulo do relatório.
	 */
	public function getSubTitulo()
	{
		return $this->m_subTitulo;
	}
	
	public function addComponente($componente)
	{
		return $this->m_componentes[] = $componente;
	}
	
	public function getComponentes()
	{
		return $this->m_componentes;
	}

	public function relstrip($html)
	{
		return strip_tags(str_ireplace('<br>', "\n", $html));
	}

    public function getTimeSinceStart() {
        return time() - $this->m_startTime;
    }

    public static function get_seconds_as_time($secs) {
        $h = floor($secs / 3600);
        $i = floor(($secs % 3600) / 60);
        $s = $secs % 60;
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);
        $i = str_pad($i, 2, '0', STR_PAD_LEFT);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

        return "$h:$i:$s";
    }

    public abstract function output();
}
?>
