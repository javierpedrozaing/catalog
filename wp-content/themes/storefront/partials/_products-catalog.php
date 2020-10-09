<div class="filter row">
    <div class="form-group col-md-2">
        <p>Filtrar por: </p>
    </div>
    <div class="form-group col-md-4">
        <select name="type-filter" class="custom-select">
            <option value="category">Tipo de filtro:</option>
            <option value="category">Categoría</option>
            <option value="collection">Colección</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <select name="select-filter" class="custom-select">
            <option value="">Seleccionar </option>
        </select>
    </div>
</div>

<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Imagen</th>
      <th scope="col">Producto</th>
      <th scope="col">Categoría</th>
      <th scope="col">Ref.</th>
      <th scope="col">Talla</th>
      <th scope="col">Precio al público</th>
      <th scope="col">Precio al por mayor</th>
      <th scope="col">Cantidad</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td><img src="" alt=""></td>
      <td>Producto 1</td>
      <td>Categoría</td>
      <td>Ref</td>
      <td>
        <select name="talla-producto" id="">
            <option value="">M</option>
            <option value="">S</option>
        </select>
      </td>
      <td>$22.000</td>
      <td>$25.000</td>
      <td><input  class="quantity" type="number"></td>
      <td><span class="totla">$52.000</span> </td>
    </tr>

    <tr>
      <th scope="row">2</th>
      <td><img src="" alt=""></td>
      <td>Producto 1</td>
      <td>Categoría</td>
      <td>Ref</td>
      <td>
        <select name="talla-producto" id="">
            <option value="">M</option>
            <option value="">S</option>
        </select>
      </td>
      <td>$22.000</td>
      <td>$25.000</td>
      <td><input class="quantity"  type="number"></td>
      <td><span class="totla">$52.000</span> </td>
    </tr>

  </tbody>
</table>
