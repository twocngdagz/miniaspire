<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateLoan;
use App\Http\Requests\Api\DeleteLoan;
use App\Http\Requests\Api\UpdateLoan;
use App\Loan;
use App\Services\Filters\LoanFilter;
use App\Services\Paginate\Paginate;
use App\Services\Transformers\LoanTransformer;

class LoanController extends ApiController
{
    public function __construct(LoanTransformer $transformer)
    {
        $this->transformer = $transformer;

        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index(LoanFilter $filter)
    {
        $loans = new Paginate(Loan::loadRelations()->filter($filter));

        return $this->respondWithPagination($loans);
    }

    public function show(Loan $loan)
    {
        return $this->respondWithTransformer($loan);
    }

    public function update(UpdateLoan $request, Loan $loan)
    {
        if ($request->has('loan')) {
            $loan->update($request->get('loan'));
        }

        return $this->respondWithTransformer($loan);
    }

    public function store(CreateLoan $request)
    {
        $user = auth()->user();

        $loan = $user->loans()->create([
            'name' => $request->input('loan.name'),
            'amount' => $request->input('loan.amount'),
            'duration' => $request->input('loan.duration'),
            'fee' => $request->input('loan.fee'),
            'rate' => $request->input('loan.rate'),
            'frequency' => $request->input('loan.frequency'),
            'user_id' => $request->input('loan.user_id'),
        ]);

        return $this->respondWithTransformer($loan);
    }

    public function destroy(DeleteLoan $request, Loan $loan)
    {
        $loan->delete();

        return $this->respondSuccess();
    }
}
