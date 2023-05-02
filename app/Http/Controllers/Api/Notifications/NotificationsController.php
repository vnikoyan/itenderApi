<?php

namespace App\Http\Controllers\Api\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AbstractController;
use App\Models\User\User;

class NotificationsController extends AbstractController
{
    public function getNotifications(){
        return auth('api')->user()->notifications;
    }

    public function read(string $id){
        $notification = auth('api')->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }
        return $this->respondWithStatus(true);
    }

    public function deleteAll(){
        auth('api')->user()->notifications()->delete();
        return $this->respondWithStatus(true);
    }
    
}
