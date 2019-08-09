app
    .controller("homeController", function ($scope) {
        $scope.pagina = "Home";

    })

.controller("facturacionController", function ($scope,$http,$location) {
    $scope.query = "";
    $scope.cantidad = 1;
    iniciarFactura();
    
    function iniciarFactura(){
        $http.get("core/iniciarFactura.php")
        .success(function(data){
            console.log(data);
            $scope.factura = data;
        })
        .error(function(err){
            console.log(err);
        })
    };
    
    
    function totales(){
        $scope.factura.bruto = 0;
        $scope.factura.monto = 0;
        for(i=0;i<$scope.factura.articulos.length;i++){
            $scope.factura.bruto = $scope.factura.bruto + $scope.factura.articulos[i].cantidad;
            $scope.factura.monto = $scope.factura.monto + $scope.factura.articulos[i].importe;
        }
    }
    
    $scope.addItem = function(item,cantidad){
        var p = true;
        var nItem = {
            codigo: item.CODIGO,
            cantidad: cantidad,
            descripcion: item.DESCRIPCION,
            precio: item.PRECIO,
            importe: item.PRECIO*cantidad
        };    
        for(i=0;i < ($scope.factura.articulos.length);i++){
            if(nItem.codigo == $scope.factura.articulos[i].codigo){
                $scope.factura.articulos[i].cantidad = $scope.factura.articulos[i].cantidad + nItem.cantidad;
                $scope.factura.articulos[i].importe = $scope.factura.articulos[i].importe + (nItem.cantidad*nItem.precio);
                p = false;
            }
        }
        if(p){
            $scope.factura.articulos.push(nItem);
        }
        totales();    
        $scope.query = "";
        $scope.items= [];
    };
    
    
    
    
    $scope.search = function () {
        if ($scope.query != "") {
            $http.post("core/busquedaArticulos.php",{query: $scope.query})
            .success(function(data){
                console.log(data);
                $scope.items = data;
                //console.log(data);
            })
            .error(function(err){
                
            })
        } else {
            $scope.items = [];
        }
    };
    
    $scope.comprobarNit = function(){
        /*if($scope.factura.nit != ""){
            $http.post("core/comprobarCliente.php",{nit: $scope.factura.nit})
            .success(function(data){
                console.log(data);
                $scope.factura = data;
            })
            .error(function(error){
                console.log(error);
                
            });
            
        }*/ 
    };
    
    $scope.addFactura = function (){
      if($scope.factura.articulos.length > 0){
          $http.post("core/generarFactura.php",$scope.factura)
              .success(function(data){
              console.log(data);
              $location.path("/facturacion/factura/"+$scope.factura.no);
              iniciarFactura();
          })
      .error(function(err){
          console.log(err);
      })
          
      }else
          $scope.error = true;
    };
    
})

.controller("verFacturasController", function ($scope, $http) {
    $http.get("core/verFacturas.php")
    .success(function(data){
        $scope.facturas = data;
       console.log(data); 
    })
    .error(function(err){
        console.log(data);
    });
    
})

.controller("produccionController",function($scope,$http){
    $http.get("core/verArticulos.php")
    .success(function(data){
        console.log(data);
        $scope.articulos = data;
    })
    
    
})

.controller("nuevoArticuloController",function($scope,$http,$location){
    $scope.articulo = {};
    $scope.addArticulo = function(){
        /* Funcion para hacer llamado http  y enviar datos a agregar*/
        $http.post("core/insertArticulo.php",$scope.articulo)
        .success(function(data){
            console.log(data);
            $scope.articulo = {};
            $location.path('/produccion');
        })
        .error(function(err){
           console.log(err); 
        });
    }
})

.controller("detalleFacturaController",function($scope,$http,$routeParams,$location){
    //$scope.factura = [];
    $http.post("core/iniciarFactura.php",{no: $routeParams.id})
    .success(function(data){
       
        $scope.factura = data;
        
    console.log($scope.factura);
    })
    .error(function(err){
        console.log(data);
    });
})