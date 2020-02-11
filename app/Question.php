<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'set_no', 'answer_text', 'correct_answer', 'year', 'is_answer_visible', 'created_by', 'updated_by',
    ];
}
