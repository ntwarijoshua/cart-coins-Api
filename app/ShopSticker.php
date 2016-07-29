<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopSticker extends Model
{
    use SoftDeletes;
    protected $date = ['deleted_at'];
    protected $table = 'shop_sticker';
    protected $fillable = ['name','payment_type','company_id','sticker_id','price','currency','completed'];

    public function company(){
        return $this->belongsTo('App\Company');
    }
    public function sticker(){
        return $this->belongsTo('App\Sticker');
    }
}
