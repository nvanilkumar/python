<?php

namespace App\Services\Blendr;

use App\Exceptions\InvalidArgumentException;

class ErrorEnvelope {
    private $error;

    public function __construct(Error $error) {
        $this->error = $error;
    }

    /**
     * @return Error
     */
    public function getError(): Error
    {
        return $this->error;
    }

    public static function fromJSON(string $json): ErrorEnvelope
    {
        $a = json_decode($json, true);

        $lastError = json_last_error();

        if ($lastError !== JSON_ERROR_NONE) {
            $message = '';

            switch ($lastError) {
                case JSON_ERROR_DEPTH:
                    $message = 'maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $message = 'unknown error';
                    break;
            }
            throw new InvalidArgumentException($message . ': \'' . $json . '\'');
        }

        if (!array_key_exists('error', $a)) {
            throw new InvalidArgumentException('no error object found: \'' . $json . '\'');
        }
        $e = $a['error'];
        $message = '';
        $code = '';

        if (array_key_exists('message', $e)) {
            $message = $e['message'];
        }

        if (array_key_exists('code', $e)) {
            $code = $e['code'];
        }
        $error = new Error($message, $code);
        return new ErrorEnvelope($error);
    }
}