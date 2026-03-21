<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogHelper
{
    public static function log($action, $description, $modelType = null, $modelId = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
