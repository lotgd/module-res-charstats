<?php
declare(strict_types=1);

namespace LotGD\Module\Res\Charstats;

use LotGD\Core\Game;
use LotGD\Core\Events\EventContext;
use LotGD\Core\Models\Character;
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
        }

        return $context;
    }

    public static function onRegister(Game $g, ModuleModel $module) { }
    public static function onUnregister(Game $g, ModuleModel $module) { }
}
