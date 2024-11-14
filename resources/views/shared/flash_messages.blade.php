@if($message = session('success'))
  <x-alert type="success">
    {{ $message }}
  </x-alert>
@endif
