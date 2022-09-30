<body>
<meta charset="UTF-8">
<table width="100%" class="table-style">
    <tr class="box-sizing">
        <td align="center" class="box-sizing py-5">
            <table width="876" class="mx-auto bg-white table-style borderTop" style="max-width:100%">
                <tr class="box-sizing">
                    <td align="center" class="box-sizing px-2">
                        <table width="100%" class="table-style" style="max-width:720px">
                            <tr class="box-sizing">
                                <td align="center" class="box-sizing py-5">
                                    @include('emails.Components.Subject', ['subject' => $subject])
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table-style" style="max-width:720px">
                            <tr class="box-sizing">
                                <td class="box-sizing pb-4 font-light">
                                    {!! $content !!}
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
