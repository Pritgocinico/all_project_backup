<?php

namespace App\Helpers;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Log;

class UserLogHelper
{
    protected $productRepository = "";
    public function __construct(ProductRepositoryInterface $productRepository)
    {
            $this->productRepository= $productRepository;
    }
    public static function storeLog($module,$text)
    {
        $data = [
            'user_id' => Auth()->user()->id,
            'module' =>$module,
            'log' =>$text,
        ];
        Log::create($data);
    }
}