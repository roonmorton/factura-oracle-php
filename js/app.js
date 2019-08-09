var app = angular.module("appIsertec",["ngRoute"])
.config(function($routeProvider){
    $routeProvider
    .when("/",{
        controller: "homeController",
        templateUrl: "views/home.html"
    })
    .when("/facturacion",{
        controller:"facturacionController",
        templateUrl: "views/facturar.html"
    })
    .when("/facturacion/listarFacturas",{
        controller: "verFacturasController",
        templateUrl: "views/facturas.html"
    })
    .when("/produccion",{
        controller: "produccionController",
        templateUrl: "views/produccion.html"
    })
    .when("/produccion/nuevoArticulo",{
        controller: "nuevoArticuloController",
        templateUrl: "views/addProducto.html"
    })
    .when("/facturacion/factura/:id",{
        controller: "detalleFacturaController",
        templateUrl: "views/detalleFactura.html"
    })
    .otherwise({
        redirectTo: "/"
    })
    
});