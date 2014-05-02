<?php namespace Atlantis\Document\Facades\Laravel;

use Illuminate\Support\Facades\Facade as BaseFacade;


class ConverterPdf extends BaseFacade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'document.pdf.wrapper'; }


}