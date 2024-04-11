<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ApiResponseClass
{
    public static function rollback($e, $message ="Something went wrong! Process not completed")
    {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message ="Something went wrong! Process not completed")
    {
        Log::info($e);
        
        return Inertia::render('BlogPost', ['message' => $message]);
    }

    public static function sendResponse($result)
    {

        return Inertia::render('BlogPost', $result);
    }

}
