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

/**
 * 
 * @author ulysses
 *
 */
class Tabela {
	private $m_titulo;
	private $m_cols;
    private $m_cabecalho = array();
	private $m_data = array();
	private $m_cellFontColor = array();
	private $m_cellColor = array();
    private $m_width;
    private $m_titleBgColor;
    private $m_colWidth = array();
    private $m_align;
	
	public function __construct($titulo, $cols, $cabecalho = null, $width = 100, $titleBgColor = "bad6ff",$align = "center")
    {
		$this->m_titulo = $titulo;
		$this->m_cols = $cols;
        $this->m_cabecalho = $cabecalho;
        $this->m_width = $width;
        $this->m_titleBgColor = $titleBgColor;
        $this->m_align = $align;
	}
	
	/**
	 * Altera a cor do background de uma célula da tabela.
	 * 
	 * @param $row O numero da linha da célula.
	 * @param $col O numero da coluna da célula.
	 * @param $rgb O código RGB da cor 
	 * @return void
	 */
	public function setCellBgColor($row, $col, $rgb)
	{
		$this->m_cellColor[$row][$col] = $rgb;
	}
	
	/**
	 * Altera a cor do background de uma linha da tabela.
	 * 
	 * @param $row O numero da linha da célula.
	 * @param $rgb O código RGB da cor 
	 * @return void
	 */
	public function setRowBgColor($row, $rgb)
	{
		for ($i = 0; $i < $this->getColsCount(); $i++)
		{
			$this->m_cellColor[$row][$i] = $rgb;
		}
	}

	/**
	 * Obtem a cor do background de uma célula da tabela.
	 * @param $row O numero da linha da célula.
	 * @param $col O numero da coluna da célula.
	 * @return int O código RGB da cor de fundo da célula.
	 */
	public function getCellBgColor($row, $col)
	{
		if (!isset($this->m_cellColor[$row][$col]))
		{
			return FALSE;
		}
		return $this->m_cellColor[$row][$col];
	}

    /**
	 * Altera a cor da fonte de uma célula da tabela.
	 *
	 * @param $row O numero da linha da célula.
	 * @param $col O numero da coluna da célula.
	 * @param $rgb O código RGB da cor
	 * @return void
	 */
	public function setCellFontColor($row, $col, $width)
	{
		$this->m_cellFontColor[$row][$col] = $width;
	}

	/**
	 * Altera a cor da fonte do texto de uma linha da tabela.
	 *
	 * @param $row O numero da linha da célula.
	 * @param $rgb O código RGB da cor
	 * @return void
	 */
	public function setRowFontColor($row, $rgb)
	{
		for ($i = 0; $i < $this->getColsCount(); $i++)
		{
			$this->m_cellFontColor[$row][$i] = $rgb;
		}
	}

	/**
	 * Obtem a cor da fonte de uma célula da tabela.
	 * @param $row O numero da linha da célula.
	 * @param $col O numero da coluna da célula.
	 * @return int O código RGB da cor da fonte da célula.
	 */
	public function getCellFontColor($row, $col)
	{
		if (!isset($this->m_cellFontColor[$row][$col]))
		{
			return FALSE;
		}
		return $this->m_cellFontColor[$row][$col];
	}
	
	public function addRow(array $row)
	{
		$this->m_data[] = $row;
	}

	public function getData()
	{
		return $this->m_data;
	}
	
	public function getColsCount()
	{
		return $this->m_cols;
	}
	
	public function getTitulo()
	{
		return $this->m_titulo;
	}

    public function setCabecalho($newCabecalho)
    {
        $this->m_cabecalho = $newCabecalho;
    }

    public function getCabecalho()
    {
        return $this->m_cabecalho;
    }

    public function getWidth()
    {
        return $this->m_width;
    }

    public function setWidth($newWidth)
    {
        $this->m_width = $newWidth;
    }

    public function getTitleBgColor()
    {
        return $this->m_titleBgColor;
    }

    public function setTitleBgColor($newTitleBgColor)
    {
        $this->m_titleBgColor = $newTitleBgColor;
    }

	/**
	 * Obtem o tamanho de uma coluna na tabela.
	 * @param $row O numero da linha da célula.
	 * @param $col O numero da coluna da célula.
	 * @return int O tamanho da coluna.
	 */
	public function getColWidth($col)
	{
		if (!isset($this->m_colWidth[$col]))
		{
			return false;
		}
		return $this->m_colWidth[$col];
	}

    /**
	 * Altera o tamanho de uma coluna na tabela.
	 * @param $row O numero da linha da célula.
	 * @param $col O numero da coluna da célula.
	 * @param $width O tamanho da coluna.
	 * @return void
	 */
	public function setColWidth($col, $width)
	{
		$this->m_colWidth[$col] = $width;
	}

	public function setAlign($align)
	{
		$this->m_align = $align;
	}
	public function getAlign()
	{
		return $this->m_align;
	}
}
?>