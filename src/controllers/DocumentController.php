<?php namespace Atlantis\Document;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Atlantis\Core\Controller\BaseController;


class DocumentController extends BaseController {

    public function getDownload(){
        $get = \Input::all();

        $name = 'transform'.studly_case($get['name']);
        return $this->{$name}($get['uuid']);
    }


    protected function transformApproval($uuid){
        $record = \Record::find($uuid);

        if($record){
            $converter = \App::make('document.converter');

            #i: Fetch and construct data from record
            $address_state = \Code::category('state')->where('name',$record->user->profile->address_state)->first()->value;
            $data = array(
                'full_name' => $record->user->full_name,
                'idno_ic' => $record->user->profile->idno_ic,
                'full_address' => "{$record->user->full_name}<br>{$record->user->profile->address_street}<br>{$record->user->profile->address_postcode}, {$address_state}",
                'offer_id' => '',
                'institution_name' => $record->institution_name,
                'course_name' => $record->application_coursed,
                'amount_text' => $record->amount_approved_text,
                'amount_value' => $record->amount_approved,
                'date' => $record->updated_at->toDateString()
            );

            $letter = $converter('pdf.snappy')->loadView('advance::documents.approval',$data);
            return $letter->download();
        }
    }
}