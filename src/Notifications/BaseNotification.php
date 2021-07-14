<?php

namespace DaydreamLab\User\Notifications;

use DaydreamLab\User\Models\NotificationTemplate\NotificationTemplate;
use DaydreamLab\User\Notifications\Channels\MitakeMessage;
use DaydreamLab\User\Notifications\Channels\XsmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $category = null;

    protected $type = null;

    protected $creatorId = 99999993;

    protected $useCustomTemplate = false;

    protected $view = 'emails.default';

    protected $subject = '';

    protected $content = '';


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($creatorId = null, $useCustomTemplate = false)
    {
        if ($creatorId) {
            $this->creatorId = $creatorId;
        }

        if ($useCustomTemplate) {
            $this->useCustomTemplate = $useCustomTemplate;
        }
    }

    public function defaultSubject()
    {
        return '';
    }


    public function defaultMailContent()
    {
        return '';
    }


    public function defaultSmsContent($channelType)
    {
        return '';
    }


    public function getMailParams()
    {
        return [];
    }


    public function getMailTemplate()
    {
        if ($this->useCustomTemplate) {
            $template = NotificationTemplate::where('channelType', 'mail')
                ->where('category', $this->category)
                ->where('type', $this->type)
                ->first();
            if ($template) {
                $this->subject = $template->subject
                    ? $this->handleReplacement($template->content)
                    : $this->defaultSubject();
                $this->content = $this->handleReplacement($template->contentHtml);
            } else {
                $this->subject = $this->defaultSubject();
                $this->content = $this->defaultMailContent();
            }
        } else {
            $this->subject = $this->defaultSubject();
            $this->content = $this->defaultMailContent();
        }
    }


    public function handleReplacement($string)
    {
        foreach ($this->replaceRules() as $key => $replaceStr) {
            $string = str_replace($key, $replaceStr, $string);
        }

        return $string;
    }


    public function replaceRules()
    {
        return [
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->getMailTemplate();

        return (new MailMessage)
                    ->subject($this->subject)
                    ->view($this->view, $this->getMailParams());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }


    public function toMitake($notifiable)
    {
        if ($this->useCustomTemplate) {
            $template = NotificationTemplate::where('channelType', 'sms')
                ->where('category', $this->category)
                ->where('type', $this->type)
                ->first();

            if ($template) {
                $this->content = $this->handleReplacement($template->content);
            } else {
                $this->content = $this->handleReplacement($this->defaultSmsContent('sms'));
            }
        } else {
            $this->content = $this->handleReplacement($this->defaultSmsContent('sms'));
        }


        return new MitakeMessage(
            $this->category,
            $this->type,
            $this->content,
            $this->creatorId,
            []
        );
    }


    public function toXsms($notifiable)
    {
        if ($this->useCustomTemplate) {
            $template = NotificationTemplate::where('channelType', 'sms')
                ->where('category', $this->category)
                ->where('type', $this->type)
                ->first();

            if ($template) {
                $this->subject = $template->subject
                    ? $this->handleReplacement($template->subject)
                    : $this->subject;
                $this->content = $this->handleReplacement($template->content);
            } else {
                $this->content = $this->handleReplacement($this->defaultSmsContent('sms'));
            }
        } else {
            $this->content = $this->handleReplacement($this->defaultSmsContent('sms'));
        }

        return new XsmsMessage(
            $this->category,
            $this->type,
            $this->subject,
            $this->content,
            $this->creatorId,
            []
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'mitake', 'xsms'];
    }
}
