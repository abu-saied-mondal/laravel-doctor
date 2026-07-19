<?php

namespace LaravelDoctor\Contracts;

use LaravelDoctor\Support\CheckResult;

interface Check
{
    
    public function check(): CheckResult;
}
