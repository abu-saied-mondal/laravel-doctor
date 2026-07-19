<?php

namespace LaravelDoctor\Support;

class HealthScore
{
    
    public static function calculate(array $results): int
    {
        if (empty($results)) {
            return 100;
        }

        $score = 100;

        foreach ($results as $result) {
            if ($result->isFailed()) {
                $score -= match ($result->getSeverity()) {
                    Severity::CRITICAL => 30,
                    Severity::HIGH => 20,
                    Severity::MEDIUM => 10,
                    Severity::LOW => 5,
                };
            } elseif ($result->isWarning()) {
                $score -= match ($result->getSeverity()) {
                    Severity::CRITICAL => 15,
                    Severity::HIGH => 10,
                    Severity::MEDIUM => 5,
                    Severity::LOW => 2,
                };
            }
        }

        return max(0, $score);
    }
}
