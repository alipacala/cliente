<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class crearPaqueteCest
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
            "nombre_producto" => "Paquete de prueba",
            "descripcion_del_producto" => "Descripcion de Paquete de prueba",
            "tipo" => "PAQ",
            "fecha_de_vigencia" => "2023-10-27",
        ];

        $I->click("Productos");
        $I->click("Crear producto de paquete / combo");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre del Paquete", $recetaCrear["nombre_producto"]);
        $I->fillField("Descripción", $recetaCrear["descripcion_del_producto"]);
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->fillField("Fecha de Vigencia", "10272023");

        $productos = [
            "Lomo Saltado",
            "Masaje relajante",
            "COCA COLA 1/2 LT",
        ];

        foreach ($productos as $index => $producto) {
            $I->click("Agregar Producto");
            
            // muestra el modal de agregar producto
            $I->wait(.2);
            $I->selectOption("Producto", $producto);
            $I->wait(.2);
            $I->fillField("Cantidad", $index + 1);
            $I->click("Agregar Producto", "#modal-insumo");

            // cierra el modal de agregar producto
            $I->wait(.2);

            $I->scrollTo("#tabla-insumos");

            $I->makeScreenshot("crear_paquete_" . $index);

            $I->see($producto, "#tabla-insumos"); // nombre
            $I->see((string)($index + 1), "#tabla-insumos"); // cantidad
        }

        $I->makeScreenshot("crear_paquete");

        $I->click("Guardar Ficha");

        $I->wait(.5);

        $I->seeInCurrentUrl("/cliente/views/listado-catalogo/");
        $I->seeInCurrentUrl("ok");

        $I->see("Paquete creado correctamente");
        $I->see("Listado de catálogo");

        $I->seeInDatabase("productos", $recetaCrear);
    }
}
