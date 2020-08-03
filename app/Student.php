<?php

namespace App;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;



class Student extends Authenticatable
{

	use Notifiable,HasApiTokens;
    
}
