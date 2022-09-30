

<body>
<meta charset="UTF-8">
    <table width="100%">
        <tr class="box-sizing">
            <td align="center" class="box-sizing py-5">
                <table width="876" class="mx-auto bg-white table-style" style="max-width:100%">
                    <tr class="box-sizing">
                        <td class="box-sizing">
                            <img src="{{asset('/email/images/header_user.jpg')}}" alt="">
                        </td>
                    </tr>
                    <tr class="box-sizing">
                        <td align="center" class="box-sizing px-2">
                            <table width="100%" class="table-style" style="max-width:720px">
                                <tr class="box-sizing">
                                    <td align="center" class="box-sizing py-5">
                                        <h1 class="h-2 color-primary" style="font-weight: medium">
                                            零壹官網會員回娘家
                                        </h1>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" class="table-style" style="max-width:720px">
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-4 font-light">
                                        親愛的 <span class="color-primary font-regular h-5">{{$userName}}</span> 您好：
                                    </td>   
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-4 font-light">
                                        為了提供您更好的網站服務體驗，我們誠摯地邀請您至零壹官網完成會員驗證與資料更新，並同時綁定 LINE官方帳號，輕鬆兩步驟，未來您將第一手掌握最新產品方案、促銷訊息，並享有活動/課程快速通關報名與不定時會員好禮放送。
                                    </td>
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-5 font-light">
                                        不要猶豫，即刻行動!!
                                    </td>
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-3 font-light">
                                        STEP 1 : 請點擊以下連結
                                    </td>
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-3 font-light">
                                        <a href="{{$updateLink}}" class="color-primary">
                                            {{$updateLink}}
                                        </a>
                                    </td>
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-3 font-light">
                                        STEP 2 : 立即加入零壹科技 LINE官方帳號
                                    </td>
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-5 font-light">
                                        <a href="{{$lineLink}}" class="color-primary">
                                            {{$lineLink}}
                                        </a>
                                    </td>
                                </tr>
                                <tr class="box-sizing">
                                    <td class="box-sizing pb-5 font-light">
                                        感謝您的支持。
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