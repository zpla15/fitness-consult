<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessConsult
{
    public $name;
    public $type;
    public $tags;

    public function __construct($name, $type=null, $tags)
    {
        $this->type = $type;
        $this->name = $name;
        $this->tags = $tags;
    }
}
