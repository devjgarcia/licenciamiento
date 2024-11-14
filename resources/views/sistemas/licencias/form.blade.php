<form action="#" method="POST" id="modal-dina-form">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="servicorreo">Servicio de Correos</label>
                <select name="servicorreo" id="servicorreo" required class="form-control">
                    <option value="">--Seleccione--</option>
                    <option {{ ($datos['servicorreo'] == 1) ? 'selected' : '' }} value="1">Activo</option>
                    <option {{ ($datos['servicorreo'] == 0) ? 'selected' : '' }} value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="correos_merca">Correos mercadeo</label>
                <textarea name="correos_merca" id="correos_merca" rows="2" class="form-control">{{$datos['correos_merca'] ?? ''}}</textarea>
            </div>
        </div>
    </div>
</form>