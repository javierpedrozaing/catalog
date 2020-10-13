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
    <form>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombres">Nombres y Apellidos</label>
                <input type="text" class="form-control" id="nombres">
            </div>
            <div class="form-group col-md-4">
                <label for="cedula">Cédula o NIT</label>
                <input type="text" class="form-control" id="cedula">
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="celular">Celular</label>
                <input type="password" class="form-control" id="celular">
            </div>
            <div class="form-group col-md-6">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address">
            </div>            
        </div>       

        <div class="dropdown-divider"></div>
        
        <?php get_template_part('partials/_products-filter'); ?>
        
        <div class="content-products"></div>

        <button type="submit" class="btn btn-primary float-right">Enviar pedido</button>
    </form>
</div>

<!-- Flexbox container for aligning the toasts -->
<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center" style="min-height: 200px;">

  <!-- Then put toasts within -->
  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <img src="..." class="rounded mr-2" alt="...">
      <strong class="mr-auto">Bootstrap</strong>
      <small>11 mins ago</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
  </div>
</div>




<?php do_action( 'storefront_sidebar' );
get_footer(); ?>