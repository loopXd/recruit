<?php

namespace App\Filters\App\User;


use App\Filters\App\Traits\DateRangeFilter;
use App\Filters\FilterBuilder;

class UserFilter extends FilterBuilder
{
    use DateRangeFilter;

    public function search($search = null)
    {
        $this->groupSearch($search, ['name', 'email', 'phone']);
    }
    
    public function city($city = null)
    {
        $this->builder->when($city, function ($builder) use ($city) {
            $builder->whereHas('profile', function ($q) use ($city) {
                $q->where('address->city', 'LIKE', "%{$city}%");
            });
        });

        return $this;
    }
}