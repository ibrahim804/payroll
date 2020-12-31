<?php

namespace App;

class MyErrorObject
{
    public $id, $error;
    public $profile_pictures = '/images/profile_pictures';
    public $trashed_pictures = '/images/trashed_pictures';
    public $deposit_rate = 0.1;
    public $pf_yearly_rate = 0.12;
    public $loan_statuses = array('started', 'running', 'finished');
    public $casual_gift = 22, $sick_gift = 6;
}
