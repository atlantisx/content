<?php

namespace Atlantis\Document\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;


class Document extends Eloquent implements StaplerableInterface {

    /** Traits */
    use EloquentTrait;
    use SoftDeletingTrait;

    /** @var array Date attributes */
    protected $dates = ['deleted_at'];
    public $timestamps = false;


    /**
     * Constructor
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = array()){
        $this->hasAttachedFile('image',[
            'styles' => [
                'thumb' => '100x100#'
            ]
        ]);

        parent::__construct($attributes);
    }

}