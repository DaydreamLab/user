<?php

namespace DaydreamLab\User\Rules;

use GuzzleHttp\Client;
use DaydreamLab\JJAJ\Rules\BaseRule;
use Illuminate\Contracts\Validation\Rule;

class RecaptchaV3 extends BaseRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();
        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('app.recaptchaV3.secret'),
                'response' => $value
            ]
        ]);

        return json_decode($response->getBody()->getContents())->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  'Recaptcha V3 verify fail';
    }
}
