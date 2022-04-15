<?php

namespace DaydreamLab\User\Notifications\User;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DaydreamLab\User\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Psy\Command\ShowCommand;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserGetTotpQrCodeNotification extends BaseNotification
{
    use Queueable;

    protected $view = 'emails.User.GetOtp';

    protected $type = "TOTP";

    protected $category = "User";

    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $creatorId = null)
    {
        parent::__construct($creatorId);
        $this->user = $user;
    }


    public function defaultSubject()
    {
        return '[零壹科技官方網站] 管理後台TOTP驗證資訊';
    }


    public function defaultMailContent()
    {
        $expiredDate = Carbon::parse($this->user->twofactor['totp']['expiredDate'], 'UTC')->tz($this->user->timezone)->format("Y-m-d H:i:s");
        $expireDyas = $this->user->twofactor['totp']['expiredSecond'] / 60 / 60 / 24;
        \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path("app/public/qrcode"));
        $qrCodeFileName = Str::uuid() . '.png';
        QrCode::format('png')->size(200)->generate(utf8_encode($this->user->twofactor['totp']['url']), storage_path("app/public/qrcode/$qrCodeFileName"));
        $qrcodeAccessUrl = asset("storage/qrcode/$qrCodeFileName");

        $img = "<img width='200' height='200' src=$qrcodeAccessUrl />";

        $str = "親愛的零壹網站管理員：<br><br>";
        $str .= "您好，您的帳號 <font style='display: none'>@</font>{$this->user->email} 後台TOTP驗證機制QRcode如下";
        $str .= "
            <table width='100%'>
                <tbody>
                    <tr>
                        <td align='center' style='padding-top: 60px;padding-bottom: 60px;'>
                            {$img}
                        </td>
                    </tr>
                </tbody>
            </table>";
        $str .= "使用期限：{$expireDyas}天，至{$expiredDate} <br><br>";
        $str .= "請以手機安裝 Microsoft Authenticator（<a target='blank' href='https://www.microsoft.com/zh-tw/security/mobile-authenticator-app'>https://www.microsoft.com/zh-tw/security/mobile-authenticator-app</a>）掃描此QRcode以產生30秒有效的網站後台驗證碼TOTP。 <br><br>";
        $str .= "若驗證碼帳號於APP已經存在，請直接點選「覆寫您帳戶現有的安全性資訊」以更新最新的TOTP資訊。<br><br>";
        $str .= "此為機敏資訊，請務必妥善保存避免外流。<br><br>";
        $str .= "零壹科技官方網站";

        return $str;
    }


    public function defaultSmsContent($channelType)
    {
        return '';
    }



    public function getMailParams()
    {
        return [
            'content' => $this->defaultMailContent(),
            'subject' => $this->defaultSubject(),
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return parent::via($notifiable);
    }
}
