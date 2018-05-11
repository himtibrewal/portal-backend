<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once FCPATH . "application/libraries/PHPExcel.php";

class Excel extends PHPExcel {

    public function __construct() {
        parent::__construct();
    }

    /** Write **/
    function column_range($lower, $upper) {
        ++$upper;
        $return = array();
        for ($i = $lower; $i !== $upper; ++$i) {
            array_push($return, $i);
        }

        return $return;
    }

    function set_cell_width_auto() {
        foreach ($this->column_range('A', $this->getActiveSheet()->getHighestDataColumn()) as $col) {
            $this->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
    }

    function set_cell_width($width_arr) {
        foreach ($this->column_range('A', $this->getActiveSheet()->getHighestDataColumn()) as $col) {
            $this->getActiveSheet()->getColumnDimension($col)->setWidth($width_arr[$col]);
        }
    }

    function excel_download($filename) {
        //$filename = $filename.'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');
        $objWriter->save('php://output');
    }

    function set_sheet_info($title = '') {
        $this->setActiveSheetIndex(0);
        $this->getActiveSheet()->setTitle($title);
    }

    function set_page_heading($title = '', $cell = 'A', $cellno = '1') {
        $this->getActiveSheet()->setCellValue($cell . $cellno, $str);
        $this->getActiveSheet()->getStyle($cell . $cellno)->getFont()->setSize(15)->setBold(TRUE);
        $this->getActiveSheet()->mergeCells("{$cell}{$cellno}:K{$cellno}");
    }

    function set_table_heading($thead, $cell = 'A', $cellno = '1') {
        $this->getActiveSheet()->fromArray($thead, null, $cell . $cellno);
        $highestCol = $this->getActiveSheet()->getHighestColumn();
        $this->getActiveSheet()->getStyle("{$cell}{$cellno}:{$highestCol}{$cellno}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('e3e3e3');
        $this->getActiveSheet()->getStyle("{$cell}{$cellno}:{$highestCol}{$cellno}")->getFont()->setBold(TRUE);
    }

    function set_table_data($data, $cell = 'A2') {
        $this->getActiveSheet()->fromArray($data, null, $cell);
    }

    function get_sheet($file) {
        try {
            $inputFileType = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($file);

            return $objPHPExcel->getSheet(0);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
    }

    /** Read **/
    function read($file = false, $sheet = false) {
        if ($file)
            $sheet = $this->get_sheet($file);
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();

        $rows = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            $r = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);
            if (isset($r[0])) {
                $r = $r[0];
                foreach ($r as $k => $v)
                    $r[$k] = trim(str_replace(' ', ' ', $v));
            }
            else
                $r = array();
            $rows[] = $r;
        }

        return $rows;
    }

    function get_column_size($sheet) {
        $highestColumn = $sheet->getHighestDataColumn();

        return PHPExcel_Cell::columnIndexFromString($highestColumn);
    }
}

/**
 * -:How to Use:-
 * //Write
 * $this->load->library('excel');
 * $this->excel->set_sheet_info('Sheet1');
 * $this->excel->set_table_heading($cols);
 * $this->excel->set_table_data($dataAr);
 * $this->excel->set_cell_width_auto();
 * $this->excel->excel_download("filename.xlsx");
 *
 * //Read
 * $this->load->library('excel');
 * $rows=$this->excel->read("filename.xlsx");
 **/
