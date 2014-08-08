<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of pdf
 *
 * @author john
 */
class pdf   extends FPDF {
    function header() {
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0,0,0);
        $this->SetAutoPageBreak(TRUE,20);
        $this->Line($this->lMargin,(20),($this->w-$this->rMargin),(20));
        $this->SetY(14);
        $this->SetFont('Arial','I',6);
        $this->Cell(0,8,'Broadband Content Delivery System',0,0,'R');
    }

    function footer() {
        //Go to 1.5 cm from bottom
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0,0,0);
        $this->SetAutoPageBreak(TRUE,20);
        $this->Line($this->lMargin,($this->h-20),($this->w-$this->rMargin),($this->h-20));
        $this->SetY(-20);
        //Select Arial italic 8
        $this->SetFont('Arial','I',8);
        //Print centered page number
        $this->Cell(0,8,'hal.'.$this->PageNo().' dari {nb}',0,0,'R');
    }
}
?>
