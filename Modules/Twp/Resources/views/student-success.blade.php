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
        header { font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
        position: fixed; top: 0px; left: 0px; right: 0px; height: 120px; border-bottom: #0b2e13 1px solid;}
        footer { font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
        position: fixed; bottom: 0px; left: 0px; right: 0px; height: 60px; font-size: 10px;
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
            <td style="width: 33%;"><strong>{{ $admin->ministry }}</strong></td>
            <td style="width: 33%;">System Navigator<br/>{{ $admin->ministry_branch }}<br/>Provincial Tuition Waiver Program</td>
            <td style="width: 33%;">
                Mailing Address:<br/>
                {{ $admin->ministry_address }}<br/>
                {{ $admin->ministry_city }} {{ $admin->ministry_prov }} {{ $admin->ministry_postal }}<br/>
                Email: tuitionwaiver@gov.bc.ca<br/>
            </td>
        </tr>
    </table>
</footer>
    <header>
        @php
        $p = public_path('images/bc_sq_logo.png');
        $data = base64_encode(file_get_contents($p));
        @endphp
        
        <table>
            <tr>
                <td style="text-align: left; width: 25%;"><img src="data:image/png;base64,{{ $data }}" style="width:120px;"></td>
                <td style="text-align: right; width: 75%;"><br/><p style="font-size: 32px;">Provincial Tuition Waiver Program </p></td>
            </tr>
        </table>
    </header>
    <p>{{ $now_d }}</p><br/>
    <p>{{ $app->student->first_name }} {{ $app->student->last_name }}</p>
    <p>Via email to: {{ $app->student->email }}</p><br/>
    <p><strong>Re: Provincial Tuition Waiver Program Application</strong></p><br/>
    <div>
        <p>I am pleased to advise that we have approved your application to the Provincial Tuition Waiver Program received
            {{ date('F j, Y', strtotime($app->received_date)) }}.</p>
        <p>As a recipient of the Provincial Tuition Waiver Program, you are eligible to have your tuition and eligible
            fees waived at an estimated total cost of ${{number_format($app->program->total_estimated_cost, 2, '.', ',')}} as you work to complete
            your {{$app->program->credential}} studies at {{$app->program->institution_name}}. This means StudentAid BC will pay your tuition and eligible fees for
            this program according to information submitted by {{$app->program->institution_name}}. </p>

        <p>If you have also applied for the <a href="https://studentaidbc.ca/explore/grants-scholarships/learning-future-grant">Learning for Future Grant</a>, you will be eligible for the grant if you are enrolled in a program or course that leads, or will lead, to a credential at the undergraduate level. Learning for Future Grant funding will be provided to you via your institution.</p>

        <p><strong>If you plan to change the location of your studies to another <a href="https://studentaidbc.ca/explore/grants-scholarships/provincial-tuition-waiver-program#Am_I_eligible">eligible institution</a>, please notify us at <a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a>, as well as inform the financial aid office at your new institution.</strong></p>

        <p>Please be advised that tuition waivers and grants may be a source of taxable income, and you will receive a T4A through your institution to include with your income tax return. For more information on how taxable income is treated, or the issuance of a T4A, please visit the <a href="https://www.canada.ca/en/services/taxes/income-tax/personal-income-tax.html">Canada Revenue Agency website</a>.</p>

        <p>If you have any questions, please do not hesitate to contact us at <a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a>.<br/>We wish you
            success in your educational pursuits.</p>
        <p>Sincerely,</p>
        <p>System Navigator, Provincial Tuition Waiver Program<br/><a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a></p>
        <p>cc: {{$app->program->institution->name}}, {{ $contact_name }}, {{ $contact_email }}</p>
    </div>


    <script type="text/php">
    if (isset($pdf)) {
        $text = "{PAGE_NUM} | {PAGE_COUNT}";
        $size = 9;
        $font = $fontMetrics->getFont("Arial,sans-serif");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 25;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>
