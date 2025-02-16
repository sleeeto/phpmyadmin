<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Controllers\Server\Databases;

use PhpMyAdmin\Controllers\Server\Databases\DestroyController;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Relation;
use PhpMyAdmin\RelationCleanup;
use PhpMyAdmin\Template;
use PhpMyAdmin\Tests\AbstractTestCase;
use PhpMyAdmin\Tests\Stubs\ResponseRenderer;
use PhpMyAdmin\Transformations;

use function __;

/**
 * @covers \PhpMyAdmin\Controllers\Server\Databases\DestroyController
 */
class DestroyControllerTest extends AbstractTestCase
{
    public function testDropDatabases(): void
    {
        global $cfg;

        $GLOBALS['server'] = 1;
        $GLOBALS['text_dir'] = 'ltr';
        $GLOBALS['PMA_PHP_SELF'] = 'index.php';

        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response = new ResponseRenderer();
        $response->setAjax(true);

        $cfg['AllowUserDropDatabase'] = true;

        $template = new Template();
        $controller = new DestroyController(
            $response,
            $template,
            $dbi,
            new Transformations(),
            new RelationCleanup($dbi, new Relation($dbi, $template))
        );

        $controller();
        $actual = $response->getJSONResult();

        $this->assertArrayHasKey('message', $actual);
        $this->assertStringContainsString('<div class="alert alert-danger" role="alert">', $actual['message']);
        $this->assertStringContainsString(__('No databases selected.'), $actual['message']);
    }
}
