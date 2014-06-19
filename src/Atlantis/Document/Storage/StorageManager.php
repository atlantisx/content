<?php namespace Atlantis\Document\Storage;

use ArrayAccess;


class StorageManager implements ArrayAccess{
    protected $app;
    protected $storages = [];


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
        $storage = array_get($this->storages,$offset);

        #i: Check array set or not
        return isset($storage);
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
            #i: Get storage from dot notation
            $storage = array_get($this->storages,$offset);

            #i: If return array (parent) get default converter by guessing
            if( gettype($storage) == 'array' ){
                #i: Get default storage from guessing
                $storage_default = $this->app['config']->get("content::document.default.storage.$offset");

                #i: Check storage exist
                if( $this->offsetExists("$offset.$storage_default") ){
                    return array_get($this->storages,"$offset.$storage_default");
                };

                return null;
            }else{
                return array_get($this->storages,$offset);
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
            array_set($this->storages,$offset,$value);
        }else{
            array_set($this->storages,$offset,$value);
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
        unset($this->storages[$offset]);
    }

    /**
     * Extend the storage using closure
     * @param $name <p>Storage name</p>
     * @param Closure $callback Provide storage class
     */
    public function extend($name,Closure $callback){
        $this->offsetSet($name,$callback);
    }


    /**
     * Registering storage extension
     * @param $name <p>Storage name</p>
     * @param $class <p>Provide storage class</p>
     */
    public function register($name,$class){
        $this->offsetSet($name,$class);
    }

    /**
     * Booting storage driver
     * @return void
     */
    public function boot(){
        foreach( $this->storages as $storage){
            $storage->boot();
        }
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