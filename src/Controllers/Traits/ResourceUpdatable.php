<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResourceUpdatable
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $collectionOrId, $id = null)
    {
        $this->checkModelAuthorization('update', 'update');

        try {
            if(is_null($id)) $id = $collectionOrId;

            $model = $this->model->find($id);
            if(is_null($model)) {
                $this->responder->set('message', 'Data not found');
                $this->responder->setStatus(404, 'Not Found');
                return $this->responder->response();
            }

            $validator = $this->model->validator($request, 'update', $id);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->set('message', $validator->errors()->first());
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }

            $fields = $request->only($model->getTableFields());
            foreach ($fields as $key => $value) {
                $model->setAttribute($key, $value);
            }
            $model->save();

            if(!$model->isDirty()) {
                $fields = $request->except($model->getTableFields());
                $triggered = isset($model->fileableEnabled) && $model->fileableEnabled;
                $triggered = $triggered || (isset($model->addressEnabled) && $model->addressEnabled);
                if($triggered) {
                    // FIXME: this lines of code below will not work
                    event('eloquent.updating: App\Models\\'.class_basename($model), $model);
                    event('eloquent.updated: App\Models\\'.class_basename($model), $model);
                }
            }

            $this->responder->set('message', 'Data updated');
            $this->responder->set('data', $model);
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}
