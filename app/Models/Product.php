<?php

namespace App\Models;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements ProductInterface
{
    use HasWallet;
    
    public function getAmountProduct(Customer $customer): int|string
    {
        return 100;
    }
    
    public function getMetaProduct(): ?array
    {
        return [
            'title' => $this->title,
            'description' => 'Purchase of Product #' . $this->id,
        ];
    }
}
