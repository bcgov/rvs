<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TWP - Student Letter</title>
    <style>
        body{
            line-height: 1.1rem;
            font-size: 14px;
            margin: 130px 30px 30px 30px;
            font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
        }
        header { font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif; position: fixed; top: 0px; left: 0px; right: 0px; height: 120px; border-bottom: #0b2e13 1px solid;}
        footer { font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif; position: fixed; bottom: 0px; left: 0px; right: 0px; height: 60px; font-size: 10px;
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
            @php
            $p = public_path('images/bc_sq_logo.png');
            $data = base64_encode(file_get_contents($p));
            @endphp
            <td style="text-align: left; width: 25%;"><img src="data:image/png;base64,{{ $data }}" style="width:120px;"></td>
            <td style="text-align: right; width: 75%;"><br/><p style="font-size: 32px;">Provincial Tuition Waiver Program </p></td>
        </tr>
    </table>
</header>
<p>{{ $now_d }}</p><br/>
<p>{{ $app->program->institution->name }}</p>
<p>{{ $contact_name }}</p>
<p>Financial Awards</p>
<p>Via email to: {{ $contact_email }}</p><br/>
<p><strong>Re: Provincial Tuition Waiver Program Application</strong></p><br/>
<div>
    <p>This letter is to inform you that, regrettably, we have been unable to approve the application made
        for {{ $app->student->first_name }} {{ $app->student->last_name }} on {{date('F j, Y', strtotime($app->received_date))}}
        for the Provincial Tuition Waiver Program, as one or more eligibility criteria have not been met.
        As such, their application has been denied, and the student has been informed.</p>

    <p>The Provincial Tuition Waiver Program is committed to supporting former youth in care to achieve their post-secondary
        education goals. The program has discretion on a case-by-case basis to approve exceptions to the eligibility
        criteria through a review of exceptional circumstances. Individuals with circumstances falling outside of the
        eligibility criteria can contact the PTWP System Navigator at <a href="mailto:BCPTWPSystemNavigator@gov.bc.ca">BCPTWPSystemNavigator@gov.bc.ca</a>
        to request a review of their eligibility.  </p>

    <p>If you have any questions about this decision, please do not hesitate to contact us at <a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a>.</p>
    <p>Sincerely,</p>
    <p>PTWP System Navigator<br/><a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a></p>
</div>


</body>
</html>
