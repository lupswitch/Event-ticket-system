<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 'email', 'phonenumber', 'message',
    ];

    public function store(array $data) {
        $this->create($data);
    }

}
