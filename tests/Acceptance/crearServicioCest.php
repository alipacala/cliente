<?php
namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class crearServicioCest
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
        $servicioCrear = [
            "nombre_producto" => "Servicio de prueba",
            "descripcion_del_producto" => "Descripcion de Servicio de prueba",
            "tipo" => "SRV",
            "tiempo_estimado" => "30",
            "requiere_programacion" => "1",
            "codigo_habilidad" => "546",
            "fecha_de_vigencia" => "2023-10-27",
        ];

        $I->click("Productos");
        $I->click("Crear producto de servicio");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->fillField("Nombre del Servicio", $servicioCrear["nombre_producto"]);
        $I->fillField("Descripción", $servicioCrear["descripcion_del_producto"]);
        $I->fillField("Tiempo estimado", $servicioCrear["tiempo_estimado"]);
        $I->checkOption("Sí"); // ¿Requiere programación?
        $I->selectOption("Habilidad del terapeuta:", "546 - Masaje N1");
        $I->selectOption("Clasificación en Catálogo de Ventas", "Bazar");
        $I->selectOption("Central de Costos", "Bazar");
        $I->fillField("Fecha de Vigencia", "10272023");

        $I->click("Agregar Insumo");
        // muestra el modal de agregar insumo
        $I->wait(.2);
        $I->selectOption("Insumo", "Loción de masaje");
        $I->wait(.2);
        $I->fillField("Cantidad", "2");
        $I->click("Agregar Insumo", "#modal-insumo");
        // cierra el modal de agregar insumo
        $I->wait(.2);

        $I->scrollTo("#tabla-insumos");
        
        $I->see("Loción de masaje", "#tabla-insumos"); // nombre
        $I->see("2", "#tabla-insumos"); // cantidad

        $I->makeScreenshot("crear_servicio");

        $I->click("Guardar Ficha");

        $I->wait(.5);

        $I->seeInCurrentUrl("/cliente/views/listado-catalogo/");
        $I->seeInCurrentUrl("ok");

        $I->see("Servicio creado correctamente");
        $I->see("Listado de catálogo");

        $I->seeInDatabase("productos", $servicioCrear);
    }
}
