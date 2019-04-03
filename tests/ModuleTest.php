<?php
declare(strict_types=1);

namespace LotGD\Module\Res\Charstats\Tests;

use LotGD\Core\Models\Character;
use LotGD\Core\Models\CharacterStats;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

use LotGD\Core\Configuration;
use LotGD\Core\Game;
use LotGD\Core\Models\Module as ModuleModel;
use LotGD\Core\Tests\ModelTestCase;

use LotGD\Module\Res\Charstats\Module;

class ModuleTest extends ModuleTestCase
{
    protected $dataset = "module";

    // TODO for LotGD staff: this test assumes the schema in their yaml file
    // reflects all columns in the core's models of characters, scenes and modules.
    // This is pretty fragile since every time we add a column, everyone's tests
    // will break.
    public function testUnregister()
    {
        Module::onUnregister($this->g, $this->moduleModel);
        $m = $this->getEntityManager()->getRepository(ModuleModel::class)->find(self::Library);
        $m->delete($this->getEntityManager());

        // Assert that databases are the same before and after.
        // TODO for module author: update list of tables below to include the
        // tables you modify during registration/unregistration.
        $tableList = [
            'characters', 'scenes', "event_subscriptions"
        ];

        $after = $this->getConnection()->createDataSet($tableList);
        $before = $this->getDataSet();

        foreach($tableList as $table) {
            $this->assertSame($before->getTable($table)->getRowCount(), $after->getTable($table)->getRowCount());
        }

        // Since tearDown() contains an onUnregister() call, this also tests
        // double-unregistering, which should be properly supported by modules.
    }

    public function testHandleUnknownEvent()
    {
        // Always good to test a non-existing event just to make sure nothing happens :).
        $context = new \LotGD\Core\Events\EventContext(
            "e/lotgd/tests/unknown-event",
            "none",
            \LotGD\Core\Events\EventContextData::create([])
        );

        // The Module expects to have a character present, so we need to set one.
        $game = $this->g;
        $character = $this->getEntityManager()->getRepository(Character::class)->find("10000000-0000-0000-0000-000000000001");
        $game->setCharacter($character);

        Module::handleEvent($this->g, $context);
    }

    public function testSomething()
    {
        $character = $this->getEntityManager()->getRepository(Character::class)->find("10000000-0000-0000-0000-000000000001");

        $charstats = new CharacterStats($this->g, $character);

        $i = 0;
        $j = 0;
        foreach ($charstats->iterate() as $group) {
            $i++;
            foreach ($group->iterate() as $stat) {
                $j++;
            }
        }

        $this->assertSame(2, $i);
        $this->assertSame(3, $j);
    }
}
