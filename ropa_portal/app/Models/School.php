<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class School extends Model
{
    protected $fillable = ['college_id', 'name'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function scopeForCollege(Builder $query, int $collegeId): Builder
    {
        return $query->where('college_id', $collegeId);
    }
}
