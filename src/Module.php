<?php
declare(strict_types=1);

namespace LotGD\Module\Res\Charstats;

use LotGD\Core\Game;
use LotGD\Core\Events\EventContext;
use LotGD\Core\Models\Character;
use LotGD\Core\Models\CharacterStatGroup;
use LotGD\Core\Models\CharacterStats\BaseCharacterStat;
use LotGD\Core\Models\CharacterStats;
use LotGD\Core\Module as ModuleInterface;
use LotGD\Core\Models\Module as ModuleModel;

class Module implements ModuleInterface {
    public static function handleEvent(Game $g, EventContext $context): EventContext
    {
        if ($context->getEvent() === "h/lotgd/core/characterStats/populate") {
            /** @var CharacterStats $stats */
            $stats = $context->getDataField("stats");
            /** @var Character $character */
            $character = $context->getDataField("character");

            $groups = [
                "vital" =>  new CharacterStatGroup("lotgd/res/charstats/vitalInfo", "Vital info", 0),
                "additional" =>  new CharacterStatGroup("lotgd/res/charstats/additionalInfo", "Additional info", 100),
            ];

            $vitalStats = [
                new BaseCharacterStat(
                    "lotgd/res/charstats/vitalInfo/name", "Name", $character->getDisplayName(), 0
                ), new BaseCharacterStat(
                    "lotgd/res/charstats/vitalInfo/alive", "Alive", $character->isAlive() ? "Yes" : "No", 100
                ), new BaseCharacterStat(
                    "lotgd/res/charstats/vitalInfo/health", "Health", $character->getHealth(), 200
                ),
            ];

            foreach ($vitalStats as $stat) {
                $groups["vital"]->addCharacterStat($stat);
            }

            $additionalStats = [];
            if ($g->getModuleManager()->getModule("lotgd/module-res-fight")) {
                $additionalStats[] = new BaseCharacterStat(
                    "lotgd/res/charstats/additionalInfo/experience", $character->getExperience(), 100
                );
            }

            foreach ($additionalStats as $stat) {
                $groups["additional"]->addCharacterStat($stat);
            }

            foreach ($groups as $group) {
                $stats->addCharacterStatGroup($group);
            }
        }

        return $context;
    }

    public static function onRegister(Game $g, ModuleModel $module) { }
    public static function onUnregister(Game $g, ModuleModel $module) { }
}
