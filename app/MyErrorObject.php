<?php

namespace App;

class MyErrorObject
{
    public $id, $error;

    // public $profile_pictures = '/images/profile_pictures';
    // public $trashed_pictures = '/images/trashed_pictures';

    public $monthly_deposit_rate = 0.05;

    public $loan_statuses = array('started', 'running', 'finished');

    public $general_leave_catagories = array('Casual', 'Sick', 'Block');
    public $gender_specialized_leave_categories = array('Paternity', 'Maternity'); // ORDER MATTERS. DON'T SWAP OR MOVE, BUT YOU CAN PUSH
}
