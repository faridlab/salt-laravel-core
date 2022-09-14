<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;

trait ResourceExportable
{

    /**
     * Export data to CSV, EXCEL, etc (TBD)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, $collection)
    {
        if(is_null($this->model)) {
            $this->responder->set('message', "Model not found!");
            $this->responder->setStatus(404, 'Not found.');
            return $this->responder->response();
        }

        try {
            $this->authorize('export', $this->model);
        } catch (\Exception $e) {
            $this->responder->set('message', 'You do not have authorization.');
            $this->responder->setStatus(401, 'Unauthorized');
            return $this->responder->response();
        }

        try {
            $validator = Validator::make($request->all(), [
                'type' => 'nullable|string|in:csv,xlsx,sql',
                'columns' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->set('message', $validator->errors()->first());
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }

            $this->responder->set('message', 'Data exported.');
            $this->responder->set('data', []);
            return $this->responder->response();

        } catch(\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }
}
