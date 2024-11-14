@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function (event) {
      document.getElementById('save-buttons')
        .addEventListener('click', function (event) {
          let target = event.target

          if (target.matches('button.dropdown-item')) {
            document.getElementById('save_action').value = target.dataset.action
            target.form.submit()
          }
        })
    })
  </script>
@endpush

<input type="hidden" name="save_action" id="save_action" value="">

<div class="btn-group" id="save-buttons">
  <button type="submit" class="btn btn-success">
    Guardar
  </button>
  <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="visually-hidden"></span>
  </button>
  <ul class="dropdown-menu">
    <li>
      <button type="button" class="dropdown-item" data-action="edit">
        Guardar y seguir editando
      </button></li>
    <li>
      <button type="button" class="dropdown-item" data-action="create">
        Guardar y crear nuevo
      </button>
    </li>
  </ul>
</div>

