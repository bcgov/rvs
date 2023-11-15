We have been advised that you did not attend/withdrew from full-time studies prior to the academic term-end indicated on your {{ $grant->py->year_start }}/{{ $grant->py->year_end }} Youth Educational Assistance Fund (YEAF) application.
Since this withdrawal does not comply with the terms under which the YEAF grant was approved, it is important that you carefully note that you are in an 'Overaward'.
This means that if you were to apply next program year, the overaward amount will be deducted from your next YEAF entitlement.
<br/><br/>
As a result of your withdrawal on {{ date('F d, Y', strtotime($grant->withdrawal_date)) }}, you are currently in a YEAF grant overaward of ${{ $grant->overaward }}. If you have not cashed the YEAF grant, please return it to the Victoria Foundation at, 200-703 Broughton Street, Victoria BC V8W 1E2, for cancellation and reduction of the overaward.
<br/><br/>
If you have any questions, please contact me at
@if(is_null($officer))
    {{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$user->tele) }}.
@else
    {{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$officer->tele) }}.
@endif
<br/>
