<?php
namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class firstCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->maximizeWindow();
        $I->amOnPage("/login");
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        // al ingresar mis credenciales y dar click en iniciar sesion
        $I->fillField("usuario", "admin");
        $I->fillField("clave", "123");
        $I->click("Iniciar sesión");

        // esperar a que cargue la página
        $I->wait(.5);

        // debo estar en la pagina de inicio y ver las opciones del menu
        $I->seeCurrentUrlEquals("/");
        $I->see("Spa");
        $I->see("Hotel");
        $I->see("Productos");
        $I->see("Usuarios");
    }
}
