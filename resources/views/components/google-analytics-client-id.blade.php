<script>
    function collectClientId() {
        if (typeof ga !== 'undefined') {
            ga(function(tracker) {
                var clientId = tracker.get('clientId');
                postClientId(clientId);
            });
        } else if (typeof gtag !== 'undefined') {
            gtag('get', "{{ config('google-analytics-4-measurement-protocol.measurement_id') }}", 'client_id',
                function(clientId) {
                    postClientId(clientId);
                });
        } else {
            var match = document.cookie.match('(?:^|;)\\s*_ga=([^;]*)');
            var raw = (match) ? decodeURIComponent(match[1]) : null;
            if (raw) {
                match = raw.match(/(\d+\.\d+)$/);
            }
            var clientId = (match) ? match[1] : null;
            if (clientId) {
                postClientId(clientId);

            }

        }
    }

    function postClientId(clientId) {
        var data = new FormData();
        data.append('client_id', clientId);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'store-google-analytics-client-id', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.send(data);
    }

    @if (!session(config('google-analytics-4-measurement-protocol.client_id_session_key'), false))
        collectClientId();
    @endif
</script>
