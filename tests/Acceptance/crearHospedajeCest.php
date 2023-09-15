<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class crearHospedajeCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->maximizeWindow();
        $I->amOnPage("/login");

        $I->fillField("usuario", "admin");
        $I->fillField("clave", "123");
        $I->click("Iniciar sesión");
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->click("Productos");
        $I->click("Crear producto de hospedaje");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre del Producto", "Hospedaje de prueba");
        $I->fillField("Descripción", "Descripcion de Hospedaje de prueba");
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->selectOption("Central de Costos", "Bazar");
        $I->fillField("Fecha de Vigencia", "10042023");

        $I->makeScreenshot("crear_hospedaje");

        $I->click("Guardar Ficha");
    }
}
