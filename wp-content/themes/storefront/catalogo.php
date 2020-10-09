<?php
/**
* Template Name: Catalogo Page
*
* @package WordPress
* @subpackage Cuadrilla Theme
*/

get_header();
?>
<div class="container">
    <h1>Catálogo</h1>
    <p>Fecha Pedido: <?=  date("j/n/Y"); ?></p>
    <form>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombres">Nombres y Apellidos</label>
                <input type="text" class="form-control" id="nombres" placeholder="Nombres y Apellidos">
            </div>
            <div class="form-group col-md-4">
                <label for="cedula">Cédula o NIT</label>
                <input type="text" class="form-control" id="cedula" placeholder="Cédula o NIT">
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="celular">Celular</label>
                <input type="password" class="form-control" id="celular" placeholder="Celular">
            </div>
            <div class="form-group col-md-6">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Dirección de envio">
            </div>            
        </div>       

        <div class="dropdown-divider"></div>     
        
        <?php  get_template_part( 'partials/_products-catalog' ); ?>

        <button type="submit" class="btn btn-primary float-right">Enviar pedido</button>
    </form>
</div>




<?php do_action( 'storefront_sidebar' );
get_footer(); ?>