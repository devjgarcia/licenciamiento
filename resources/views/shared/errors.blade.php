@if ($errors->any())
  <div class="fw-bold text-danger fs-5">
    {{ __('Ups! Algo falló.') }}
  </div>

  <ul class="list-unstyled text-danger text-center">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
@endif
