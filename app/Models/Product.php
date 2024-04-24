<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['product_name', 'price', 'stock', 'company_name', 'comment', 'img_path'];


    public function getData(){
        $data = Product::table($this->table)->get();
        return $data;
    }

    public function deleteStep72ById($id)
    {
        return $this->destroy($id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
