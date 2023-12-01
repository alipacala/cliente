<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class crearRecetaCest
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
        $recetaCrear = [
            "nombre_producto" => "Receta de prueba",
            "descripcion_del_producto" => "Descripcion de Receta de prueba",
            "tipo" => "RST",
            "tiempo_estimado" => "30",
            "fecha_de_vigencia" => "2023-10-27",
            "preparacion" => "Preparacion de Receta de prueba",
        ];

        $I->click("Productos");
        $I->click("Crear producto de receta");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre de la Receta", $recetaCrear["nombre_producto"]);
        $I->fillField("Descripción", $recetaCrear["descripcion_del_producto"]);
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->selectOption("Central de Costos", "Bazar");
        $I->fillField("Fecha de Vigencia", "10272023");
        $I->fillField("Tiempo de preparación", $recetaCrear["tiempo_estimado"]);
        $I->fillField("Preparación", $recetaCrear["preparacion"]);

        $insumos = [
            "Cebolla blanca",
            "Papa",
            "Carne",
            "Tomate"
        ];

        foreach ($insumos as $index => $insumo) {
            $I->click("Agregar Insumo");
            
            // muestra el modal de agregar insumo
            $I->wait(.2);
            $I->selectOption("Insumo", $insumo);
            $I->wait(.2);
            $I->fillField("Cantidad", $index + 1);
            $I->click("Agregar Ingrediente");

            // cierra el modal de agregar insumo
            $I->wait(.2);

            $I->scrollTo("#tabla-insumos");

            $I->makeScreenshot("crear_receta_" . $index);

            $I->see($insumo, "#tabla-insumos"); // nombre
            $I->see((string)($index + 1), "#tabla-insumos"); // cantidad
        }

        $I->makeScreenshot("crear_receta");

        $I->click("Guardar Ficha");

        $I->wait(.5);

        $I->seeInCurrentUrl("/listado-catalogo/");
        $I->seeInCurrentUrl("ok");

        $I->see("Receta creada correctamente");
        $I->see("Listado de catálogo");

        $I->seeInDatabase("productos", $recetaCrear);
    }
}