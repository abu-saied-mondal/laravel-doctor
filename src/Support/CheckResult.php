<?php

namespace LaravelDoctor\Support;

class CheckResult
{
    public function __construct(
        protected string $checkName,
        protected string $status,
        protected Severity $severity,
        protected string $message,
        protected ?string $recommendation = null,
        protected bool $repairAvailable = false,
        protected ?string $repairCommand = null
    ) {}

    public static function make(
        string $checkName,
        string $status,
        Severity $severity,
        string $message,
        ?string $recommendation = null,
        bool $repairAvailable = false,
        ?string $repairCommand = null
    ): self {
        return new self($checkName, $status, $severity, $message, $recommendation, $repairAvailable, $repairCommand);
    }

    public static function success(string $checkName, string $message): self
    {
        return new self($checkName, 'success', Severity::LOW, $message);
    }

    public static function warning(
        string $checkName,
        string $message,
        ?string $recommendation = null,
        bool $repairAvailable = false,
        ?string $repairCommand = null,
        Severity $severity = Severity::MEDIUM
    ): self {
        return new self($checkName, 'warning', $severity, $message, $recommendation, $repairAvailable, $repairCommand);
    }

    public static function fail(
        string $checkName,
        string $message,
        ?string $recommendation = null,
        bool $repairAvailable = false,
        ?string $repairCommand = null,
        Severity $severity = Severity::HIGH
    ): self {
        return new self($checkName, 'fail', $severity, $message, $recommendation, $repairAvailable, $repairCommand);
    }

    public function getCheckName(): string
    {
        return $this->checkName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function isRepairAvailable(): bool
    {
        return $this->repairAvailable;
    }

    public function getRepairCommand(): ?string
    {
        return $this->repairCommand;
    }

    public function isPassed(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'fail';
    }

    public function isWarning(): bool
    {
        return $this->status === 'warning';
    }
}
