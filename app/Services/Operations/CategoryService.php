<?php

namespace App\Services\Operations;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryService 
{
    public function add($data)
    {
        try {
            DB::beginTransaction();
            $category = Category::create($data);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->error([], $e->getMessage(), 401);
            }

            DB::commit();
            return $category;
    }

    public function list()
    {
        return Category::all();
    }
}