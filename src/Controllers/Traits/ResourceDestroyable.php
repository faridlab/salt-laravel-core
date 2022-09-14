<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResourceDestroyable
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $collectionOrId, $idOrType = null)
    {
        $this->checkModelAuthorization('destroy', 'destroy');

        try {
            if(is_null($idOrType)) $idOrType = $collectionOrId;

            // Delete all selected IDs
            if($idOrType == "selected") {
                if($request->has('selected')) {
                    $ids = $request->get('selected');
                    $model = $this->model->whereIn('id', $ids);
                    if($model->count() < 1) {
                        $this->responder->set('message', 'Selected IDs not found');
                        $this->responder->setStatus(404, 'Not Found');
                        return $this->responder->response();
                    }
                    $model->delete();
                    $this->responder->set('message', 'Selected IDs are deleted');
                    $this->responder->set('data', $model);
                    return $this->responder->response();
                }

                $this->responder->set('message', "Selected IDs is required");
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }

            // Delete all selected
            if($idOrType == "all") {
                $model = $this->model->whereNull('deleted_at');
                if($model->count() < 1) {
                    $this->responder->set('message', 'There is not data found');
                    $this->responder->setStatus(404, 'Not Found');
                    return $this->responder->response();
                }
                $model->delete();
                $this->responder->set('message', 'All data are deleted');
                $this->responder->set('data', $model);
                return $this->responder->response();
            }

            $isUuid = Str::isUuid($idOrType);
            if(!$isUuid) {
              $this->responder->set('message', "Request method not defined");
              $this->responder->setStatus(400, 'Bad Request.');
              return $this->responder->response();
            }

            // Pointing to spesific data by ID
            $model = $this->model->find($idOrType);
            if(is_null($model)) {
                $this->responder->set('message', 'Data not found');
                $this->responder->setStatus(404, 'Not Found');
                return $this->responder->response();
            }
            $model->delete();
            $this->responder->set('message', 'Data deleted');
            $this->responder->set('data', $model);
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}
