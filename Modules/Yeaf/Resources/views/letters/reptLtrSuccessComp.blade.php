Congratulations on completing your studies.
<br/><br/>
If you meet the necessary criteria, you may be eligible for an additional bursary for the next program year.
<br/><br/>
We wish you continued success in your studies.
<br/><br/>
If you have any questions, please contact me at
@if(is_null($officer))
{{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$user->tele) }}.
@else
{{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$user->tele) }}.
@endif
<br/>
