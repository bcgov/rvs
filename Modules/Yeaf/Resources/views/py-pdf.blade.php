<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>YEAF - Student Letter</title>
    <style>
        body{
            line-height: 1.1rem;
            font-size: 14px;
            margin: 130px 30px 30px 30px;
        }
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 120px; border-bottom: #0b2e13 1px solid; }
        footer { font-family: sans-serif; position: fixed; bottom: 12px; left: 0px; right: 0px; height: 80px; font-size: 10px;
            border-top: #0b2e13 1px solid; line-height: 1rem;}
        td{
            vertical-align: top;
        }
        ul{
            margin-top: 0; padding-top: 0;
            margin-bottom: 0; padding-bottom: 0;
        }
        table{
            width: 100%;
        }
        img{
            width: 125px;
        }
    </style>
</head>
<body>
    <header>
        <table>
            <tr>
                <td style="text-align: left; width: 25%;"><img src="{{ public_path('/images/bc_sq_logo.png') }}"></td>
                <td style="text-align: left; width: 30%;">
                    <br/>
                    <br/>
                    <strong>{{ $admin->ministry }}</strong>
                </td>
                <td style="text-align: left; width: 45%;"></td>
            </tr>
        </table>
    </header>

    <table>
        <tr>
            <td style="width: 35%;">
                <strong>TO:</strong>
                <table style="margin-left: 5px;">
                    <tr>
                        <td style="text-align: right;">Name:</td>
                        <td>{{ $admin->org_fname }} {{ $admin->org_lname }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Organization:</td>
                        <td>{{ $admin->org }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Fax:</td>
                        <td>{{ $admin->org_fax }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Date:</td>
                        <td>{{ $now_d }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total pages including this page:</td>
                    </tr>
                </table>
            </td>
            <td style="width: 20%;"></td>
            <td style="width: 35%;">
                <strong>FROM:</strong>
                <table style="margin-left: 5px;">
                    <tr>
                        <td style="text-align: right;">Name:</td>
                        <td>{{ $admin->user_fname }} {{ $admin->user_lname }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Branch:</td>
                        <td>{{ $admin->user_branch }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Phone:</td>
                        <td>{{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$admin->user_tele) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="font-size: 11px;">This information is CONFIDENTIAL.  It is intended only for the use of the person to whom it is addressed.  Any distribution, copying or other use by anyone else is strictly prohibited.  If you have received this facsimile in error, please telephone us immediately and destroy it.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3"><br/><br/><br/></td>
        </tr>
        <tr>
            <td colspan="3">Attached are copies of the {{ $program_year->year_start }}/{{ $program_year->year_end }} YEAF Awards.<br/><br/></td>
        </tr>
        <tr>
            <td colspan="3">Please contact me if you have any question.<br/></td>
        </tr>
        <tr>
            <td colspan="2">



                <br/><br/>Thank you,<br/><br/>
                    {{ $admin->user_fname }} {{ $admin->user_lname }}<br/>
            </td>
        </tr>

    </table>
    <footer>
        <table>
            <tr>
                <td style="width: 25%;">{{ $admin->ministry }}</td>
                <td style="width: 20%;">{{ $admin->ministry_branch }}</td>
                <td style="width: 30%;">
                    Mailing Address:<br/>
                    {{ $admin->ministry_address }}<br/>
                    {{ $admin->ministry_city }} {{ $admin->ministry_prov }} {{ $admin->ministry_postal }}<br/>
                    {{ $admin->ministry_tele_victoria }}<br/>
                    {{ $admin->ministry_tele_toll_free }} (Toll-free in Canada/USA)<br/>
                    {{ $admin->ministry_tele_lower }} (B.C. Lower Mainland)<br/>
                </td>
                <td style="width: 25%;">
                    Courier Address:<br/>
                    {{ $admin->ministry_location }}<br/>
                    {{ $admin->ministry_location_city }} {{ $admin->ministry_location_prov }} {{ $admin->ministry_location_postal }}<br/>
                    Fax: {{ $admin->ministry_fax }}<br/>
                    Toll-free Fax: {{ $admin->ministry_location_tele_toll_free }}
                </td>
            </tr>
        </table>
    </footer>

</body>
</html>
