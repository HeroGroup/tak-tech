<?php
 
namespace App\Enums;

use App\Traits\ForSelect;

enum TransactionStatus: string
{
  use ForSelect;
  
  case PENDING = 'در انتظار پرداخت';
  case PAYMENT_SUCCESSFUL = 'پرداخت موفق';
  case PAYMENT_FAILED = 'پرداخت ناموفق';

}
