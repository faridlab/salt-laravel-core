<?php

namespace SaltLaravel\Controllers\Traits;

use Illuminate\Http\Request;

trait ResourceIndexable
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request, $parentId = null) {

      $this->checkModelAuthorization('index', 'read');

      try {

          $count = $this->model->count();
          $model = $this->model->filter();

          if($this->is_nested === true) {
              if(is_null($this->parent_field)) {
                throw new \Exception('Please define $parent_field');
              }
              $count = $this->model->where($this->parent_field, $parentId)->count();
              $model = $this->model->where($this->parent_field, $parentId)->filter();
          }

          $format = $request->get('format', 'default');

          $limit = intval($request->get('limit', 25));
          if($limit > 100) {
              $limit = 100;
          }

          $p = intval($request->get('page', 1));
          $page = ($p > 0 ? $p - 1: $p);

          $modelCount = clone $model;
          $meta = array(
              'recordsTotal' => $count,
              'recordsFiltered' => $modelCount->count()?: $count
          );

          $data = $model
                      ->offset($page * $limit)
                      ->limit($limit)
                      ->get();

          $this->responder->set('message', 'Data retrieved.');
          $this->responder->set('meta', $meta);
          $this->responder->set('data', $data);

          return $this->responder->response();
      } catch(\Exception $e) {
          $this->responder->set('message', $e->getMessage());
          $this->responder->setStatus(500, 'Internal server error.');
          return $this->responder->response();
      }
  }

}
