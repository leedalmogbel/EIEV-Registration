<?php

namespace App\Exceptions;

use Exception;

class TestException extends Exception
{
    //
    public function render($request) {
        return redirect('/login')->withErrors(
            ['username' => 'Something']
        );
    }
}
