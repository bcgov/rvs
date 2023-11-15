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
    <table>
        <tr>
            <td style="text-align: left; width: 25%;"><img src="{{ public_path('/images/bc_sq_logo.png') }}"></td>
            <td style="text-align: right; width: 75%;"><br/><p style="font-family: Arial,sans-serif; font-size: 32px; font-weight: lighter;">Provincial Tuition Waiver Program </p></td>
        </tr>
    </table>
</header>
<p>{{ $now_d }}</p><br/>
<p>{{ $app->student->first_name }} {{ $app->student->last_name }}</p>
<p>Via email to: {{ $app->student->email }}</p><br/>
<p><strong>Re: Provincial Tuition Waiver Program Application (PTWP) Application</strong></p><br/>
<div>
    <p>This letter is to inform you that, regrettably, we are unable to approve your application to the Provincial Tuition Waiver Program, as one or more eligibility criteria have not been met.</p>

    <p>To be eligible for the Provincial Tuition Waiver Program, applicants must be: </p>

    @foreach($app->reasons as $reason)
        <p style="white-space: pre-line;">{{ $reason->letter_body }}</p>
    @endforeach

    @foreach($app->reasons as $reason)
        @if($reason->title === 'Time in Care')
            <p>As our records indicate that your time in care status totals [XX] days (outline confirmed care status(es)); we are unable to approve your application at this time. </p>
        @endif
    @endforeach

    <p>The Provincial Tuition Waiver Program is committed to supporting former youth in care to achieve their post-secondary education goals. The program has discretion on a case-by-case basis to approve exceptions to the eligibility criteria through a review of exceptional circumstances. Individuals with circumstances falling outside of the eligibility criteria can contact the PTWP System Navigator to request a review of their eligibility. </p>
    <div style="page-break-before: always;"></div>
    <p>We also encourage you to contact your institution’s financial aid office to explore your eligibility for other programs and bursaries that may help offset educational costs, including student financial assistance.</p>

    <p>If you have any questions about this decision, or other grant and student financial assistance options that may be available to you through StudentAid BC, please do not hesitate to contact us at: <a href="mailto: tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a>.</p>
    <p>Sincerely,</p>
    <p>System Navigator<br/>Provincial Tuition Waiver Program<br/><a href="mailto:tuitionwaiver@gov.bc.ca">tuitionwaiver@gov.bc.ca</a></p>
{{--    <p>cc: {{$app->program->institution->name}}, {{ $app->program->institution->contact_name }}, {{ $app->program->institution->contact_email }}</p>--}}
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
