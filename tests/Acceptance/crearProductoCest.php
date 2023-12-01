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
        $productoCrear = [
            "nombre_producto" => "Producto de prueba",
            "tipo" => "PRD",
            "tipo_de_unidad" => "UNIDAD",
            "fecha_de_vigencia" => "2023-10-27",
            "stock_min_temporada_baja" => 1,
            "stock_min_temporada_alta" => 2,
            "stock_max_temporada_baja" => 3,
            "stock_max_temporada_alta" => 4,
        ];

        $I->click("Productos");
        $I->click("Crear producto terminado / insumo");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre del Producto", $productoCrear["nombre_producto"]);
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->selectOption("Central de Costos", "Bazar");
        $I->selectOption("Tipo de Producto", "Producto Venta");
        $I->fillField("Fecha de Vigencia del Producto", "10272023");
        $I->fillField("Cantidad de Unidades", "1");

        $I->fillField("Stock Mínimo Temp. Baja", $productoCrear["stock_min_temporada_baja"]);
        $I->fillField("Stock Mínimo Temp. ALTA", $productoCrear["stock_min_temporada_alta"]);
        $I->fillField("Stock Máximo Temp. Baja", $productoCrear["stock_max_temporada_baja"]);
        $I->fillField("Stock Máximo Temp. ALTA", $productoCrear["stock_max_temporada_alta"]);

        $I->makeScreenshot("crear_producto");

        $I->click("Guardar Ficha");

        $I->wait(.5);

        $I->seeInCurrentUrl("/listado-catalogo/");
        $I->seeInCurrentUrl("ok");

        $I->see("Producto creado correctamente");
        $I->see("Listado de catálogo");

        $I->seeInDatabase("productos", $productoCrear);
    }
}