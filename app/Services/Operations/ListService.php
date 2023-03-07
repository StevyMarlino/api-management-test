<?php 

namespace App\Services\Operations;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Carbon;

class ListService
{
    public function allList()
    {
        $incomes = Income::whereBetween('created_at', [Carbon::now()->subMonth(3), Carbon::now()])->get();
        $expenses = Expense::whereBetween('created_at', [Carbon::now()->subMonth(3), Carbon::now()])->get();

        $data = [
            'incomes' => $incomes,
            'expenses' =>  $expenses
        ];

        return $data;
    }

    public function myList()
    {
        $incomes = Income::whereBetween('created_at', [Carbon::now()->subMonth(3), Carbon::now()])
            ->where('user_id',auth()->user()->id)
            ->get();
        $expenses = Expense::whereBetween('created_at', [Carbon::now()->subMonth(3), Carbon::now()])
            ->where('user_id',auth()->user()->id)
            ->get();

        $data = [
            'incomes' => $incomes,
            'expenses' =>  $expenses
        ];

        return $data;
    }
}