<?php

namespace App\Observers;
use SaltLaravel\Models\Users;
use Illuminate\Support\Facades\Auth;
use SaltLaravel\Events\UserActivate;

class UsersObserver extends Observer
{

    public function creating($model) {
        $model['password'] = bcrypt($model->password);
    }

    public function updating($model) {
        // if($model->isDirty('is_active') && $model->is_active == 1) {
        //     $user = Auth::user();
        // }
    }

    public function updated($model) {
        // if($model->isDirty('is_active') && $model->is_active == 1) {

        // }
    }

}
