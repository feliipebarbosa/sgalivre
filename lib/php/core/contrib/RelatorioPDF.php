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
require_once('fpdf.php');

class PDFMTable extends FPDF
{
	private $fullWidth = 0;
	private $widths;
	private $aligns;
	
	public function PDFMTable()
	{
		parent::__construct('L', 'mm', 'a3');
	}
	
	function setFullWidth($fw) {
		$this->fullWidth = $fw;
		$this->fhPt = ($fw + $this->lMargin + $this->rMargin) * $this->k;
	}

	function getFullWidth() {
		return $this->fullWidth;
	}
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	public function getWidths()
	{
		return $this->widths;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data, $fillcolor, $fill, $cellColors)
	{
		//Calculate the height of the row
		$nb=0;
		$i = 0;
		foreach ($data as $value)
		{
			$nb=max($nb,$this->NbLines($this->widths[$i],$value));
			$i++;
		}
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);

		$i = 0;		
		foreach ($data as $value)
		{
			$this->SetFillColor($cellColors[$i][0], $cellColors[$i][1], $cellColors[$i][2]);
		    $w=$this->widths[$i];
		    $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		    //Save the current position
		    $x=$this->GetX();
		    $y=$this->GetY();
		    
		    // always filled
			$style = 'DF';
			
		    //Draw the border
		    $this->Rect($x,$y,$w,$h, $style);
		    //Print the text
		    $this->MultiCell($w,5,$value,0,$a);
		    //Put the position to the right of the cell
		    $this->SetXY($x+$w,$y);
			$i++;
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
		    $this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
		    $w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
		    $nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
		    $c=$s[$i];
		    if($c=="\n")
		    {
		        $i++;
		        $sep=-1;
		        $j=$i;
		        $l=0;
		        $nl++;
		        continue;
		    }
		    if($c==' ')
		        $sep=$i;
		    $l+=$cw[$c];
		    if($l>$wmax)
		    {
		        if($sep==-1)
		        {
		            if($i==$j)
		                $i++;
		        }
		        else
		            $i=$sep+1;
		        $sep=-1;
		        $j=$i;
		        $l=0;
		        $nl++;
		    }
		    else
		        $i++;
		}
		return $nl;
	}
}

class RelatorioPDF extends Relatorio
{

	private function getMaxTextWidth($fpdf, $tabela)
	{
		$maxWidths = array();
		$mstr = array();
		
		foreach ($tabela->getData() as $row)
		{
			//
			$i = 0;
			foreach ($row as $cell)
			{
				
				$w = 0;
				$cell = $this->relstrip($cell);
				if (strpos($cell, "\n") !== FALSE)
				{
					$lines = explode("\n", $cell);
					foreach ($lines as $ln)
					{
						$tmp =  $fpdf->GetStringWidth($ln);
						if ($tmp > $w)
						{
							$w = $tmp;
						}
					}
				}
				else
				{
					$w =  $fpdf->GetStringWidth((string) $cell);
					
				}
				
				if (!isset($maxWidths[$i]))
				{
					$maxWidths[$i] = $w + 10;
					$mstr[$i] = $cell;
				}
				else if ($w > $maxWidths[$i])
				{
					$maxWidths[$i] = $w + 10;
					$mstr[$i] = $cell;
				}
				
				$i++;
			}
		}
		return $maxWidths;
	}

	public function html2rgb($color)
	{
		if ($color[0] == '#')
		    $color = substr($color, 1);

		if (strlen($color) == 6)
		    list($r, $g, $b) = array($color[0].$color[1],
		                             $color[2].$color[3],
		                             $color[4].$color[5]);
		elseif (strlen($color) == 3)
		    list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
		    return false;

		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

		return array($r, $g, $b);
	}

    public function output()
	{
		$pdf = new PDFMTable();
		$pdf->AddPage('L');
		$pdf->SetFont('Arial', 'b', 18);
		$pdf->Image('themes/sga.default/imgs/logo_horizontal_simples2.jpg', $pdf->lMargin, $pdf->tMargin);
		$pdf->Write(7, ' ');
		
		$pdf->Text($pdf->lMargin + 130, 20, iconv('utf-8' ,'iso-8859-1', $this->getTitulo()));
		
		$pdf->SetFont('Arial', '', 12);
		$pdf->Text($pdf->lMargin + 130, 25, iconv('utf-8' ,'iso-8859-1', $this->getSubTitulo()));
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		
		foreach ($this->getComponentes() as $componente)
		{
			if ($componente instanceof Tabela)
			{
				$this->display_table($pdf, $componente);
			}
			else if ($componente instanceof Separador)
			{
				$this->display_separador($pdf);
			}
		}

		$pdf->Output('relatorio.pdf', 'D');
	}
	
	public function display_table(PDFMTable $pdf, Tabela $tabela) {
		
		//Header
		$w = $this->getMaxTextWidth($pdf, $tabela);
		
		
		$fullwidth = array_sum($w);
		if ($fullwidth > $pdf->getFullWidth()) {
			$pdf->setFullWidth($fullwidth);
		}
		
		//Color and font restoration
		$pdf->SetFillColor(224, 235, 255);
		$defaultFillColor = array(224, 235, 255);
		$defaultTextColor = 0;
		$pdf->SetTextColor($defaultTextColor);
		
		// Titulo
		$pdf->SetWidths(array($fullwidth));
		$pdf->Row(array(iconv('utf-8' ,'iso-8859-1', $this->relstrip($tabela->getTitulo()))), $fillcolor, true, array(array(0xCC, 0xCC, 0xCC)));
		
		//Data
		$pdf->SetWidths($w);
		
		$fill = 0;
		$size = count($body);
		$i = 0;
		foreach ($tabela->getData() as $row)
		{
			$fillcolor = $defaultFillColor;
			$j = 0;
			$cellColors = array();			
			foreach ($row as $key => $value)
			{
				$color = $tabela->getCellBgColor($i, $j);
				if ($color) {
					$cellColors[$j] = $color;
				}
				else {
					 $cellColors[$j] = array(0xFF, 0xFF, 0xFF);
				}
				$row[$key] = iconv('utf-8' ,'iso-8859-1', $this->relstrip($value));
				$j++;
			}
			$pdf->Row($row, $fillcolor, $fill, $cellColors);
		    $fill = !$fill;
		    
		    $i++;
		}
	}
	
	public function display_separador(PDFMTable $pdf) {
		$pdf->Ln();
		$pdf->Ln();
	}
}
?>
