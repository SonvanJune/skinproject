{{-- Unified Luxury Toast notification component (replaces legacy blocking modal alerts) --}}
@if (session($notification))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.LuxuryToast) {
                window.LuxuryToast.show(@json(session($notification)), @json($type ?? 'info'));
            }
        });
    </script>
@endif
