{{-- <h1>itender</h1> --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var url = ` {{ \Config::get('values')['frontend_url'] }} `;
    window.location.href = url
</script>
