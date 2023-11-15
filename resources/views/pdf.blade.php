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
                <td style="text-align: center; width: 50%;"></td>
                <td style="text-align: left; width: 25%;"></td>
            </tr>
        </table>
    </header>

    <table>
        <tr><td style="text-align: right;">{{ $now_d }}</td></tr>
        <tr>
            <td>
                {{ $student->first_name }} {{ $student->last_name }}<br/>
                {{ $student->address }}<br/>
                {{ $student->city }} {{ $student->province }} {{ $student->postal_code }}<br/><br/><br/>
                Dear {{ $student->first_name }} {{ $student->last_name }}:<br/><br/>



                @switch($doc)
                    @case('rptLtrApproved')
                    @include('letters.reptLtrApproved', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'))
                    @break
                    @case('rptLtrPending')
                    @include('letters.reptLtrPending', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'))
                    @break
                    @case('rptLtrDenied')
                    @include('letters.reptLtrDenied', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'))
                    @break
                    @case('rptLtrApprovedSFASExtract')
                    @include('letters.reptLtrApprovedSFASExtract', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'))
                    @break
                    @case('rptLtrSuccessComp')
                    @include('letters.reptLtrSuccessComp', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'))
                    @break

                    @case('rptLtrWithdrawal')
                    @include('letters.reptLtrWithdrawal', compact('grant', 'admin', 'user', 'doc', 'student', 'officer', 'now_d', 'now_t'))
                    @break

                @endswitch





                <br/><br/>Sincerely,<br/><br/><br/>
                @if(is_null($officer))
                    {{ $user->first_name }} {{ $user->last_name }}<br/>
                    {{ $admin->contact_title }} - {{ $admin->contact_dept }}<br/>
                @else
                    {{ $officer->first_name }} {{ $officer->last_name }}<br/>
                    Program Officer - Directed Programs<br/>
                @endif
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
