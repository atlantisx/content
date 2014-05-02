<?php namespace Atlantis\Document;
/**
 * Part of the Atlantis package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Atlantis\Document
 * @version    1.0.0
 * @author     Nematix LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 1997 - 2013, Nematix LLC
 * @link       http://nematix.com
 */


use \Illuminate\View\Environment as BaseEnvironment;


class Environment extends BaseEnvironment {

    public function __construct(){
    }

    public function Processor($processor_name){
        try{
            #x: Processor name not provided exception
            if(empty($processor_name)) throw new Exception('Processor not defined!');

            #i: Sanitize processor name
            $processor_name = studly_case($processor_name);

            #i: Get processor instance
            $processor = \App::make('Atlantis\\Document\\'.$processor_name);

            #x: Processor instance not loaded exception
            if(empty($processor)) throw new Exception('Cannot load document processor!');

            return $processor;

        }catch(Exception $e){

        }
    }

}