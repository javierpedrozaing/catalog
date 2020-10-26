<?php
/**
* Template Name: Catalogo Page
*
* @package WordPress
* @subpackage Cuadrilla Theme
*/

get_header();
?>
<div class="catalog container">
    <div class="spinner-border text-info loader-ajax" role="status">
	    <span class="sr-only">Loading...</span>
	</div>
    <h1>Catálogo</h1>
    <p>Fecha Pedido: <?=  date("j/n/Y"); ?></p>
    <form id="data-order">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombres">Nombres y Apellidos</label>
                <input required type="text" name="nombres" class="form-control" id="nombres">
            </div>
            <div class="form-group col-md-4">
                <label for="cedula">Cédula o NIT</label>
                <input required type="text" name="cedula" class="form-control" id="cedula">
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input required type="email" name="email" class="form-control" id="email">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="celular">Celular</label>
                <input required type="password" name="celular" class="form-control" id="celular">
            </div>
            <div class="form-group col-md-6">
                <label for="address">Address</label>
                <input required type="text" name="address" class="form-control" id="address">
            </div>            
        </div>       

        <div class="dropdown-divider"></div>
        
        <?php get_template_part('partials/_products-filter'); ?>
        
        <div class="content-products"></div>

        <button type="button" class="btn btn-primary float-right send-order">Enviar pedido</button>
    </form>
</div>


<?php get_footer(); ?>