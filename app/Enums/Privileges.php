<?php
 
namespace App\Enums;

use App\Traits\ForSelect;
 
enum Privileges: string
{
  use ForSelect;

  case CATEGORIES = 'categories';
  case PRODUCTS = 'products';
  case SERVICES = 'services';
  case ORDERS = 'orders';
  case TRANSACTIONS = 'transactions';
  case DISCOUNTS = 'discounts';
  case USERS = 'users';

}
