<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;
use PDOException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        // Bắt và xử lý lỗi kết nối CSDL
        $this->renderable(function (QueryException $e, $request) {
            return response()->view('errors.db_connection', [], 500);
        });

        $this->renderable(function (PDOException $e, $request) {
            return response()->view('errors.db_connection', [], 500);
        });

        // Report lỗi khác nếu cần
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
