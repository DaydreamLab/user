<body>
<meta charset="UTF-8">
<table width="100%" class="table-style">
    <tr class="box-sizing">
        <td align="center" class="box-sizing py-5">
            <table width="876" class="mx-auto bg-white table-style borderTop" style="max-width: 100%">
                <tr class="box-sizing">
                    <td align="center" class="box-sizing px-2">
                        <table width="100%" class="table-style" style="max-width: 720px">
                            <tr class="box-sizing">
                                <td align="center" class="box-sizing py-5">
                                    @include('emails.Components.Subject', ['subject' => $subject])
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table-style" style="max-width: 720px">
                            <tr class="box-sizing">
                                <td class="box-sizing pb-4 font-light">
                                    @include('emails.Components.Greeting', ['name' => $user->name])
                                </td>
                            </tr>
                            <tr>

                            </tr>
                            <tr class="box-sizing">
                                <td class="box-sizing pb-4 font-light">
                                    您的帳號 ({{$user->mobilePhone}}) 正在進行零壹科技網站登入驗證，驗證碼為：
                                </td>
                            </tr>
                            <tr class="box-sizing">
                                <td align="center" class="box-sizing pb-4 font-light">

                                    <table width="20%">
                                        <tbody>
                                        <tr class="box-sizing">
                                            <td align="center" style="font-size: 44px;padding: 20px 30px;background-color: #eef5cc;line-height: 1;color: #abcd02;">
                                                {{$code}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr class="box-sizing">
                                <td class="box-sizing pb-4 font-light">
                                    為了保障您帳號的安全性，請在15分鐘內完成驗證。<br><br>
                                    若您近期沒有執行登入零壹官方網站，請儘速與網站管理團隊聯繫以保障帳號安全。<br><br><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="box-sizing">
                    <td class="box-sizing">
                        @include('emails.Components.Foot')
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>