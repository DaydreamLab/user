<?php

namespace DaydreamLab\User\Controllers\Xsms;


use DaydreamLab\JJAJ\Helpers\ArrayToXml;
use DaydreamLab\User\Requests\Xsms\XsmsQuerySmsRequest;
use GuzzleHttp\Client;

class XsmsController
{
    protected $package = 'User';

    protected $modelName = 'Xsms';

    protected $modelType = 'Parent';

    public function __construct()
    {
    }

    public function querySms(XsmsQuerySmsRequest $request)
    {
        $apiUrl = 'https://xsms.aptg.com.tw/XSMSAP/api/APIQueryHttpRequest';

        $validated = $request->validated();
        $params = [
            'MDN'   => config('daydreamlab.user.sms.xsms.mdn'),
            'UID'   => config('daydreamlab.user.sms.xsms.uid'),
            'UPASS' => config('daydreamlab.user.sms.xsms.upass'),
        ];

        $content = [
            'TaskID'    => $validated->get('TaskID'),
            'GetMode'   => $validated->get('GetMode')
        ];

        $xml = ArrayToXml::convertWithoutDeclaration($content, 'Request');
        $params['Content'] = $xml;

        $client = new Client();
        $response = $client->post($apiUrl, [
            'form_params' => $params
        ]);

        show($response->getBody()->getContents());
    }
}
