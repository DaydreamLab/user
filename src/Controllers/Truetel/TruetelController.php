<?php

namespace DaydreamLab\User\Controllers\Truetel;

use DaydreamLab\User\Requests\Truetel\TruetelQuerySmsRequest;

class TruetelController
{
    protected $package = 'User';

    protected $modelName = 'Truetel';

    protected $modelType = 'Parent';

    public function __construct()
    {
    }

    public function querySms(TruetelQuerySmsRequest $request)
    {
        $ip = config('daydreamlab.user.sms.truetel.host');
        $port = config('daydreamlab.user.sms.truetel.port');
        $apiUrl = "http://{$ip}:{$port}/mpushapi/smsquerydr";

        $result = [];
        foreach ($request->get('messageIds') as $messageId) {
            $postData = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'xml' => \DaydreamLab\JJAJ\Helpers\ArrayToXml::convert(
                        [
                            'SysId' => config('daydreamlab.user.sms.truetel.sysid'),
                            'MessageId' => $messageId,
                            'DestAddress' => (int)$request->get('phone'),
                        ],
                        'SmsSubmitReq',
                        true,
                        'UTF-8',
                        '1.0',
                        [],
                        null,
                        false
                    )
                ]
            ];

            $response = (new \GuzzleHttp\Client())->post($apiUrl, $postData);
            $content = $response->getBody()->getContents();
            $result[] = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        }
        show($result);
    }
}
