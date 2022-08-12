<?php

namespace App\Exceptions;

use Exception;

class GeneralJsonException extends Exception
{
    /**
     * Constructor
     * 
     * @param string    $message  - the exception message
     * @param int       $code     - the exception code
     * @param Exception $previous - the previous exception
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Covert to string
     * 
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * Render the exception as an HTTP response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json(
            [
                'message' => $this->message,
                'code' => $this->code,
            ],
            $this->code
        );
    }
}
