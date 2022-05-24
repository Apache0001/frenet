<?php

namespace Source\Support;

class Message
{
    private $text;

    private $type;

    public function getText()
    {
        return $this->text;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * info
     *
     * @param string $message
     * @return message|null
     */
    public function info(string $message): Message
    {
        $this->type = 'info';
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * info
     *
     * @param string $message
     * @return message|null
     */
    public function error(string $message): Message
    {
        $this->type = 'error';
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return "<div class='".CONF_MESSAGE_CLASS." {$this->getType()}'>{$this->getText()}</div>";
    }

    private function filter(string $message): string
    {
        return filter_var($message, FILTER_DEFAULT);
    }

}