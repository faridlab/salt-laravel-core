<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResourceTrashedable
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trashed(Request $request, $collectionOrId, $id = null)
    {
        $this->checkModelAuthorization('trashed', 'read');

        try {
            if(is_null($id)) $id = $collectionOrId;

            $data = $this->model->onlyTrashed()->find($id);
            if(is_null($data)) {
                $this->responder->set('message', 'Data not found');
                $this->responder->setStatus(404, 'Not Found');
                return $this->responder->response();
            }
            $this->responder->set('message', 'Data retrieved');
            $this->responder->set('data', $data);
            return $this->responder->response();
        } catch(\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}
