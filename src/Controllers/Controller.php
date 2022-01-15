<?php

namespace SaltLaravel\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
