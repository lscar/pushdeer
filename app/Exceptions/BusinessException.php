<?php

namespace App\Exceptions;

use App\Services\MessageService;
use Exception;

class BusinessException extends Exception
{
    public MessageService $business;

    public function __construct($business)
    {
        parent::__construct(static::summarize($business));

        $this->business = $business;
    }

    protected static function summarize(MessageService $business)
    {
        $messages = $business->errors()->all();

        if (!count($messages) || !is_string($messages[0])) {
            return $business->getTranslator()->get('The given data was invalid.');
        }

        $message = array_shift($messages);

        if ($count = count($messages)) {
            $pluralized = $count === 1 ? 'error' : 'errors';

            $message .= ' ' . $business->getTranslator()->get("(and :count more $pluralized)", compact('count'));
        }

        return $message;
    }
}
