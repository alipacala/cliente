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
        $hospedajeCrear = [
            "nombre_producto" => "Hospedaje de prueba",
            "descripcion_del_producto" => "Descripcion de Hospedaje de prueba",
            "tipo" => "SVH",
            "fecha_de_vigencia" => "2023-10-27"
        ];

        $I->click("Productos");
        $I->click("Crear producto de hospedaje");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre del Producto", $hospedajeCrear["nombre_producto"]);
        $I->fillField("Descripción", $hospedajeCrear["descripcion_del_producto"]);
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->selectOption("Central de Costos", "Bazar");
        $I->fillField("Fecha de Vigencia", "10272023");

        $I->makeScreenshot("crear_hospedaje");

        $I->click("Guardar Ficha");

        $I->wait(.5);

        $I->seeInCurrentUrl("/cliente/views/listado-catalogo/");
        $I->seeInCurrentUrl("ok");

        $I->see("Hospedaje creado correctamente");
        $I->see("Listado de catálogo");

        $I->seeInDatabase("productos", $hospedajeCrear);
    }
}