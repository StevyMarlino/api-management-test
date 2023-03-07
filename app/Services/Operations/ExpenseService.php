<?php

namespace App\Services\Operations;

use App\Models\Expense;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function save($data)
    {
        try {
            DB::beginTransaction();
            $expense = Expense::create([
                'amount' => $data['amount'],
                'category_id' => $data['category_id'],
                'start_date' => Carbon::now()
           ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->error([], $e->getMessage(), 401);
            }

            DB::commit();
            return $expense;
    }
}