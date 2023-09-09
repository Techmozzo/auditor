<?php

namespace App\Interfaces;

interface Types{
    const USERS = [
        'AUDITOR' => 'AUDITOR',
        'BANKER' => 'BANKER',
        'ADMINISTRATOR' => 'ADMINISTRATOR'
    ];

    const STATUS = [
        'PENDING' => 'PENDING',
        'APPROVED' => 'APPROVED',
        'DECLINED' => 'DECLINED',
        'CANCELLED' => 'CANCELLED'
    ];
}


