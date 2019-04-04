<?php
declare(strict_types=1);


namespace LotGD\Module\Res\Charstats\CharacterStats;


use LotGD\Core\Models\CharacterStats\BaseCharacterStat;

class ProgressBarCharacterStat extends BaseCharacterStat
{
    private $options;

    public function __construct(string $id, string $name, $value, int $weight = 0, array $options = [])
    {
        parent::__construct($id, $name, $value, $weight);
        $default_options = [
            "min" => 0,
            "max" => 100,
        ];

        foreach ($options as $option => $value) {
            if (isset($default_options[$option])) {
                $default_options[$option] = $value;
            }
        }
        $this->options = $default_options;
    }

    public function getMax(): float
    {
        return (float)$this->options["max"];
    }

    public function getMin(): float
    {
        return (float)$this->options["min"];
    }
}