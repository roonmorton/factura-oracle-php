CREATE TABLE ARTICULO
  (
    CODIGO VARCHAR2 (10) NOT NULL ,
    PRECIO FLOAT ,
    DESCRIPCION VARCHAR2 (60) ,
    EXISTENCIA  NUMBER
  );
  
ALTER TABLE ARTICULO ADD CONSTRAINT ARTICULO_PK PRIMARY KEY ( CODIGO );


CREATE TABLE CLIENTE
  (
    NOMBRE   VARCHAR2 (40) ,
    DIRECCION VARCHAR2 (40) ,
    NIT      VARCHAR2 (15) NOT NULL
  ) ;
ALTER TABLE CLIENTE ADD CONSTRAINT CLIENTE_PK PRIMARY KEY ( NIT ) ;


CREATE TABLE DETALLE_FACTURA
  (
    FACTURA_CODIGO  VARCHAR2 (10) NOT NULL ,
    ARTICULO_CODIGO VARCHAR2 (10) NOT NULL ,
    CANTIDAD        NUMBER
  ) ;


CREATE TABLE FACTURA
  (
    CODIGO         VARCHAR2 (10) NOT NULL,
    FECHA          DATE,
    CLIENTE_NIT VARCHAR2 (15) NOT NULL,
    MONTO float,
    pago number

  ) ;
  
ALTER TABLE FACTURA ADD CONSTRAINT FACTURA_PK PRIMARY KEY ( CODIGO ) ;


ALTER TABLE FACTURA ADD CONSTRAINT FACTURA_CLIENTE_FK FOREIGN KEY ( CLIENTE_NIT ) REFERENCES CLIENTE ( NIT ) ;

ALTER TABLE DETALLE_FACTURA ADD CONSTRAINT FK_ASS_2 FOREIGN KEY ( FACTURA_CODIGO ) REFERENCES FACTURA ( CODIGO ) ;

ALTER TABLE DETALLE_FACTURA ADD CONSTRAINT FK_ASS_3 FOREIGN KEY ( ARTICULO_CODIGO ) REFERENCES ARTICULO ( CODIGO ) ;


/***** Borrar tablas Este orden asi se elimina las dependecnias de las FK ****************/

drop table detalle_factura;
drop table factura;
drop table cliente;
drop table articulo;

desc cliente;
desc factura;
desc detalle_factura;
desc articulo;


/****************** Procedimiento Almacenado para Agragar un nuevo Articulo ****************/

create or replace procedure nuevoArticulo
(
  codigo articulo.codigo%type,
  precio articulo.precio%type,
  descripcion articulo.descripcion%type,
  existencia articulo.existencia%type
)
as
begin
insert into articulo values(codigo,precio,descripcion,existencia);
commit;
end nuevoArticulo;

desc factura


/************************** Procedimiento almacenado para Agregar una factura sin cliente *****************/

create or replace procedure facturarSinCliente
(
  nombre in cliente.nombre%type,
  direccion in cliente.direccion%type,
  nit in cliente.nit%type,
  facCodigo in factura.codigo%type,
  facfecha in factura.fecha%type,
  monto in factura.monto%type,    
  pago in factura.pago%type,
)
as
begin
insert into cliente values(nombre,direccion,nit);
insert into factura values(facCodigo,facfecha,nit,monto,pago);
commit;
end facturarSinCLiente;


/*********** Procedimiento almacenado para agregar un factura con Cliente ******/

create or replace procedure facturarConCliente
(
  nit in cliente.nit%type,
  facCodigo in factura.codigo%type,
  facfecha in factura.fecha%type,
  pago in factura.pago%type,
  monto in factura.monto%type
)
as
begin
insert into factura values(facCodigo,facfecha,nit,monto,pago);
commit;
end facturarConCLiente;


/****** Procedimiento almacenado para actualizar la existencia de un Articulo ******/

create or replace procedure actualizarExistenciaArticulo
(
  cod articulo.codigo%type,
  ex articulo.existencia%type
)
as
begin
update articulo set existencia = ex where codigo = cod;
commit;
end actualizarExistenciaArticulo;



/************** Procedimiento almacenado para agregar el detalle a la Factura *****************/

create or replace procedure detalleFactura
(
  facCodigo in factura.codigo%type,
  artCodigo in articulo.codigo%type,
  cantidad in detalle_factura.cantidad%type
)
as
begin
insert into detalle_factura values(facCodigo,artCodigo,cantidad);
update articulo set existencia = articulo.existencia-cantidad where codigo = artCodigo;
commit;
end detalleFactura;



select * from cliente;
select * from factura;
select * from articulo;

execute FacturarSinCliente('Ron Quevedo','Escuitla Guatemala','12345678','100','20/12/2015');
execute FacturarSinCliente('Ron Quevedo','Escuitla Guatemala','12345677','101','20/12/2015');
execute facturarConCliente('12345677','103','20/12/2015');

execute nuevoArticulo('110COC',6.00,'Coca cola lata 200ml',10);
execute nuevoArticulo('112COC',6.00,'Coca cola Desechable 600ml',70);

select * from articulo where descripcion like '%lata%' or descripcion like '%coca%';

execute actualizarExistenciaArticulo('110COC',10);



select f.codigo codigo, f.monto,f.fecha fecha, c.nit NIT, c.nombre nombre,c.direccion, a.descripcion, df.cantidad,a.precio from factura f
join cliente c
on f.cliente_nit = c.nit
join detalle_factura df
on df.factura_codigo = f.codigo
join articulo a
on df.articulo_codigo = a.codigo where f.codigo = '1000';


select f.codigo, f.monto, f.fecha, c.nombre from factura f
join cliente c
on f.cliente_nit = c.nit order by f.codigo desc



/***************************** consulta que muestra  CODIGO FACTURA; FECHA; NOMBRE CLIENTE; NIT CLIENTE; CANTIDAD; Y EL TOTAL DE LA FACTURA ****************/
select f.codigo, f.fecha, c.nombre, c.nit, 
          (select sum(df.cantidad*ar.precio)
           from articulo ar
           join detalle_factura df
           on df.articulo_codigo = ar.codigo
           where df.factura_codigo = f.codigo) as total,
           (select sum(df.cantidad)
            from detalle_factura df
            where df.factura_codigo= f.codigo) as cantidad
from factura f
join cliente c
on f.cliente_nit = c.nit;



