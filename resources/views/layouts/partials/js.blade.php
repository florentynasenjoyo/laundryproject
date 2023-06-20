@section('js')
<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    @if(Session::has('success'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.success("{{ session('success') }}");
        @endif
      
        @if(Session::has('errors'))
        toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            @foreach ($errors->all() as $errors)
                toastr.error("{{ $errors }}");
            @endforeach
        @endif
      
        @if(Session::has('warning'))
            toastr.options =
                {
                    "closeButton" : true,
                    "progressBar" : true
                }
            toastr.warning("{{ session('warning') }}");
        @endif
</script>
@endsection
