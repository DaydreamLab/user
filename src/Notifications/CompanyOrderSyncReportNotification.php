<?php

namespace DaydreamLab\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyOrderSyncReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $view = 'emails.Company.OrderSyncReport';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected array $result, protected string $userName)
    {
        $this->onQueue('import-job');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }


    public function buildContent(): string
    {
        return '以下為公司銷售紀錄同步結果：' . PHP_EOL
            . "新增公司銷售紀錄: " . $this->result['addOrder'] . '筆' . PHP_EOL
            . "更新公司銷售紀錄: " . $this->result['updateOrder'] . '筆' . PHP_EOL
            . "更新公司紀錄: " . $this->result['updateCompany'] . '筆' . PHP_EOL
            . "新增失敗: " . $this->result['fail'] . '筆' . PHP_EOL;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = '【零壹官網】公司銷售紀錄同步結果';
        return (new MailMessage)
                ->subject($subject)
                ->view(
                    $this->view,
                    [
                        'userName' => $this->userName,
                        'content' => $this->buildContent(),
                        'errors' => $this->result['errors']
                    ]
                );

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
            //
        ];
    }
}
