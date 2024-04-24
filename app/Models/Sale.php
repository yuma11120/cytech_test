<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $fillable = ['product_id'];


    public function getData(){
        $data = Sale::table($this->table)->get();
        return $data;
    }

    public function deleteStep72ById($id)
    {
        return $this->destroy($id);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
