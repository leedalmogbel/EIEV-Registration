<?php

namespace App\Exceptions;

use URL;

class FieldException extends \Exception {
    /**
     * Custom method that gets the array of exception messages
     *
     * @return array
     */ 
    public function getMessages() {

        // check if the exception message is a json string
        json_decode($this->getMessage());
        if (json_last_error() === JSON_ERROR_NONE) {
            // if it is
            // decode it as an array and return
            return json_decode($this->getMessage(), true);
        }

        // if not, return message pushed in an array
        return [$this->getMessage()];
    }

    public function render($request) {
        // Since we know that this is an error on a submitted form
        // we know that the redirect URL should be the current URL
        $url = URL::current();
        
        // we should redirect this back
        // with the error messages in the form
        // and the inputed data
        return redirect($url)
            ->withErrors($this->getMessages())
            ->withInput($request->input());
            
    }
}
