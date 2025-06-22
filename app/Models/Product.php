<?php

namespace App\Models;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model implements ProductInterface
{
    use HasWallet, HasFactory;
    
    public function getAmountProduct(Customer $customer): int|string
    {
        return $this->quantity;
    }
    
    public function getMetaProduct(): ?array
    {
        return [
            'name' => $this->name,
            'description' => 'Purchase of Product #' . $this->id,
        ];
    }
    
    public function packages(): HasMany{
        return $this->hasMany(Package::class);
    }
}
