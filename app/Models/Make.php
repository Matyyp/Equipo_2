<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    use HasFactory;

    public function make_service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function make_voucher()
    {
        return $this->belongsTo(Voucher::class,'id_voucher', 'id_voucher');
    }
}
