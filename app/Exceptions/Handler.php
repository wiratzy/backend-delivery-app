<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        // Tangani ValidationException secara eksplisit
        if ($exception instanceof ValidationException) {
            Log::error('Validation failed', ['errors' => $exception->errors()]);
            return response()->json([
                'success' => false, 
                'message' => 'Validation failed: ' . $exception->getMessage(),
                'errors' => $exception->errors(),
            ], 422);
        }

        // Default ke handler Laravel bawaan untuk error lainnya
        return parent::render($request, $exception);
    }
}
