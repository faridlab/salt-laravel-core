<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResourceStorable
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->checkModelAuthorization('store', 'create');

        try {
            $validator = $this->model->validator($request);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->set('message', $validator->errors()->first());
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }
            $fields = $request->only($this->model->getTableFields());
            foreach ($fields as $key => $value) {
                $this->model->setAttribute($key, $value);
            }
            $this->model->save();
            $this->responder->set('message', Str::title(Str::singular($this->table_name)).' created!');
            $this->responder->set('data', $this->model);
            $this->responder->setStatus(201, 'Created.');
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}
