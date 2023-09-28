<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class deRegistroClienteSpaHastaFacturaCest
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
        $I->click("Spa");
        $I->click("Registro Spa");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        // buscar un cliente existente
        $I->fillField("#dni_titular", "76368626"); // Abraham
        $I->wait(.2);

        // le quitamos el foco al input
        $I->executeJS('document.querySelector("#dni_titular").blur();');

        $I->wait(.2);

        $I->seeInField("#apellidos_nombres_titular", "Lipa Calabilla, Abraham");
        // $I->canSeeOptionIsSelected("#sexo_titular", "M");

        $I->click("Aceptar");

        // esperar a que carguen los datos de las apis
        $I->wait(1);

        $I->seeInCurrentUrl("/cliente/views/relacion-clientes-hotel-spa/");
        $I->seeInCurrentUrl("ok");

        $I->see("Checking creado correctamente");
        $I->see("Formulario de Relación de Clientes en el Hotel Spa");

        // hacer click en el boton de ver cuenta en la primera fila de la tabla
        $I->click("Ver Cuenta", "#tabla-clientes tbody tr:first-child");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->seeInCurrentUrl("/cliente/views/estado-cuenta-cliente/");
        $I->seeInCurrentUrl("?nro_registro_maestro");
        $I->see("Estado de cuenta del Cliente");

        $I->seeInField("Nombre del Cliente", "Lipa Calabilla, Abraham");
        $I->seeInField("Tipo", "SPA");
        $nroRegistroMaestro = $I->grabFromCurrentUrl('/nro_registro_maestro=([^&]+)/', "nro_registro_maestro");
        $I->seeInField("Nro. Registro Maestro", $nroRegistroMaestro);
        $I->seeInField("Nro. Habitación", "---");

        $I->click("Agregar comanda");

        // esperar a que carguen los datos de las apis
        $I->wait(.5);

        $I->seeInCurrentUrl("/cliente/views/agregar-comanda/");
        $I->seeInCurrentUrl("?nro_registro_maestro=" . $nroRegistroMaestro);

        $I->see("Agregar comanda");

        // agregar un producto
        $I->click("Bebidas", "#lista-grupos");
        $I->wait(.2);
        $I->click("Bebidas frias 1", "#lista-grupos");
        $I->wait(1);
        // hacer clic en el tr de la tabla que contiene el producto COCA COLA 1/2 LT
        $I->executeJS('document.querySelector("#tabla-productos tbody tr:nth-child(1) a").click();');

        // se espera a que cargue el modal para elegir la cantidad
        $I->wait(.2);

        $I->see("Cantidad");
        $I->fillField("#cantidad", "3");
        $I->click("Aceptar", "#modal-cantidad");

        // se espera a que cierra el modal
        $I->wait(1);

        $I->see("COCA COLA 1/2 LT", "#tabla-comandas");
        $I->see("3", "#tabla-comandas");

        // retroceder a la lista de grupos
        $I->click("#cabecera-grupos button");

        // agregar una receta
        $I->click("Platos de comida", "#lista-grupos");
        $I->wait(.2);
        $I->click("Lomo saltado", "#tabla-productos");

        // se espera a que cargue el modal para elegir la cantidad
        $I->wait(.2);
        $I->see("Cantidad");
        $I->fillField("#cantidad", "2");
        $I->click("Aceptar", "#modal-cantidad");

        // se espera a que cierra el modal
        $I->wait(.2);
        $I->see("Lomo saltado", "#tabla-comandas");
        $I->see("2", "#tabla-comandas");

        // agregar un servicio
        $I->click("Spa", "#lista-grupos");
        $I->wait(.2);
        $I->click("MASAJE HINDÚ", "#tabla-productos");

        // se espera a que cargue el modal para ingresar los detalles
        $I->wait(.2);
        $I->see("Asignación de servicio");
        $I->seeInField("#servicio", "MASAJE HINDÚ");
        $I->selectOption("Aplicado a", "Lipa Calabilla, Abraham");
        $I->selectOption("Profesional asignado", "Garcia, Maria");
        $I->fillField("Fecha", "09282023");
        $I->fillField("Hora", "1100P");
        $I->click("Aceptar", "#modal-terapista");

        // se espera a que cierra el modal
        $I->wait(.2);

        // agregar un paquete
        $I->click("PAQUETES Y PROM.", "#lista-grupos");
        $I->wait(.2);
        $I->click("Dia de la madre", "#tabla-productos");

        // se espera a que cargue el modal para elegir la cantidad
        $I->wait(.2);
        $I->see("Cantidad");
        $I->fillField("#cantidad", "1");
        $I->click("Aceptar", "#modal-cantidad");

        // se espera a que cierra el modal
        $I->wait(.2);

        $I->click("Aceptar");

        // se espera a que cargue el modal para elegir al acompañante
        $I->wait(.2);
        $I->see("Asignación de Comanda");
        $I->selectOption("Cliente", "Lipa Calabilla, Abraham");

        $I->click("Aceptar", "#modal-comanda");

        // se espera a que carguen los datos de las apis
        $I->wait(.5);

        $I->seeInCurrentUrl("/cliente/views/estado-cuenta-cliente/");
        $I->seeInCurrentUrl("?nro_registro_maestro=" . $nroRegistroMaestro);
        $I->seeInCurrentUrl("ok");


        $I->see("Comanda guardada correctamente");

        $I->see("COCA COLA 1/2 LT", "#tabla-comandas");
        $I->see("3", "#tabla-comandas");
        $I->see("Lomo saltado", "#tabla-comandas");
        $I->see("2", "#tabla-comandas");
        $I->see("MASAJE HINDÚ", "#tabla-comandas");
        $I->see("1", "#tabla-comandas");
        $I->see("Dia de la madre", "#tabla-comandas");
        $I->see("1", "#tabla-comandas");

        $total = $I->grabTextFrom("#total-cantidad");

        $I->click("Totalizar");

        // se espera a que cargue el modal para totalizar
        $I->wait(.2);

        $I->see("Totalizar", "#modal-comprobante");
        $I->seeInField("Monto Total:", $total);

        $I->selectOption("Tipo de Comprobante:", "FACTURA");
        $I->fillField("Nro Documento:", "10763686260");
        $I->waitForElementChange("#nombre", function () use ($I) {
            $I->seeInField("Nombre:", "LIPA CALABILLA ABRAHAM");
        }, 5);
        $I->fillField("Dirección:", "AV. LOS ALAMOS 123");
        $I->fillField("Lugar:", "LIMA");

        $I->click("Aceptar", "#modal-comprobante");
        $I->wait(1);

        // se abre una nueva pestaña con el pdf
        $I->switchToNextTab();
        $I->wait(1);
        $I->seeInCurrentUrl("http://localhost/apitest/reportes");
        $I->seeInCurrentUrl("?tipo=generar-factura&nro_comprobante");
    }
}