<?php
 
namespace App\Enums;

use App\Traits\ForSelect;
 
enum TransactionReason: string
{
  use ForSelect;
  
  case CHARGE = 'شارژ کیف پول';
  case PAYMENT = 'پرداخت سفارش';
  case TRANSFER = 'انتقال';
}
