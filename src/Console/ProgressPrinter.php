<?php

namespace LaravelDoctor\Console;

use Illuminate\Console\Command;

class ProgressPrinter
{
    public function __construct(protected Command $command) {}

    public function start(int $steps): void
    {
        $this->command->getOutput()->writeln('<comment>Starting Laravel Doctor diagnostics...</comment>');
        $this->command->getOutput()->newLine();
    }

    public function advance(string $checkName): void
    {
        $this->command->getOutput()->writeln(" Running check: <info>{$checkName}</info>...");
    }

    public function finish(): void
    {
        $this->command->getOutput()->newLine();
        $this->command->getOutput()->writeln('<info>Diagnostics completed!</info>');
        $this->command->getOutput()->newLine();
    }
}
