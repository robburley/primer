A new lead has been sent from the {{ $campaign->name }} campaign from {{ $campaign->tenant->name }}. <br>

@foreach($data as $key => $value)
    {{ str_replace('-', ' ', $key) }}: {{ $value }} <br>
@endforeach