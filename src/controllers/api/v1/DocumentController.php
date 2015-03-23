<?php

namespace Atlantis\Content\Api\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Atlantis\Core\Controller\BaseController;
use Atlantis\Document\Model\Document;


class DocumentController extends BaseController{

    /**
     * Index
     *
     * @return mixed
     */
    public function index(){
        return Document::all();
    }


    /**
     * Store document
     *
     * @return mixed
     */
    public function store(){
        $post = Input::all();

        try{
            $file = Input::file('qqfile');

            #i: Process document
            $document = new Document();
            $document->uuid = $post['qquuid'];
            $document->key = $post['document_key'];
            $document->image = $file;

            DB::beginTransaction();
            $document->save();
            DB::commit();

            #i: Notification
            $post['success'] = true;

        } catch(\Exception $e){
            DB::rollback();
            $post['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: Server response
        return Response::json($post);
    }


    /**
     * Document show
     *
     * @param $uuid
     * @return mixed
     * @throws \Exception
     */
    public function show($uuid){
        $get = Input::all();

        try{
            #i: Find document
            $document = Document::where('uuid','=',$uuid)->first();

            if($document){
                #i: If found get the image full url
                $get['image'] = $document->image->url();
                $get['_status'] = array(
                    'type' => 'success'
                );
                $document->increment('hit');

            }else{
                throw new \Exception('Document not found!');
            }

        } catch(\Exception $e){
            $get['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: Server response
        return Response::json($get);
    }


    /**
     * Document destroy
     *
     * @param $uuid
     * @return mixed
     */
    public function destroy($uuid){
        try{
            #i: Search for detail & delete all documents
            $document = Document::where('uuid','=',$uuid)->first();
            $document->delete();

            #i: Notification
            $delete['uuid'] = $uuid;
            $delete['_status'] = array(
                'type' => 'success',
                'message' => 'Sucessfully delete the document'
            );

        } catch(\Exception $e){
            $delete['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: Server response
        return Response::json($delete);
    }

}