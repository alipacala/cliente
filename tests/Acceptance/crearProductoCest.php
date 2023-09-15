<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class crearProductoCest
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
        $I->click("Crear producto terminado / insumo");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre del Producto", "Producto de prueba");
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->selectOption("Central de Costos", "Bazar");
        $I->selectOption("Tipo de Producto", "Insumo");
        $I->fillField("Stock Mínimo Temp. Baja", "1");
        $I->fillField("Stock Mínimo Temp. ALTA", "2");
        $I->fillField("Stock Máximo Temp. Baja", "3");
        $I->fillField("Stock Máximo Temp. ALTA", "4");

        $I->makeScreenshot("crear_producto");

        $I->click("Guardar Ficha");

        $I->wait(.5);
        
        $I->seeCurrentUrlEquals("/cliente/views/listado-catalogo/");
        $I->see("Listado de catálogo");

        $I->seeInDatabase("productos", [
            "nombre_producto" => "Producto de prueba",
            "tipo" => "PRD",
            "stock_min_temporada_baja" => 1,
            "stock_min_temporada_alta" => 2,
            "stock_max_temporada_baja" => 3,
            "stock_max_temporada_alta" => 4,
        ]);
    }
}
