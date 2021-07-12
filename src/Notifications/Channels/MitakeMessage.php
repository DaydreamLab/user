<?php

namespace DaydreamLab\Dddream\Notifications\Channels;

class MitakeMessage
{
    public $content;

    public $creatorId;

    public $msgType;

    public $extraFields;

    public function __construct($content, $type, $creatorId, $extraFields = [])
    {
        $this->content = $content;
        $this->msgType = $type;
        $this->creatorId = $creatorId;
        $this->extraFields = $extraFields;
    }
}