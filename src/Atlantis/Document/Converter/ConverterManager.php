<?php namespace Atlantis\Document\Converter;

use ArrayAccess;


class ConverterManager implements ArrayAccess{
    protected $app;
    protected $converters = [];


    public function __construct($app){
        $this->app = $app;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        #i: Get array by notation value
        $converter = array_get($this->converters,$offset);

        #i: Check array set or not
        return isset($converter);
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if( $this->offsetExists($offset) ){
            #i: Get converter from dot notation
            $converter = array_get($this->converters,$offset);

            #i: If return array (parent) get default converter by guessing
            if( gettype($converter) == 'array' ){
                #i: Get default converter from guessing
                $converter_default = $this->app['config']->get("content::document.default.converter.$offset");

                #i: Check converter exist
                if( $this->offsetExists("$offset.$converter_default") ){
                    return array_get($this->converters,"$offset.$converter_default");
                };

                return null;
            }else{
                return array_get($this->converters,$offset);
            }
        }
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if( $this->offsetExists($offset) ){
            array_set($this->converters,$offset,$value);
        }else{
            array_set($this->converters,$offset,$value);
        }
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->converters[$offset]);
    }


    public function extend($type,$name,Closure $callback){
        $this->offsetSet("$type.$name",$callback);
    }


    public function register($type,$name,$callback){
        $this->offsetSet("$type.$name",$callback);
    }


    function __invoke($name)
    {
        if( isset($name) ){
            return $this->offsetGet($name);
        }else{
            return $this;
        }
    }


}