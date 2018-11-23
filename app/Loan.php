<?php

namespace App;

use App\Repayment;
use App\Services\Filters\Filterable;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use Filterable;

    protected $fillable = [
        'name', 'amount', 'duration', 'fee', 'rate', 'frequency', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function scopeLoadRelations($query)
    {
        return $query->with('user')
        ->withCount('user');
    }

    public function totalRepayments()
    {
        return $this->repayments->sum('amount');
    }

    public function ratePerPeriod()
    {
        return ($this->rate/100) / $this->duration;
    }


    public function repayment()
    {
        $value1 = $this->ratePerPeriod() * pow((1 + $this->ratePerPeriod()), $this->duration);
        $value2 = pow((1 + $this->ratePerPeriod()), $this->duration) - 1;
        return $this->amount * ($value1 / $value2);
    }

    public function balance()
    {
        $balance = $this->repayments->reduce(function ($previousBalance, $repayment) {
            $interest = $this->ratePerPeriod() * $previousBalance;
            $principal = $repayment->amount - $interest;
            $currentBalance = $previousBalance - $principal;
            return $currentBalance;
        }, $this->amount);

        return round($balance, 2);
    }
}
