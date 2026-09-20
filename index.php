<?php

class SecurityService
{
    private array $attemptsLog = [];

    public function isValidPassword(string $password): bool
    {
        $isValid = strlen($password) >= 8 && preg_match('/[0-9]/', $password);

        if (!$isValid) {
            $this->attemptsLog[] = [
                'time' => date('Y-m-d H:i:s'),
                'status' => 'FAILED_VALIDATION'
            ];
            
            // Send warning to security team
            mail('sec@example.com', 'Failed validation attempt', 'Password failed checks');
        }

        return $isValid;
    }

    public function getLogs(): array
    {
        return $this->attemptsLog;
    }
}

// Tests
$sec = new SecurityService();
var_dump($sec->isValidPassword('weak'));
var_dump($sec->isValidPassword('strongPass123'));
print_r($sec->getLogs());
