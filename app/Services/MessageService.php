<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Http\ReturnCode;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\MessageBag;

class MessageService
{
    protected Translator $translator;

    protected array $data;

    protected MessageBag $message;

    protected string $exception = BusinessException::class;

    public function __construct()
    {
        $this->message = new MessageBag();
    }

    public function getData(): array
    {
        throw_if($this->fails(), $this->exception, $this);

        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function addMessage(ReturnCode $code, string $message = '', bool $throw = true): void
    {
        $this->message->add(
            $code->value,
            $message ?: trans('business.' . $code->value)
        );

        throw_if($throw, $this->exception, $this);
    }

    public function cleanMessage(): void
    {
        $this->message = new MessageBag();
    }

    public function messages(): MessageBag
    {
        return $this->message;
    }

    public function errors(): MessageBag
    {
        return $this->messages();
    }

    public function getTranslator(): Translator
    {
        return $this->translator ?? app('translator');
    }

    public function setTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

    public function passes(): bool
    {
        return $this->message->isEmpty();
    }

    public function fails(): bool
    {
        return $this->message->isNotEmpty();
    }
}