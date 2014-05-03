<?php namespace Atlantis\Document;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Atlantis\Core\Controller\BaseController;


class DocumentController extends BaseController {

    public function getIndex(){
        $pdf = App::make('document.pdf');

        $data = array(
            'full_name' => 'Azri Jamil',
            'idno_ic' => '810304115179',
            'full_address' => 'Azri Jamil<br>B-5-8 Plaza Mont Kiara,<br>50480 Kuala Lumpur',
            'offer_id' => '123456',
            'date' => Carbon::now()->toDateString(),
            'institution_name' => 'Universiti Islam Malaysia',
            'course_name' => 'Pengajian Syariah',
            'amount_text' => 'Dua Ribu Sahaja',
            'amount_value' => '2000'
        );

        $letter = $pdf->loadView('application.advance.documents.approval',$data);
        return $letter->download();
    }

}