@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div class="primer-notificiation c-alert c-alert--{{ $message['level'] }} {{ $message['important'] ? 'alert-important' : '' }} u-text-center u-mb-zero u-ph-large u-pv-small"
             style="position: fixed; bottom: 0; width: 100%; z-index: 99999; display: block;"
        >
            @if( $message['level'] == 'success')
                <i class="c-alert__icon fa fa-check-circle"></i>
            @elseif( $message['level'] == 'warning')
                <i class="c-alert__icon fa fa-exclamation-triangle"></i>
            @elseif( $message['level'] == 'danger')
                <i class="c-alert__icon fa fa-exclamation-circle"></i>
            @else
                <i class="c-alert__icon fa fa-info-circle"></i>
            @endif

            {!! $message['message'] !!}

            @if ($message['important'])
                <button class="c-close" data-dismiss="alert" type="button">×</button>
            @endif
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
