<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResourceRestorable
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $collectionOrId, $id = null)
    {
        $this->checkModelAuthorization('restore', 'restore');

        try {
            if(is_null($id)) $id = $collectionOrId;

            // FIXME: if condition depth only 2
            if($id == "selected") { // Delete all selected IDs
                if($request->has('selected')) {
                    $ids = $request->get('selected');
                    $model = $this->model->onlyTrashed()->whereIn('id', $ids);
                    if($model->count() < 1) {
                        $this->responder->set('message', 'Selected IDs not found');
                        $this->responder->setStatus(404, 'Not Found');
                        return $this->responder->response();
                    }
                    $model->restore();
                    $this->responder->set('message', 'Selected IDs are restored');
                    $this->responder->set('data', $model);
                    return $this->responder->response();
                }
                $this->responder->set('message', "Selected IDs is required");
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }

            if($id == "all") { // Delete all selected
                $model = $this->model->onlyTrashed();
                if($model->count() < 1) {
                    $this->responder->set('message', 'There is not data found');
                    $this->responder->setStatus(404, 'Not Found');
                    return $this->responder->response();
                }
                $model->restore();
                $this->responder->set('message', 'All data are restored');
                $this->responder->set('data', $model);
                return $this->responder->response();
            }

            $isUuid = Str::isUuid($id);
            if(!$isUuid) {
              $this->responder->set('message', "Request method not defined");
              $this->responder->setStatus(400, 'Bad Request.');
              return $this->responder->response();
            }

            // Pointing to spesific data by ID
            $data = $this->model->onlyTrashed()->find($id);
            if(is_null($data)) {
                $this->responder->set('message', 'Data not found');
                $this->responder->setStatus(404, 'Not Found');
                return $this->responder->response();
            }
            $data->restore();
            $this->responder->set('message', 'Data restored');
            $this->responder->set('data', $data);
            return $this->responder->response();
        } catch(\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}
