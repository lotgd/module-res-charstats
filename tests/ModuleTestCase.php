<?php
declare(strict_types=1);

namespace LotGD\Module\Res\Charstats\Tests;


use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Events as DoctrineEvents;
use LotGD\Core\Doctrine\EntityPostLoadEventListener;
use LotGD\Core\LibraryConfigurationManager;
use LotGD\Core\ModelExtender;
use LotGD\Core\Models\EventSubscription;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

use LotGD\Core\Configuration;
use LotGD\Core\Game;
use LotGD\Core\GameBuilder;
use LotGD\Core\Tests\ModelTestCase;
use LotGD\Core\Models\Module as ModuleModel;

use LotGD\Module\Res\Charstats\Module;
use Symfony\Component\Yaml\Yaml;

class ModuleTestCase extends ModelTestCase
{
    const Library = 'lotgd/module-res-charstats';
    const RootNamespace = "LotGD\\Module\\Res\\Charstats\\";

    public $g;
    protected $moduleModel;

    public function getDataSet(): array
    {
        return Yaml::parseFile(implode(DIRECTORY_SEPARATOR, [__DIR__, 'datasets', $this->dataset . '.yml']));
    }

    public function getCwd(): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, '..']);
    }

    public function setUp(): void
    {
        parent::setUp();

        // Register and unregister before/after each test, since
        // handleEvent() calls may expect the module be registered (for example,
        // if they read properties from the model).
        $this->moduleModel = new ModuleModel(self::Library);
        $this->moduleModel->save($this->getEntityManager());
        Module::onRegister($this->g, $this->moduleModel);

        $this->g->getEntityManager()->flush();
        $this->g->getEntityManager()->clear();
    }

    public function tearDown(): void
    {
        Module::onUnregister($this->g, $this->moduleModel);
        $m = $this->getEntityManager()->getRepository(ModuleModel::class)->find(self::Library);
        if ($m) {
            $m->delete($this->getEntityManager());
        }

        $this->g->getEntityManager()->flush();
        $this->g->getEntityManager()->clear();

        parent::tearDown();
    }
}