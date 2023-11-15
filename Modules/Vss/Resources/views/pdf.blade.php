<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VSS - Report</title>
    <style>
        body{
            line-height: 1.6rem;
            font-size: 14px;
            margin: 130px 30px 30px 30px;
        }
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 120px; }
        td{
            vertical-align: top;
        }
        ul{
            margin-top: 0; padding-top: 0;
            margin-bottom: 0; padding-bottom: 0;
        }
        table{
            border-bottom: #0b2e13 1px solid; width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <table>
            <tr>
                <td style="text-align: left; width: 25%;"><small>{{ $now_d }}<br/>{{ $now_t }}</small></td>
                <td style="text-align: center; width: 50%;">Ministry of Post-Secondary Education<br/>Verification Statistics System<br/><strong>Verification Report</strong></td>
                <td style="text-align: left; width: 25%;"></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Student: {{ $case->last_name }}, {{ $case->first_name }}</strong></td>
                <td style="text-align: left;"><strong>SIN: {{ $case->sin }}</strong></td>
            </tr>
        </table>
    </header>

    <table>
        <tr>
            <td style="width: 40%;"><strong>Case Status:</strong> {{ $case->incident_status }}</td>
            <td style="width: 60%;"><strong>School:</strong> {{ $case->institution->institution_code }} | {{ $case->institution->institution_name }}</td>
        </tr>
        <tr>
            <td><strong>Open Date:</strong> {{ $case->open_date }}</td>
            <td><strong>Primary Area of Audit:</strong> {{ $case->primaryAudit->description }}</td>
        </tr>
        <tr>
            <td><strong>Severity:</strong> {{ $case->severity === 'A' ? 'Audit' : 'Investigation' }}</td>
            <td><strong>Referred by:</strong> {{ $case->referral->description }}</td>
        </tr>
        <tr>
            <td><strong>Auditor/Date:</strong> {{ $case->auditor_user_id }} {{ $case->audit_date }}</td>
            <td><strong>Investigator/Date:</strong> {{ $case->investigator_user_id }} {{ $case->investigation_date }}</td>
        </tr>
        <tr>
            <td>
                <strong>Primary Area of Audit:</strong>
                {{ $case->primaryAudit->description }} <small>({{$case->audit_type === 'A' ? 'Pre-Audit' : 'Post-Audit'}})</small>
            </td>
            <td>
                <strong>Additional Areas of Audit:</strong>
                <ul>
                    @foreach($case->audits as $audit)
                        <li>{{$audit->audit->description}} <small>({{$audit->audit_type === 'A' ? 'Pre-Audit' : 'Post-Audit'}})</small></li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Nature of Offence:</strong>
                <ul>
                    @foreach($case->offences as $offence)
                        <li>{{$offence->offence->description}}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <strong>Sanctions:</strong>
                <ul>
                    @foreach($case->sanctions as $sanction)
                        <li>{{$sanction->sanction->description}}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 50%;"><strong>Closure:</strong></td>
            <td style="width: 50%;"></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;"><strong>Case Closed/Date:</strong> {{ $case->case_close === true ? 'Yes' : 'No' }} {{ $case->close_date }}</td>
            <td style="padding-left: 15px;">
                <strong>Reason For Closing: </strong>
                @switch($case->reason_for_closing)
                    @case('C')<span>Complete</span>@break
                    @case('D')<span>Deceased</span>@break
                    @case('N')<span>No Response</span>@break
                @endswitch
            </td>
        </tr>
        <tr>
            <td style="padding-left: 15px;"><strong>Case Outcome:</strong>
                @switch($case->case_outcome)
                    @case('S')<span>Substantiated</span>@break
                    @case('U')<span>Unsubstantiated</span>@break
                @endswitch
            </td>
            <td style="padding-left: 15px;">
                <strong>Appealed/Outcome: </strong>
                <span>{{ $case->appeal_flag === true ? 'Yes' : 'No' }} </span>
                @switch($case->appeal_outcome)
                    @case('A')<span>Approved</span>@break
                    @case('D')<span>Denied</span>@break
                    @case('P')<span>Approved in Part</span>@break
                @endswitch
            </td>
        </tr>
    </table>
    <table style="page-break-after: auto;">
        <tr>
            <td><strong>RCMP:</strong></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">
                <strong>Referred to RCMP/Date:</strong>
                <span>{{ $case->rcmp_referral_flag === true ? 'Yes' : 'No' }} {{ $case->rcmp_referral_date }} </span>
            </td>
            <td style="padding-left: 15px;">
                <strong>Charges Laid: </strong>
                <span>{{ $case->charges_laid_flag === true ? 'Yes' : 'No' }} </span>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">
                <strong>RCMP Closure Date:</strong>
                <span>{{ $case->rcmp_closure_date }} </span>
            </td>
            <td style="padding-left: 15px;">
                <strong>Convicted: </strong>
                <span>{{ $case->conviction_flag === true ? 'Yes' : 'No' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 15px;">
                <strong>Sentence Comments: </strong>{{ $case->sentence_comment }}
            </td>
        </tr>
    </table>
    <table style="page-break-after: always;">
        <tr>
            <td style="width: 20%;"><strong>Funding:</strong></td>
            <td style="width: 25%;"></td>
            <td style="width: 15%;"></td>
            <td style="width: 20%;"></td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            <td style="text-align: center;"><strong>Application</strong></td>
            <td><strong>Funding Type</strong></td>
            <td><strong>Overaward</strong></td>
            <td><strong>Prevented Funding</strong></td>
            <td><strong>Total</strong></td>
        </tr>
        @foreach($case->funds as $fund)
        <tr>
            <td style="text-align: center;">{{ $fund->application_number }}</td>
            <td>{{ $fund->fundingType->funding_type }}</td>
            <td>${{ number_format($fund->over_award, 2, '.', ',') }}</td>
            <td>${{ number_format($fund->prevented_funding, 2, '.', ',') }}</td>
            <td>${{ number_format($fund->over_award + $fund->prevented_funding, 2, '.', ',') }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td style="text-align: center;"><strong>Totals</strong></td>
            <td><strong>${{ number_format($case->total_over_award, 2, '.', ',') }}</strong></td>
            <td><strong>${{ number_format($case->total_prevented_funding, 2, '.', ',') }}</strong></td>
            <td><strong>${{ number_format($case->total_award, 2, '.', ',') }}</strong></td>
        </tr>

    </table>
    <strong>COMMENTS:</strong>
    @foreach($case->comments as $comment)
        <p><strong>{{ $comment->staff_user_id }}  |  {{ $comment->comment_date }}</strong></p>
        <p style="white-space: pre-line; line-height: 1.2rem;">{{ $comment->comment_text }}</p>
        <hr/>
    @endforeach
</body>
</html>
