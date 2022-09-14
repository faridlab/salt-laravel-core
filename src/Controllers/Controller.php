<?php

namespace SaltLaravel\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use SaltLaravel\Models\Resources;
use SaltLaravel\Services\ResponseService;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $table_name = null;
    protected $modelNamespace = 'App';
    protected $model = null;
    protected $segments = [];
    protected $segment = null;
    protected $responder = null;

    public $response = array();

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Request $request, Resources $model, ResponseService $responder) {
        try {
            $this->responder = $responder;
            $this->segment = $request->segment(3);
            if($this->checkIfModelExist(Str::studly($this->segment), $this->modelNamespace)) {
                $this->model = $this->getModelClass(Str::studly($this->segment), $this->modelNamespace);
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

    protected function checkPermissions($authenticatedRoute, $authorize) {
        if(in_array($authenticatedRoute, $this->model->getAuthenticatedRoutes())) {
            $table = $this->model->getTable();
            $generatedPermissions = [$table.'.*.*', $table.'.'.$authorize.'.*'];
            $defaultPermissions = $this->model->getPermissions($authorize);
            $permissions = array_merge($generatedPermissions, $defaultPermissions);
            $user = Auth::user();
            $can = $table.'.'.$authorize.'.*';
            // dd($user->getAllPermissions()->whereIn('name', $permissions)->count());
            $hasPermission = (boolean) $user->getAllPermissions()->filter(function ($item) use ($can) {
              $pttrn = Str::replace('*', '(.*)', Str::replace('.', '\.', $item->name));
              return preg_match("/{$pttrn}/", $can);
            })->count();
            // if(!$user->hasAnyPermission($permissions)) {
            if(!$hasPermission) {
                throw new \Exception('You do not have authorization.');
            }
        }
    }

    protected function checkModelAuthorization($authenticatedRoute, $authorize) {

        if(is_null($this->model)) {
            $this->responder->set('message', "Model not found!");
            $this->responder->setStatus(404, 'Not found.');
            return $this->responder->response();
        }

        try {
            $this->checkPermissions($authenticatedRoute, $authorize);
        } catch (\Exception $e) {
            $this->responder->set('message', 'You do not have authorization.');
            $this->responder->setStatus(401, 'Unauthorized');
            return $this->responder->response();
        }
    }

    protected function checkIfModelExist($model, $namespace = 'App') {
        try {
            $model = app("{$namespace}\Models\\{$model}");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getModelClass($model, $namespace = 'App') {
        try {
            $model = app("{$namespace}\Models\\{$model}");
            return $model;
        } catch (\Exception $e) {
            return null;
        }
    }
}
