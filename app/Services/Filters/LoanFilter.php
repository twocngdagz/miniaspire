<?php

namespace App\Services\Filters;

class LoanFilter extends Filter
{
    protected function user($name)
    {
        $user = User::whereName($name)->first();

        $userId = $user ? $user->id : null;

        return $this->builder->whereUserId($userId);
    }
}
