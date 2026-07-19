<?php

namespace LaravelDoctor\Contracts;

interface Reporter
{
    
    public function report(array $results): string;
}
