<?php

namespace DaydreamLab\User\Notifications\Channels;

class XsmsMessage
{
    public $category;   # 簡訊分類

    public $type;       # 分類類型

    public $subject;    # 簡訊主旨

    public $content;    # 簡訊內容

    public $creatorId;  # 創建者id

    public $extraFields = [];

    public function __construct($category, $type,$subject, $content, $creatorId, $extraFields = [])
    {
        $this->category = $category;
        $this->type = $type;
        $this->subject = $subject;
        $this->content = $content;
        $this->creatorId = $creatorId;
        $this->extraFields = $extraFields;
    }
}
