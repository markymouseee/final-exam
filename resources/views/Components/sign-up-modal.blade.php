<div class="modal fade" id="sign-up-modal" tabindex="-1" aria-labelledby="sign-up-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center flex-column align-items-center text-center">
                <button class="btn btn-close" data-bs-toggle="modal" dara-bs-target="#sign-up-modal" type="button"
                    aria-label="Close"></button>
                <h5 class="modal-title mb-1 d-flex align-items-center gap-1" id="sign-up-modal-label">
                    <img src="{{ asset('images/logo.png') }}" alt="logo" class="d-inline-block align-text-top me-2"
                        height="40" width="40">
                    Join the <strong>BM
                        Music</strong> Vibe
                </h5>
                <p class="mb-0 text-muted" style="max-width: auto;font-size: 0.9rem;">
                    Create your free account and unlock a world of beats, rhythms, and pure musical energy. Let’s get
                    started!
                </p>
            </div>

            <div class="modal-body">
                <form id="signup-form">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="signup-fullname" name="name" placeholder="">
                        <label for="signup-username" class="form-label">Fullname</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="signup-username" name="username" placeholder="">
                        <label for="signup-email" class="form-label">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="signup-password" name="password" placeholder="">
                        <label for="signup-password" class="form-label">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="signup-password-confirmation"
                            name="password_confirmation" placeholder="">
                        <label for="signup-retype-password" class="form-label">Re-type password</label>
                    </div>
                    <p class="text-muted text-center" style="font-size: 15px;"> By continuing, you agree to our <a
                            href="">Terms</a> and <a href="">Privacy
                            Policy</a>.</p>
                    <button type="submit" id="btn-sign-up" class="btn btn-dark w-100" style="border-radius: 50px;">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>

                        <svg id="icon" width="20" height="20" viewBox="0 0 32 32" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-360.000000, -1087.000000)" fill="currentColor">
                                    <path
                                        d="M376,1117 C368.268,1117 362,1110.73 362,1103 C362,1095.27 368.268,1089 376,1089 C383.732,1089 390,1095.27 390,1103 C390,1110.73 383.732,1117 376,1117 L376,1117 Z M376,1087 C367.163,1087 360,1094.16 360,1103 C360,1111.84 367.163,1119 376,1119 C384.837,1119 392,1111.84 392,1103 C392,1094.16 384.837,1087 376,1087 L376,1087 Z M376.879,1096.46 C376.639,1096.22 376.311,1096.15 376,1096.21 C375.689,1096.15 375.361,1096.22 375.121,1096.46 L369.465,1102.12 C369.074,1102.51 369.074,1103.14 369.465,1103.54 C369.854,1103.93 370.488,1103.93 370.879,1103.54 L375,1099.41 L375,1110 C375,1110.55 375.447,1111 376,1111 C376.553,1111 377,1110.55 377,1110 L377,1099.41 L381.121,1103.54 C381.512,1103.93 382.145,1103.93 382.535,1103.54 C382.926,1103.14 382.926,1102.51 382.535,1102.12 L376.879,1096.46 L376.879,1096.46 Z"
                                        id="arrow-up-circle" sketch:type="MSShapeGroup">

                                    </path>
                                </g>
                            </g>
                        </svg>
                        Sign Up
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        handleSignUp();
    });

    function handleSignUp() {
        $('#signup-form').on('submit', async function(e) {
            e.preventDefault();

            Spinner($("#btn-sign-up"), true);

            const formData = $(this).serializeArray().reduce((obj, item) => {
                obj[item.name] = item.value;
                return obj;
            }, {});

            try {
                const response = await fetch("/api/register", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    body: JSON.stringify(formData)
                })

                const data = await response.json();
                if (response.ok) {
                    Spinner($("#btn-sign-up"), false);
                    Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                        })
                        .then((willDirect) => {
                            if (willDirect.isConfirmed) {
                                window.location.href = data.redirect
                            }
                        })

                } else {
                    if (data.errors) {
                        Spinner($("#btn-sign-up"), false);
                        Object.keys(data.errors).forEach(key => {
                            const inputFields = $(`input[name="${key}"]`);

                            if (inputFields.length) {
                                inputFields.addClass('is-invalid');
                                inputFields.next('.invalid-feedback').remove();
                                inputFields.after(
                                    `<div class="invalid-feedback">${data.errors[key][0]}</div>`
                                );
                            }
                        })
                    } else {
                        Spinner($("#btn-sign-up"), false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'An error occurred. Please try again later.',
                        })
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                Spinner($("#btn-sign-up"), false);
            }
        })
    }

    function Spinner(button, isLoading) {
        button.prop('disabled', isLoading);
        button.find('.spinner-border').toggleClass('d-none', !isLoading);
        if (isLoading) {
            button.text('Signing up...');
        } else {
            button.text('Sign Up');

        }
    }
</script>
