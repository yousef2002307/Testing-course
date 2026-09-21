<?php

class MemberRewardService
{
    private array $rewardAuditTrail = [];

    public function getMemberRewardPoints(array $memberRecord, ?array $purchaseHistory = null): int
    {
        // Guard 1: Missing purchases
        //no need for thiss special case can be relaced wit p[orders] ?? [] in foreach
        if (!isset($purchaseHistory['orders']) || count($purchaseHistory['orders']) === 0) {
            return 0;
        }

        // Calculate spending
        $totalSpent = 0.0;
        foreach ($purchaseHistory['orders'] as $order) {
            $totalSpent += $order['amount'] ?? 0.0;
        }

        // Calculate points
        $points = (int) floor($totalSpent / 50);

        // Tier bonus logic
        //too complex movee it to helper function with match instead of  : ?
        $tier = $memberRecord['grade'] ?? 'STANDARD';
        $finalPoints = ($tier === 'GOLD') 
            ? ($points > 100 ? $points * 2 + 50 : $points * 2) 
            : (($tier === 'SILVER') ? (int)($points * 1.5) : $points);

        // Record audit
        //unexpected behaviour this only get the members
        if ($finalPoints > 0) {
            $this->rewardAuditTrail[] = [
                'member_id' => $memberRecord['id'] ?? null,
                'points'    => $finalPoints,
                'date'      => date('Y-m-d H:i:s'),
            ];
        }

        return $finalPoints;
    }

    public function getAuditTrail(): array
    {
        return $this->rewardAuditTrail;
    }
}

// Tests
$service = new MemberRewardService();

$member = ['id' => 42, 'grade' => 'GOLD'];
$history = [
    'orders' => [
        ['amount' => 1200.0],
        ['amount' => 3800.0],
    ]
];

echo "Points earned: " . $service->getMemberRewardPoints($member, $history) . "\n";
print_r($service->getAuditTrail());

