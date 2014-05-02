<?php namespace Atlantis\Document;

use Illuminate\Support\Facades\App;


class DocumentController extends \Atlantis\Admin\BaseController {
    protected $layout = 'admin::layouts.common';

    public function getIndex(){
        $pdf = App::make('document.pdf');

        $data = array(
            'full_name' => 'Azri Jamil',
            'idno_ic' => '810304115179',
            'full_address' => '',
            'offer_id' => '123456',
            'date' => '',
            'institution_name' => '',
            'course_name' => '',
            'amount_text' => '',
            'amount_value' => ''
        );

        $letter = $pdf->loadView('application.advance.documents.approval',$data);
        return $letter->download();
    }

}