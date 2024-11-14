@if($status = session('status'))
  <div class="mb-3 text-success text-center">
    <b>{{ $status }}</b>
  </div>
@endif
