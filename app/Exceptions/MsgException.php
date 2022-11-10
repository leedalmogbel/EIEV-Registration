<?php

namespace App\Exceptions;

use Exception;

use Session;

class MsgException extends Exception
{
    protected $url = null;
    /**
     * Custom construct, accepts url parameter
     *
     * @param String $msg
     * @param Strung|null $url
     */ 
    public function __construct($msg, $url = null) {
        // save url to object
        $this->url = $url;
        // call default construct
        parent::__construct($msg);
    }

    /**
     * renders exception
     *
     * @param Object $request
     */ 
    public function render($request) {
        // if url is not set
        if (!$this->url) {
            // set url to current
            // NOTE: This migh cause infinite redirect
            $this->url = URL::current();
        }

        // set message to session flash
        Session::flash('message', __($this->getMessage()));
        Session::flash('message_type', 'error');

        // redirect to url
        return redirect($this->url);
    }
}
