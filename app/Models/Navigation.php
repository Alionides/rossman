<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;
    protected $casts = [
        'top_nav' =>'json',
        'red_nav_top' =>'json',
        'red_nav_bottom' =>'json',
        'footer_about_nav' =>'json',
        'footer_customer_nav' =>'json',
        'footer_rossmanclub_nav' =>'json',
        'footer_rules_nav' =>'json',
    ];
}
