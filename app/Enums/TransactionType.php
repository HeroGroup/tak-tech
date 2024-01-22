<?php
 
namespace App\Enums;

use App\Traits\ForSelect;
 
enum TransactionType: string
{
  use ForSelect;
  
  case INCREASE = 'افزایش';
  case DECREASE = 'کاهش';
}
