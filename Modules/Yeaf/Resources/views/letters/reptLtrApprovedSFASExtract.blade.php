On your StudentAid BC application for financial assistance, you indicated that you were a youth in continuing care or custody of a director of child welfare in B.C. on your 19th birthday.  Based on this information and through verification with the Ministry of Children and Families, it has been determined that you are eligible to receive a $5,500 non-repayable grant through the Youth Educational Assistance Fund (YEAF).
<br/><br/>
A cheque in the amount of $5,500.00 will be processed by {{ $grant->batch->batch_date }} and will be mailed to you at your home address within four to six weeks.  All grants are considered taxable income and you will be issued a T4A for the total amount of grant funding received from the YEAF program for each calendar year.  Please notify StudentAid BC of any change of address.
<br/><br/>
The intent of the YEAF grant is to help you with your educational costs and/or to reduce your student loan debt.  To make a payment on your BC Student Loan, please contact the BCSL Service Bureau at 1-877-535-7680.  To make a payment on your Canada Student Loan (CSL), please contact the National Student Loan Service Centre at 1-888-815-4514.
<br/><br/>
Please be advised that eligibility for this funding is dependent on completion of the study period indicated on your StudentAid BC application.  If you withdrew early, please return this cheque to StudentAid BC.  You do not have to apply for student financial assistance to be eligible for YEAF.  For more information on the Youth Educational Assistance Fund please visit our website at www.aved.gov.bc.ca/studentaidbc/specialprograms/yeaf.htm
<br/><br/>
We wish you continued success in your studies. If you have any questions, please contact me at
@if(is_null($officer))
    {{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$user->tele) }}.
@else
    {{ preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3',$officer->tele) }}.
@endif
<br/>
