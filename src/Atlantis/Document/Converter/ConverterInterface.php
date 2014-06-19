<?php namespace Atlantis\Document\Converter;


interface ConverterInterface {

    public function loadFile($file);
    public function loadHTML($string);
    public function loadView($view, $data, $mergeData);
    public function save($filename);
    public function download($filename);
    public function stream($filename);
    //public function accepts();
    //public function converts();

}