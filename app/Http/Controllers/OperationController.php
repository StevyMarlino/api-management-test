<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\IncomeRequest;
use App\Services\Operations\ExpenseService;
use App\Services\Operations\IncomeService;
use App\Services\Operations\ListService;
use App\Services\Users\UserListService;

class OperationController extends Controller
{
    public function listUsers()
    {
        try {
           $list = (new UserListService)->list();
        } catch(\Exception $e) {
            return response()->error([], $e->getMessage(), 401);
        }

        return response()->success($list,'all User List', 201);
    }

    public function addIncome(IncomeRequest $request)
    {
        try {
            (new IncomeService)->save($request);
        } catch(\Exception $e) {
            return response()->error([], $e->getMessage(), 401);
        }

        return response()->success([],'New Income added', 201);
    }

    public function addExpense(ExpenseRequest $request)
    {
        (new ExpenseService)->save($request);

        return response()->success([],'New Expense added', 201);
    }

    public function myOperations()
    {
        try {
          $data =  (new ListService)->myList();
        } catch(\Exception $e) {
            return response()->error([], $e->getMessage(), 401);
        }

        return response()->success($data, 'My Last 3 Mooth Operation', 200);
    }

    public function allOperation()
    {
        try {
          $data =  (new ListService)->myList();
        } catch(\Exception $e) {
            return response()->error([], $e->getMessage(), 401);
        }

        return response()->success($data, 'My Last 3 Mooth Operation', 200);
    }
}
