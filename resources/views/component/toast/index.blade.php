@php
    $toastQueue = [];

    // 1. Success keys
    foreach (['success', 'add_to_cart_success', 'change_success', 'change_password_success', 'reset_success', 'delete_success', 'register_success'] as $key) {
        if (session()->has($key)) {
            $msg = session($key);
            if (!empty($msg) && is_string($msg)) {
                $toastQueue[] = ['type' => 'success', 'message' => $msg];
            }
        }
    }

    // 2. Error keys
    foreach (['error', 'failed', 'register_failed', 'add_to_cart_failed', 'delete_failed'] as $key) {
        if (session()->has($key)) {
            $msg = session($key);
            if (!empty($msg) && is_string($msg)) {
                $toastQueue[] = ['type' => 'error', 'message' => $msg];
            }
        }
    }

    // 3. Warning keys
    foreach (['warning', 'token_expired', 'permission_denied', 'need_security'] as $key) {
        if (session()->has($key)) {
            $msg = session($key);
            if (!empty($msg) && is_string($msg)) {
                $toastQueue[] = ['type' => 'warning', 'message' => $msg];
            }
        }
    }

    // 4. Info keys
    foreach (['info', 'status', 'message'] as $key) {
        if (session()->has($key)) {
            $msg = session($key);
            if (!empty($msg) && is_string($msg)) {
                $toastQueue[] = ['type' => 'info', 'message' => $msg];
            }
        }
    }

    // 5. Form validation errors ($errors)
    if (isset($errors) && is_object($errors) && method_exists($errors, 'any') && $errors->any()) {
        foreach ($errors->all() as $err) {
            if (!empty($err) && is_string($err)) {
                $toastQueue[] = ['type' => 'error', 'message' => $err];
            }
        }
    }
@endphp

@if (!empty($toastQueue))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.LuxuryToast) {
                const toasts = @json($toastQueue);
                toasts.forEach((item, index) => {
                    setTimeout(() => {
                        window.LuxuryToast.show(item.message, item.type);
                    }, index * 250);
                });
            }
        });
    </script>
@endif
