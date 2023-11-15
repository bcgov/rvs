I am pleased to advise you that your application for a grant under the {{ $grant->py->year_start }}/{{ $grant->py->year_end }} Youth Educational Assistance Fund for Former Youth in Care (YEAF) program year has been approved.
<br/><br/>
A cheque payable to you in the amount of ${{ $grant->total_yeaf_award }} will be processed {{ $grant->letter_text }} and will be mailed to your home address within four to six weeks of processing.
<br/><br/>
As all grants are considered taxable income, you will be issued a T4A for the total amount of grant funding received from the YEAF program for each calendar year.  Please notify StudentAid BC of change of your address.
<br/><br/>
The intent of this program is to help you with your educational costs and/or to reduce your Student Loan debt.  To make a payment on your BC Student Loan (BCSL) and Canada Student Loan (CSL), please contact the National Student Loans Service Centre (NSLSC) at 1-888-815-4514.
<br/><br/>
We wish you continued success in your studies.
<br/><br/>
If you have any questions, please contact me at
@if(is_null($officer))
{{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$user->tele) }}.
@else
{{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$officer->tele) }}.
@endif
<br/>
