<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Controllers;

use PhpMyAdmin\Controllers\CheckRelationsController;
use PhpMyAdmin\Http\ServerRequest;
use PhpMyAdmin\Relation;
use PhpMyAdmin\Template;
use PhpMyAdmin\Tests\AbstractTestCase;
use PhpMyAdmin\Tests\Stubs\ResponseRenderer;

/**
 * @covers \PhpMyAdmin\Controllers\CheckRelationsController
 */
class CheckRelationsControllerTest extends AbstractTestCase
{
    public function testCheckRelationsController(): void
    {
        $GLOBALS['server'] = 1;
        $GLOBALS['text_dir'] = 'ltr';
        $GLOBALS['PMA_PHP_SELF'] = 'index.php';

        $request = $this->createStub(ServerRequest::class);
        $request->method('getParsedBodyParam')->willReturnMap([
            ['create_pmadb', null, null],
            ['fixall_pmadb', null, null],
            ['fix_pmadb', null, null],
        ]);

        $response = new ResponseRenderer();
        $template = new Template();
        $controller = new CheckRelationsController($response, $template, new Relation($this->dbi, $template));
        $controller($request);

        $actual = $response->getHTMLResult();

        $this->assertStringContainsString('phpMyAdmin configuration storage', $actual);
        $this->assertStringContainsString(
            'Configuration of pmadb…      <span class="text-danger"><strong>not OK</strong></span>',
            $actual
        );
        $this->assertStringContainsString(
            'Create</a> a database named \'phpmyadmin\' and setup the phpMyAdmin configuration storage there.',
            $actual
        );
    }
}
