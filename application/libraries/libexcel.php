<?php
class libexcel {
    private $data;
    private $numrows;
    private $fieldname;
    private $filename;

    function Libexcel($arrdata) {
        $this->data = @$arrdata['data'];
        $this->numrows = @$arrdata['numrows'];
        $this->caption = @$arrdata['caption'];
        $this->filename = @$arrdata['filename'];
        $this->fieldname = @$arrdata['fieldname'];
        $this->numberformat = @$arrdata['numberformat'];
        $this->export();
    }

    function xlsBOF() {
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        return;
    }

    function xlsEOF() {
        echo pack("ss", 0x0A, 0x00);
        return;
    }

    function xlsWriteNumber($Row, $Col, $Value) {
        echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
        echo pack("d", $Value);
        return;
    }

    function xlsWriteLabel($Row, $Col, $Value ) {
        $L = strlen($Value);
        echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        echo $Value;
        return;
    }

    function export() {

        $filename = $this->filename?$this->filename:"document";

        // Send Header

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        ;
        header("Content-Disposition: attachment;filename=".$filename.".xls");
        header("Content-Transfer-Encoding: binary ");

        // XLS Data Cell
        $this->xlsBOF();
        $z =0;
        foreach(@$this->caption as $caption) {
            $this->xlsWriteLabel(0,$z,$caption);
            $z++;
        }
        $x = 0;
        while($x < @$this->numrows) {
            $y=0;
            foreach(@$this->fieldname as $i=>$index) {
                if (count($this->numberformat)>0) {
                    if(!in_array(($i+1),$this->numberformat))
                        $this->xlsWriteLabel(($x+1),$y,$this->data[$x][$index]);
                    else
                        $this->xlsWriteNumber(($x+1),$y,$this->data[$x][$index]);
                } else {
                    $this->xlsWriteLabel(($x+1),$y,$this->data[$x][$index]);
                }
                $y++;
            }
            $x++;
        }
        $this->xlsEOF();
        exit();
    }

}
?>