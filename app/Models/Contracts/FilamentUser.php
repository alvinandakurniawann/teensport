<?php

namespace App\Models\Contracts;

interface FilamentUser
{
    public function canAccessPanel(): bool;
}