<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotPreferensiAHP extends Model
{
    use HasFactory;

    protected $table = 'bobot_preferensi_ahp';
    protected $guarded = ['id'];
}
