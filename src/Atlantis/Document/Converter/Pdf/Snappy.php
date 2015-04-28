<?php namespace Atlantis\Document\Converter\Pdf;
/**
 * A Atlantis wrapper for SnappyPDF
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Atlantis
 * @version    1.0.0
 * @author     Nematix LLC, Barry vd. Heuvel (Original)
 * @license    BSD License (3-clause)
 * @copyright  (c) 1997 - 2013, Nematix LLC
 * @link       http://nematix.com
 */

use Atlantis\Document\Converter\ConverterInterface;
use Atlantis\Document\Converter\ConverterFactory;
use Knp\Snappy\Pdf;


class Snappy extends ConverterFactory implements ConverterInterface{
    protected $snappy;
    protected $rendered = false;
    protected $options;
    protected $file_extension = 'pdf';


    public function __construct($app,$snappy=null){
        $binary = $app['config']->get('content::document.snappy.pdf.binary');
        $options = $app['config']->get('content::document.snappy.pdf.options');

        $this->snappy = new Pdf($binary,$options);
        $this->options = array();
    }


    /**
     * Set the paper size (default A4)
     *
     * @param string $paper
     * @param string $orientation
     * @return $this
     */
    public function setPaper($paper, $orientation=null){
        $this->snappy->setOption('page-size', $paper);
        if($orientation){
            $this->snappy->setOption('orientation', $orientation);
        }
        return $this;
    }


    /**
     * Set the orientation (default portrait)
     *
     * @param string $orientation
     * @return static
     */
    public function setOrientation($orientation){
        $this->snappy->setOption('orientation', $orientation);
        return $this;
    }


    /**
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function setOption($name, $value){
        $this->snappy->setOption($name, $value);
        return $this;
    }


    /**
     *
     * @param $options
     * @return $this
     */
    public function setOptions($options){
        $this->snappy->setOptions($options);
        return $this;
    }


    /**
     * Output the PDF as a string.
     *
     * @return string The rendered PDF as string
     */
    public function output(){
        $output = '';
        if($this->html){
            $output = $this->snappy->getOutputFromHtml($this->html, $this->options);
        }elseif($this->file){
            $output = $this->snappy->getOutput($this->file, $this->options);
        }
        return $output;
    }

    /**
     * Save the PDF to a file
     *
     * @param $filename
     * @return static
     */
    public function save($filename){

        if($this->html){
            $this->snappy->generateFromHtml($this->html, $filename, $this->options);
        }elseif($this->file){
            $this->snappy->generate($this->file, $filename, $this->options);
        }

        return $this;
    }



    public function __call($name, $arguments){
        return call_user_func_array (array( $this->snappy, $name), $arguments);
    }

}