<?php namespace Atlantis\Document\Converter\Image;
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

use Knp\Snappy\Image;


class Snappy {
    protected $snappy;

    public function __construct($app){
        $binary = \App::make('config')->get('content::document.snappy.image.binary');
        $options = \App::make('config')->get('content::document.snappy.image.options');

        $this->snappy = new Image($binary,$options);
    }

    public function __call($name, $arguments){
        return call_user_func_array (array( $this->snappy, $name), $arguments);
    }

}