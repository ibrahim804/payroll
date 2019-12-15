@component('mail::message')

# Hey, {{ $full_name }} !!

Your Payment Information are as follows:

<div align="center">
    Basic Salary: <b> {{$basic}} </b> BDT <br>
    House Rent Allowance: <b> {{$house}} </b> BDT <br>
    Medical Allowance: <b> {{$medical}} </b> BDT <br>
    Fuel Allowance: <b> {{$fuel}} </b> BDT <br>
    Phone Bill Allowance: <b> {{$phone}} </b> BDT <br>
    Special Allowance: <b> {{$special}} </b> BDT <br>
    Others Allowance: <b> {{$other_a}} </b> BDT <br>
    Tax Deduction: <b> {{$tax}} </b> BDT <br>
    Provident Fund: <b> {{$pf}} </b> BDT <br>
    Others Deduction: <b> {{$other_d}} </b> BDT <br>
    Gross Salary: <b> {{$gross}} </b> BDT <br>
    Total Deduction: <b> {{$total_d}} </b> BDT <br>
    Net Salary: <b> {{$net}} </b> BDT <br>
    Unpaid Leave Taken: <b> {{$unpaid}} </b> Day(s) <br>
    Salary Deduction For Unpaid Leave: <b> {{$leave_d}} </b> BDT <br>
    Paid Amount: <b> {{$payable}} </b> BDT <br>
</div>

Thank you. <br>
@endcomponent
