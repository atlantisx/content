<?php

namespace Atlantis\Document\Converter;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;


class ConverterFactory {

    protected $file_extension = 'txt';

    protected $data;


    /**
     * Load a HTML string
     *
     * @param string $string
     * @return static
     */
    public function loadHTML($string){
        $this->html = (string) $string;
        $this->file = null;

        return $this;
    }


    /**
     * Load a HTML file
     *
     * @param string $file
     * @return static
     */
    public function loadFile($file){
        $this->html = null;
        $this->file = $file;

        return $this;
    }


    /**
     * Load a View and convert to HTML
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return static
     */
    public function loadView($view, $data = array(), $mergeData = array()){
        $this->data = $data;

        /** Create view */
        $this->html = View::make($view, $data, $mergeData)->render();
        $this->file = null;

        return $this;
    }


    /**
     * Show or hide warnings
     *
     * @param bool $warnings
     * @return $this
     * @deprecated
     */
    public function setWarnings($warnings){
        //Doesn't do anything
        return $this;
    }


    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download($filename = 'document'){
        $output = $this->output();

        $filename = $this->interpolate($filename);

        return Response::make($output, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  "attachment; filename=\"{$filename}.{$this->file_extension}\""
        ));
    }


    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stream($filename = 'document' ){
        $that = $this;

        $filename = $this->interpolate($filename);

        return Response::stream(function() use($that){
            echo $that->output();

        }, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  "inline; filename=\"{$filename}.{$this->file_extension}\""
        ));
    }


    /**
     * String interpolation
     *
     * @param $subject
     * @return mixed
     */
    private function interpolate($subject){
        $data = $this->data;

        /** Find all prefix in value string */
        preg_match_all('/[:]\w+/',$subject,$matches);

        /** Replace all founded */
        array_walk($matches[0], function($item) use($data, &$subject){
            if( isset($data[ltrim($item,':')]) ) $subject = str_replace($item, $data[ltrim($item,':')], $subject);
        });

        return $subject;
    }

}