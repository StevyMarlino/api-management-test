<?php

namespace App\Services\Operations;

use App\Models\Income;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeService 
{
    public function save($data)
    {
        try {
            DB::beginTransaction();
               $income = Income::create([
                    'amount' => $data['amount'],
                    'category_id' => $data['category_id'],
                    'start_date' => Carbon::now()
               ]);
               
            } catch (\Exception $e) {
                DB::rollBack();
                
                return response()->error([], $e->getMessage(), 401);
            }

            DB::commit();
            return $income;
    }
}