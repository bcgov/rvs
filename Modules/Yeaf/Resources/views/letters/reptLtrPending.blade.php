Our office is currently in receipt of your application for the Youth Educational Assistance Fund.  Your application is being held pending receipt of the information as indicated below.
<br/><br/>
<ul>
    <li>{{ $grant->custom_pending_reason }}</li>
    @foreach($grant->grantPendingIneligibles as $in)
        @if(stripos($in->ineligible->paragraph_text, '~~PROGRAMYR~~') > 0)
            <li>{{ str_replace('~~PROGRAMYR~~', $grant->py->year_start . '/' . $grant->py->year_end, $in->ineligible->paragraph_text) }}</li>
        @elseif(stripos($in->ineligible->paragraph_text, '~~AGE~~') > 0)
            <li>{{ str_replace('~~AGE~~', $grant->age, $in->ineligible->paragraph_text) }}</li>
        @else
            <li>{{ $in->ineligible->paragraph_text }}</li>
        @endif
    @endforeach
</ul>
<br/><br/>
The Youth Education Assistance Fund program runs from August 1 to July 31 of each year.  Applications for the new program year will be available in July of each year.  You may download all required documentation on our web site at www.StudentAidBC.ca
<br/><br/>
If you have any questions, please contact me at
@if(is_null($officer))
    {{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$user->tele) }}.
@else
    {{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$officer->tele) }}.
@endif
<br/>
