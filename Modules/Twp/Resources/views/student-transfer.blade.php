<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TWP - Student Transfer Letter</title>
    <style>
        body{
            line-height: 1.1rem;
            font-size: 14px;
            margin: 130px 30px 30px 30px;
            font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
        }
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 120px; border-bottom: #0b2e13 1px solid;}
        footer { font-family: sans-serif; position: fixed; bottom: 0px; left: 0px; right: 0px; height: 60px; font-size: 10px;
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

<footer>
    <table>
        <tr>
            <td style="width: 50%;">{{ $admin->ministry }}<br/>System Navigator<br/>{{ $admin->ministry_branch }}<br/>Provincial Tuition Waiver Program</td>
            <td style="width: 50%;">
                Mailing Address:<br/>
                {{ $admin->ministry_address }}<br/>
                {{ $admin->ministry_city }} {{ $admin->ministry_prov }} {{ $admin->ministry_postal }}<br/>
                Email: tuitionwaiver@gov.bc.ca<br/>
            </td>
        </tr>
    </table>
</footer>
<header>
    <table>
        <tr>
            <td style="text-align: left; width: 25%;"><img src="{{ public_path('/images/bc_sq_logo.png') }}"></td>
            <td style="text-align: right; width: 75%;"><br/><p style="font-family: Arial,sans-serif; font-size: 32px; font-weight: lighter;">Provincial Tuition Waiver Program </p></td>
        </tr>
    </table>
</header>
<p>{{ $now_d }}</p><br/>
<p>{{ $app->program->institution->name }}</p>
<p>{{ $contact_name }}</p>
<p>Financial Awards</p>
<p>Via email to: {{ $contact_email }}</p><br/>
<p><strong>Re: Provincial Tuition Waiver Program Transfer or Concurrent Studies</strong></p><br/>
<div>
    <p>Please be advised that {{ $app->student->first_name }} {{ $app->student->last_name }} is approved under the Provincial Tuition Waiver Program</p>

    <p>{{ $app->student->first_name }} {{ $app->student->last_name }} is eligible to have their tuition and eligible fees waived as they work to complete
        their {{ $app->program->credential }} studies at {{ $app->program->institution->name }}.</p>

    <p>If you have any questions about this approval, please do not hesitate to contact us at
        <a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a>.</p>
    <p>Sincerely,</p>
    <br/>
    <p>System Navigator<br/>Provincial Tuition Waiver Program<br/><a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a></p>
    <p>cc: {{ $app->student->first_name }} {{ $app->student->last_name }}, {{ $app->student->email }}</p>
</div>


</body>
</html>
