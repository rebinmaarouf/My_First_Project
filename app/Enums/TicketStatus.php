<?php

namespace App\Enums;
enum TicketStatus: string{
    case Open = 'open';
    case APPROVED = 'approved' ;  
    case REJECTED = 'rejected';
}


?>