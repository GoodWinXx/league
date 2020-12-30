<?php
namespace app\common\library\excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel
{

    protected $excel;

    public function __construct()
    {
        $this->excel = new Spreadsheet;
    }

    public function export($filename, $headers, $data, $keys, $width = 20, $savePath='')
    {
        $filename = iconv("utf-8","gb2312//TRANSLIT",$filename);
        //保证titles为从0开始的数组索引
        foreach ($headers as $k=>$v) {
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

            // $v = iconv('UTF-8', 'GBK//IGNORE', $v);
            if (strpos($v,':')) {
                $arr = explode(':',$v);
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$arr[0]);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($arr[1]);
            } else {
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$v);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($width);
            }

        }

        $data = array_values($data);
        foreach ($data as $key => $value) {
            $k = 0;
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

            // $this->excel->getActiveSheet()->getStyle($cellOffset)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            foreach ($keys as $val) {
                $integer = floor($k / 26);
                $remainder = $k % 26;
                $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

                if (strpos($val,':')) {
                    $var_arr = explode(':',$val);
                    $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($key+2), $value[$var_arr[0]],\PHPExcel_Cell_DataType::TYPE_STRING);
                    if ($var_arr[1] == 'wrap') {
                        $this->excel->getActiveSheet()->getStyle($cellOffset.($key+2))->getAlignment()->setWrapText(true);
                    }
                    // $this->excel->getActiveSheet()->getStyle($cellOffset.($key+2))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                } else {
                    $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($key+2),$value[$val]);
                }
                $k++;
            }
        }

        $this->excel->getActiveSheet()->setTitle('Simple');
        $this->excel->setActiveSheetIndex(0);





        $objWriter = IOFactory::createWriter($this->excel, 'Xls');

        if ($savePath === '') {
            // ob_end_clean();//添加否则会乱码
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            $objWriter->save('php://output');
            exit;
        } else {
            if (ob_get_length() > 0) {
                ob_end_clean();//添加否则会乱码
            }
            $filename = iconv("gb2312","utf-8//TRANSLIT",$filename);
            $objWriter->save($savePath.'/'.$filename);
            return $savePath.'/'.$filename;
        }
    }

    /**
     * 带合并单元格的导出
     * @param  [type]  $filename [description]
     * @param  [type]  $header   [description]
     * @param  [type]  $data     [description]
     * @param  [type]  $keys     [description]
     * @param  integer $width    [description]
     * @param  integer $is_merge    [默认列是否不合并]
     * @param  [type]  $savePath     [description]
     * @return [type]            [description]
     */
    public function multiExport($filename, $header, $data, $keys, $is_merge = 0, $width = 20, $savePath='')
    {
        $filename = iconv("utf-8","gb2312",$filename);
        $this->excel->setActiveSheetIndex(0);
        foreach ($header as $k => $v) {
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

            if (strpos($v,':')) {
                $arr = explode(':',$v);
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$arr[0]);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($arr[1]);
            } else {
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$v);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($width);
            }
        }
        $data = array_values($data);
        $hb_rows = 0;//被合并的行数
        foreach ($data as $key => $value) {
            $k = 0;
            $kk = $key + $hb_rows;
            $is_turn_hb = 0;//本轮是否进行了合并
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
            $is_hb = $value['merge_num'] ?? 0;//单元格合并行数

            $this->excel->getActiveSheet()->getStyle($cellOffset)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            foreach ($keys as $val) {
                $integer = floor($k / 26);
                $remainder = $k % 26;
                $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                $no_merge = $is_merge;//是否本列不进行合并

                if (strpos($val,':')) {
                    $var_arr = explode(':',$val);
                    if($var_arr[1] == 'arr'){
                        $need_arr = $value[$var_arr[0]];
                        foreach ($need_arr as $kn => $vn) {
                            $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($kk+2+$kn), $vn);
                        }
                        $no_merge = $is_merge > 0 ? 0 : 1;
                    }elseif ($var_arr[1] == 'arr_num') {
                        $need_arr = $value[$var_arr[0]];
                        foreach ($need_arr as $kn => $vn) {
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2+$kn), $vn,\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        }
                        $no_merge = $is_merge > 0 ? 0 : 1;
                    }elseif ($var_arr[1] == 'arr_str') {
                        $need_arr = $value[$var_arr[0]];
                        foreach ($need_arr as $kn => $vn) {
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2+$kn), $vn,\PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                        $no_merge = $is_merge > 0 ? 0 : 1;
                    }elseif ($var_arr[1] == 'wrap') {
                        $this->excel->getActiveSheet()->getStyle($cellOffset.($kk+2))->getAlignment()->setWrapText(true);
                    }elseif($var_arr[1] == 'merge' && $is_hb > 1){
                        //记录下要合并的区域,所有列表完成后再进行合并
                        $merge_arr[] = $cellOffset.($kk+2).':'.$cellOffset.($kk+2+$is_hb-1);
                        $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($kk+2),$value[$var_arr[0]]);
                    }
                    else{
                        if(isset($var_arr[2]) && $var_arr[2]=="num"){
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2), $value[$var_arr[0]],\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } else{
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2), $value[$var_arr[0]],\PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                    }
                } else {
                    $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($kk+2),$value[$val]);
                }
                $k++;
                if ($is_hb > 1 && !$no_merge) {
                    $this->excel->setActiveSheetIndex(0)->mergeCells($cellOffset.($kk+2).':'.$cellOffset.($kk+2+$is_hb-1));
                    $is_turn_hb = 1;
                }
            }
            $is_turn_hb && $hb_rows = $hb_rows + $is_hb - 1 ;//此轮进行过合并,记录被合并的行数
        }
        if (isset($merge_arr)) {
            foreach ($merge_arr as $key => $value) {
                $this->excel->setActiveSheetIndex(0)->mergeCells($value);
            }
        }


        if ($savePath === '') {
            ob_end_clean();//添加否则会乱码
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        } else {
            if (ob_get_length() > 0) {
                ob_end_clean();//添加否则会乱码
            }
            $filename = iconv("gb2312","utf-8",$filename);
            $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save($savePath.'/'.$filename);
            return $savePath.'/'.$filename;
        }
    }

    /**
     * 导出二级表头xls
     * @auther   chenmy
     * @DateTime 2018-06-13
     * @param    [type]     $header   [表头: 关联数组或索引数组]
     * @param    [type]     $data     [导出数据]
     * @param    [type]     $filename [文件名]
     * @param    array      $property [excel属性]
     * @param    string     $savePath [保存路径]
     * @return   [type]               [description]
     */
    public function exportMoreTitle($header, $data, $filename, $property=[], $savePath = '')
    {
        $filename = iconv("utf-8","gb2312",$filename);
        $this->excel->getProperties();
        isset($property['creator']) && $this->excel->setCreator($property['creator']);
        isset($property['lastModifiedBy']) && $this->excel->setCreator($property['lastModifiedBy']);
        isset($property['title']) && $this->excel->setCreator($property['title']);
        isset($property['subject']) && $this->excel->setCreator($property['subject']);
        isset($property['description']) && $this->excel->setCreator($property['description']);
        isset($property['keywords']) && $this->excel->setCreator($property['keywords']);
        isset($property['category']) && $this->excel->setCreator($property['category']);
        $this->excel->setActiveSheetIndex(0);

        $i = 0;
        $data_keys = [];
        $depth = $this->array_depth($header);
        if ($depth != 1) {
            $depth -= 1;
        }
        foreach ($header as $key => $val) {
            $integer = floor($i / 26);
            $remainder = $i % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
            if (!is_array($val)) {
                $data_keys[] = $key;
                if ($depth != 1) {
                    $this->excel->getActiveSheet()->mergeCells($cellOffset.'1:'.$cellOffset.($depth-1))->setCellValue($cellOffset.'1', $val);
                } else {
                    $this->excel->getActiveSheet()->setCellValue($cellOffset.'1', $val);
                }
                $i++;
            } else {
                $arr_length = count($val['child']) - 1;
                $from = $cellOffset.'1';
                $integer = floor(($i + $arr_length) / 26);
                $remainder = ($i + $arr_length) % 26;
                $to = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                $this->excel->getActiveSheet()->mergeCells($from.':'.$to.'1')->setCellValue($from, $val['title']);
                $j = 0;
                foreach ($val['child'] as $ch_key => $ch_val) {
                    if(isset($ch_val['child'])){
                        $integer = floor(($i + $j) / 26);
                        $remainder = ($i + $j) % 26;
                        $from_cell = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                        $arr_length = count($ch_val['child']) - 1;
                        $integer = floor(($i + $arr_length) / 26);
                        $remainder = ($i + $arr_length) % 26;
                        $to_cell = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                        $this->excel->getActiveSheet()->mergeCells($from_cell.'2'.':'.$to_cell.'2')->setCellValue($from_cell.'2', $ch_val['title']);
                        $j++;
                        $k = $j-1;
                        foreach ($ch_val['child'] as $c_key => $c_val) {
                            $data_keys[] = $c_key;
                            $integer = floor(($i + $k) / 26);
                            $remainder = ($i + $k) % 26;
                            $k_cell = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                            $this->excel->getActiveSheet()->setCellValue($k_cell.'3', $c_val);
                            $k++;
                        }
                        $i = $i + $arr_length;
                    }else{
                        $data_keys[] = $ch_key;
                        $integer = floor(($i + $j) / 26);
                        $remainder = ($i + $j) % 26;
                        $j_cell = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                        $this->excel->getActiveSheet()->mergeCells($j_cell.'2:'.$j_cell.'3')->setCellValue($j_cell.'2', $ch_val);
                        $j++;
                    }
                }
                $i = $i + $arr_length + 1;
            }
            $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth(20);
        }
        // pd($data_keys);
        // pd($data,false);
        if ($this->isAssocArray($header)) {
            $items = [];
            foreach ($data as $d) {
                $item = [];
                foreach ($data_keys as $k) {
                    $item[] = isset($d[$k]) ? $d[$k] : '-';
                }
                $items[] = $item;
            }
        } else {
            $items = $data;
        }
        // pd($items);
        $this->excel->getActiveSheet()->fromArray($items, null, 'A4', true);
        if ('' == $savePath) {
            ob_end_clean();//添加否则会乱码
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        } else {
            if (ob_get_length() > 0) {
                ob_end_clean();//添加否则会乱码
            }
            $filename = iconv("gb2312","utf-8",$filename);
            $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save($savePath.'/'.$filename);
            return $savePath.'/'.$filename;
        }

    }

    /**
     * 获取数组维度
     * @auther   chenmy
     * @DateTime 2018-06-15
     * @param    [type]     $array [description]
     * @return   [type]            [description]
     */
    public function array_depth($array) {
        if(!is_array($array)) return 0;
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::array_depth($value) + 1;
                $max_depth = max($depth, $max_depth);
            }
        }
        return $max_depth;
    }

    public function isAssocArray(array $var)
    {
        return array_diff_assoc(array_keys($var), range(0, sizeof($var))) ? TRUE : FALSE;
    }

    public function exportOss($filename,$headers,$data,$keys, $width = 20)
    {
        $filename = iconv("utf-8","gb2312",$filename);
        //保证titles为从0开始的数组索引
        foreach ($headers as $k=>$v) {
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

            // $v = iconv('UTF-8', 'GBK//IGNORE', $v);
            if (strpos($v,':')) {
                $arr = explode(':',$v);
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$arr[0]);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($arr[1]);
            } else {
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$v);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($width);
            }

        }

        $data = array_values($data);
        foreach ($data as $key => $value) {
            $k = 0;
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

            $this->excel->getActiveSheet()->getStyle($cellOffset)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            foreach ($keys as $val) {
                $integer = floor($k / 26);
                $remainder = $k % 26;
                $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

                if (strpos($val,':')) {
                    $var_arr = explode(':',$val);
                    $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($key+2), $value[$var_arr[0]],\PHPExcel_Cell_DataType::TYPE_STRING);
                    if ($var_arr[1] == 'wrap') {
                        $this->excel->getActiveSheet()->getStyle($cellOffset.($key+2))->getAlignment()->setWrapText(true);
                    }
                    // $this->excel->getActiveSheet()->getStyle($cellOffset.($key+2))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                } else {
                    $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($key+2),$value[$val]);
                }
                $k++;
            }
        }/*var_dump("123");exit;*/
        /*        $this->excel->getActiveSheet()->setTitle('Simple');
                $this->excel->setActiveSheetIndex(0);*/
        /*ob_end_clean();//添加否则会乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public'); */
        $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $res =  $objWriter->save("/var/www/asus_xigoubao_com/backend/runtime/".$filename . "/".time().".xls");

        return true;
        exit;
    }

    /**
     * 带合并单元格的导出
     * @param  [type]  $filename [description]
     * @param  [type]  $header   [description]
     * @param  [type]  $data     [description]
     * @param  [type]  $keys     [description]
     * @param  integer $width    [description]
     * @param  integer $is_merge    [默认列是否不合并]
     * @return [type]            [description]
     */
    public function multiExportOss($filename, $header, $data, $keys, $is_merge = 0, $width = 20)
    {
        $filename = iconv("utf-8","gb2312",$filename);
        $this->excel->setActiveSheetIndex(0);
        foreach ($header as $k => $v) {
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);

            if (strpos($v,':')) {
                $arr = explode(':',$v);
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$arr[0]);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($arr[1]);
            } else {
                $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.'1',$v);
                $this->excel->getActiveSheet()->getColumnDimension($cellOffset)->setWidth($width);
            }
        }
        $data = array_values($data);
        $hb_rows = 0;//被合并的行数
        foreach ($data as $key => $value) {
            $k = 0;
            $kk = $key + $hb_rows;
            $is_turn_hb = 0;//本轮是否进行了合并
            $integer = floor($k / 26);
            $remainder = $k % 26;
            $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
            $is_hb = $value['merge_num'] ?? 0;//单元格合并行数

            $this->excel->getActiveSheet()->getStyle($cellOffset)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            foreach ($keys as $val) {
                $integer = floor($k / 26);
                $remainder = $k % 26;
                $cellOffset = $integer ? chr($integer+64).chr($remainder+64+1) : chr($remainder+64+1);
                $no_merge = $is_merge;//是否本列不进行合并

                if (strpos($val,':')) {
                    $var_arr = explode(':',$val);
                    if($var_arr[1] == 'arr'){
                        $need_arr = $value[$var_arr[0]];
                        foreach ($need_arr as $kn => $vn) {
                            $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($kk+2+$kn), $vn);
                        }
                        $no_merge = $is_merge > 0 ? 0 : 1;
                    }elseif ($var_arr[1] == 'arr_num') {
                        $need_arr = $value[$var_arr[0]];
                        foreach ($need_arr as $kn => $vn) {
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2+$kn), $vn,\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        }
                        $no_merge = $is_merge > 0 ? 0 : 1;
                    }elseif ($var_arr[1] == 'arr_str') {
                        $need_arr = $value[$var_arr[0]];
                        foreach ($need_arr as $kn => $vn) {
                            $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2+$kn), $vn,\PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                        $no_merge = $is_merge > 0 ? 0 : 1;
                    }elseif ($var_arr[1] == 'wrap') {
                        $this->excel->getActiveSheet()->getStyle($cellOffset.($kk+2))->getAlignment()->setWrapText(true);
                    }elseif($var_arr[1] == 'merge' && $is_hb > 1){
                        //记录下要合并的区域,所有列表完成后再进行合并
                        $merge_arr[] = $cellOffset.($kk+2).':'.$cellOffset.($kk+2+$is_hb-1);
                        $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($kk+2),$value[$var_arr[0]]);
                    }else{
                        $this->excel->setActiveSheetIndex(0)->setCellValueExplicit($cellOffset.($kk+2), $value[$var_arr[0]],\PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                } else {
                    $this->excel->setActiveSheetIndex(0)->setCellValue($cellOffset.($kk+2),$value[$val]);
                }
                $k++;
                if ($is_hb > 1 && !$no_merge) {
                    $this->excel->setActiveSheetIndex(0)->mergeCells($cellOffset.($kk+2).':'.$cellOffset.($kk+2+$is_hb-1));
                    $is_turn_hb = 1;
                }
            }
            $is_turn_hb && $hb_rows = $hb_rows + $is_hb - 1 ;//此轮进行过合并,记录被合并的行数
        }
        /*if (isset($merge_arr)) {
            foreach ($merge_arr as $key => $value) {
                $this->excel->setActiveSheetIndex(0)->mergeCells($value);
            }
        }

        ob_end_clean();//添加否则会乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        exit;*/
        $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $res =  $objWriter->save("/var/www/asus_xigoubao_com/backend/runtime/".$filename . "/".time().".xls");

        return true;
        exit;
    }
}