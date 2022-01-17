<?php

namespace SaltLaravel\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Exceptions\UnauthorizedException;

use SaltLaravel\Models\Resources;
use SaltLaravel\Services\ResponseService;

class ApiResourcesNestedController extends ApiResourcesController
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Request $request, Resources $model, ResponseService $responder) {
        try {
            $this->responder = $responder;
            $this->segment = $request->segment(5);
            if($this->checkIfModelExist(Str::studly($this->segment))) {
                $this->model = $this->getModelClass(Str::studly($this->segment));
            } else {
                if($model->checkTableExists($this->segment)) {
                    $this->model = $model;
                    $this->model->setTable($this->segment);
                }
            }
            if($this->model) {
                $this->responder->set('collection', $this->model->getTable());
                // SET default Authentication
                $this->middleware('auth:api', ['only' => $this->model->getAuthenticatedRoutes()]);
            }

            if(is_null($this->table_name)) $this->table_name = $this->segment;
            $this->segments = $request->segments();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}
