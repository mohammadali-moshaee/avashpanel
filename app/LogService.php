<?php

namespace App;
use App\Models\Admin\Log;

class LogService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function createLog($action, $description, $model = null)
    {
        $userid;
        
        if($model == 'danger login'){
            $userid = null;
        }else{
            $userid = Auth()->user()->id;
        }
        
        Log::create([
            'action' => $action,
            'description' => $description,
            'user_id' => $userid,
            'ip_address' => request()->ip(),
            'model_type' => is_object($model) ? get_class($model) : (is_string($model) ? $model : null),
            'model_id' => is_object($model) && method_exists($model, 'getKey') ? $model->getKey() : null,
        ]);
    }
}
