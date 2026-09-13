<?php

namespace App\Models;

use App\Core\Model;

class PricingRule extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT pr.*, p.name as product_name FROM pricing_rules pr JOIN products p ON pr.product_id = p.id WHERE pr.is_active = 1 ORDER BY pr.product_id, pr.priority');
    }

    public function create(array $data): int
    {
        return $this->insert('pricing_rules', $data);
    }

    public function deleteRule(int $id): void
    {
        $this->delete('pricing_rules', 'id = ?', [$id]);
    }

    public function rulesForProduct(int $productId): array
    {
        return $this->fetchAll('SELECT * FROM pricing_rules WHERE product_id = ? AND is_active = 1 ORDER BY priority DESC', [$productId]);
    }

    public function computePrice(int $productId, string $grade, ?float $size, ?int $ageDays, ?string $season, float $basePrice): float
    {
        $rules = $this->rulesForProduct($productId);
        if (empty($rules)) {
            return $basePrice;
        }
        foreach ($rules as $rule) {
            $match = true;
            if ($rule['grade'] !== '*' && $rule['grade'] !== $grade) {
                $match = false;
            }
            if ($rule['size_cm_min'] !== null && $rule['size_cm_min'] !== '') {
                if ($size === null || $size < (float) $rule['size_cm_min']) {
                    $match = false;
                }
            }
            if ($rule['size_cm_max'] !== null && $rule['size_cm_max'] !== '') {
                if ($size === null || $size > (float) $rule['size_cm_max']) {
                    $match = false;
                }
            }
            if ($rule['age_days_min'] !== null && $rule['age_days_min'] !== '') {
                if ($ageDays === null || $ageDays < (int) $rule['age_days_min']) {
                    $match = false;
                }
            }
            if ($rule['age_days_max'] !== null && $rule['age_days_max'] !== '') {
                if ($ageDays === null || $ageDays > (int) $rule['age_days_max']) {
                    $match = false;
                }
            }
            if ($rule['season'] && $rule['season'] !== '' && $rule['season'] !== $season) {
                $match = false;
            }
            if ($match) {
                if (!empty($rule['fixed_amount'])) {
                    return (float) $rule['fixed_amount'];
                }
                return round($basePrice * (float) $rule['multiplier'], 2);
            }
        }
        return $basePrice;
    }
}
