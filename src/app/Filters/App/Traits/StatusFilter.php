<?php


namespace App\Filters\App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait StatusFilter
{
    public function status($ids = null)
    {
        $this->builder->when($ids, function (Builder $builder) use ($ids) {
            $builder->whereIn('status_id', explode(',', $ids));
        });
    }
}