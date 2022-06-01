<table style="width:100%;height:100%;max-width:760px;border-spacing:0;border-collapse:collapse;margin:0 auto;" align="center">
    <tbody>
        <tr>
            <td style="padding:20px;font-family:'Open Sans','HelveticaNeue-Light','Helvetica Neue Light',Helvetica,Arial,sans-serif;">
                <table style="width:100%;height:100%;border-spacing:0;border-collapse:collapse;margin:0 auto" align="center">
                    <thead style="background-color:#e0e0e0;">
                        <tr>
                            <th style="text-align:center;padding:20px 15px;height:72px;">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('images/logo-1.svg') }}" border="0" />
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#f5f5f5;">
                            <td style="padding:50px;">
                                @yield('content')
                            </td>
                        </tr>
                        <tr style="background-color:#e0e0e0;height:120px;">
                            <td style="padding:50px 30px;">
                                <p style="text-align:center;font-size:1.2em;margin:0px;padding:0px;margin-bottom:15px;font-weight:bold;">{{ $setting->where('name', 'company_name')->first()->value }}</p>
                                <p style="text-align:center;padding:0px;"><?= str_replace('<br />', ', ', nl2br( $setting->where('name', 'company_address')->first()->value)) ?></p>
                                <p style="text-align:center;padding:0px;">&copy;<?= date('Y'); ?> - <a href="{{ url('/') }}">{{ $setting->where('name', 'app_name')->first()->value }}</a></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
