<?php

namespace App;

use App\Loan;
use App\Services\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use Filterable;

    protected $fillable = [
        'amount', 'loan_id'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
