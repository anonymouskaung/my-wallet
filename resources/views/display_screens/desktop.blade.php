<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <title>My Wallet</title>
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /*input[type="number"] {
            -moz-appearance: textfield;
        }*/

        .focus-border-success:focus {
            border: 3px solid hsla(120, 72%, 29%, 0.933);
        }

        nav a:hover {
            background-color: #ddd;
        }

        .nav-link {
            color: #343a40;
        }

        .nav-link.active {
            color: #ffffff;
        }

        .mobile-tab-content {
            display: none;
        }

        textarea::placeholder {
            text-transform: none;
        }

        .nav-link-action:hover {
            background-color: #ddd;
            color: black;
        }

        #balanceAmount {
            font-size: 1.5rem;
            font-weight:normal;
        }
    </style>
</head>

<body>
    <div class="container p-1 p-md-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9">
                <div class="bg-info rounded-3">
                    <div class="card bg-warning rounded-bottom-5 border-0 sticky-top">
                        <div class="card-header"
                            style="font-size: 1.1rem; color: #343a40; font-weight: 600; border-left: 6px solid #0d6efd;">
                            My Wallet
                        </div>
                        <div class="card-body d-flex align-items-center mb-1 mb-md-5">
                            <img src="{{ asset('images/profiles/' . (auth()->user()->photo ?? 'profile.png')) }}"
                                style="height: 80px; width: 80px;" class="rounded-circle me-2" alt="Profile Photo">
                            <div>
                                <div>
                                    <strong id="display-profile-name" style="font-family: Times, serif;">{{
                                        auth()->user()->name ?? 'Nickname' }}
                                    </strong>
                                </div>
                                <div>
                                    <span style="font-size: 1.2rem; font-weight: bold;">
                                        <i class="fa-solid fa-wallet"></i>
                                        Balance:
                                    </span>
                                    <span id="balanceAmount">{{ $balance ?? 00 }}</span>
                                    <span> Ks</span>
                                    <span onclick="toggleEye()">
                                        <i id="eye" class="fa-regular fa-eye" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0">
                            <ul id="walletTab" class="nav nav-pills nav-fill rounded bg-white" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button id="wallet-tab" type="button" class="nav-link nav-link-action active"
                                        data-bs-toggle="tab" data-bs-target="#wallet" role="tab">
                                        Wallet
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button id="inbox-tab" type="button" class="nav-link nav-link-action"
                                        data-bs-toggle="tab" data-bs-target="#inbox" role="tab">
                                        Inbox
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button id="profile-tab" type="button" class="nav-link nav-link-action"
                                        data-bs-toggle="tab" data-bs-target="#profile" role="tab">
                                        Profile
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- tab panel -->
                    <div class="tab-content">
                        <div id="wallet" class="tab-pane fade show active" role="tabpanel">
                            <div class="row p-1 p-md-3">
                                <div class="col-12 col-md-6 mb-3">
                                    <!--money added form-->
                                    <form id="topup-form">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="h5 text-center mb-0" style="font-family: Times, serif;">Top
                                                    Up
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <label for="topup-amount" class="form-label h6">Amount</label>
                                                <input id="topup-amount" min="100" name="addedAmount"
                                                    class="form-control mb-3" required type="number"
                                                    placeholder="Enter at least 100 Ks">
                                                <label for="topup-description" class="form-label h6">Description</label>
                                                <textarea id="topup-description" class="form-control mb-3"
                                                    name="topupDescription" placeholder="e.g. Weekly Pass"
                                                    style="text-transform: capitalize;"
                                                    onblur="titleCase(this)"></textarea>
                                            </div>
                                            <div class="card-footer">
                                                <button id="topup-btn" type="submit" class="btn btn-success w-100">Top
                                                    Up</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <form id="pay-form">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="h5 mb-0 text-center" style="font-family: Times, serif;">Pay
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <label for="pay-amount" class="form-label h6">
                                                    Amount
                                                </label>
                                                <input id="pay-amount" type="number" name="usedAmount"
                                                    class="form-control mb-3" placeholder="Enter amount(Ks)" required>
                                                <label for="pay-description" class="form-label h6">
                                                    Description
                                                </label>
                                                <textarea id="pay-description" class="form-control mb-3"
                                                    name="payDescription" placeholder="e.g. Phone Bill"
                                                    style="text-transform: capitalize;"
                                                    onblur="titleCase(this)"></textarea>
                                            </div>
                                            <div class="card-footer">
                                                <button id="pay-btn" type="submit" class="btn btn-primary w-100">
                                                    Pay
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="inbox" class="py-2 px-1 py-md-3 px-md-2 tab-pane fade" role="tabpanel">
                            @if( isset($inboxes) )
                            <div id="inbox-list" class="list-group">
                                @foreach($inboxes as $inbox)
                                <div class="list-group-item">
                                    <div>
                                        <small class="text-muted" style="font-size: smaller;">{{
                                            $inbox->created_at->format('j M Y') }}</small>
                                        <!-- {{ $inbox->created_at }} -->
                                        <div>
                                            <h6>Money {{ $inbox->money_flow }}</h6>
                                            <p class="text-dark">You {{ $inbox->money_flow }} {{ $inbox->amount }} Ks.
                                            </p>
                                            @if($inbox->content)
                                            <p class="text-muted">Description: {{ $inbox->content }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div id="profile" class="p-1 p-md-3 tab-pane fade" role="tabpanel">
                            <ul class="list-group rounded-bottom-0 mb-2">
                                <li class="list-group-item text-muted border-bottom-0" disabled>
                                    User Info
                                </li>
                                @if(isset(auth()->user()->email))
                                <li id="email" class="list-group-item">
                                    {{ auth()->user()->email }}
                                </li>
                                @endif
                                @if(isset(auth()->user()->phone))
                                <li id="phone" class="list-group-item">
                                    {{ auth()->user()->phone }}
                                </li>
                                @endif
                            </ul>
                            <div class="list-group rounded-0 mb-2">
                                <span class="list-group-item text-muted border-bottom-0" disabled>
                                    Account
                                </span>
                                <button id="recover-phone-button" type="button" onclick="rotate(this, 90)"
                                    data-bs-toggle="collapse" data-bs-target="#recover-phone-content"
                                    class="list-group-item list-group-item-action text-dark">
                                    <i class="fa-solid fa-square-phone me-2"></i>
                                    Edit Phone Number
                                    <span class="float-end text-muted">
                                        <i class="fa-solid fa-chevron-right rotate-item"></i>
                                    </span>
                                </button>
                                <div id="recover-phone-content" class="collapse bg-light">
                                    <form class="p-3" id="recover-phone-form">
                                        <div class="input-group">
                                            <input type="number" name="recoverPhone" class="form-control"
                                                placeholder="Enter Recover Phone Number" required>
                                            <button id="recover-phone-save-button" type="submit"
                                                class="btn btn-primary">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <button id="recover-email-button" type="button" onclick="rotate(this, 90)"
                                    class="list-group-item list-group-item-action text-dark" data-bs-toggle="collapse"
                                    data-bs-target="#recover-email-content">
                                    <i class="fa-solid fa-envelope me-2"></i>
                                    Edit Email Address
                                    <span class="float-end text-muted">
                                        <i class="fa-solid fa-chevron-right rotate-item"></i>
                                    </span>
                                </button>
                                <div id="recover-email-content" class="collapse bg-light">
                                    <form id="recover-email-form" class="p-3">
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="recoverEmail"
                                                placeholder="Enter recover email">
                                            <button id="recover-email-save-button" type="submit"
                                                class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <button id="saved-name-button" type="button" onclick="rotate(this, 90)"
                                    data-bs-toggle="collapse" data-bs-target="#edit-name-content"
                                    class="list-group-item list-group-item-action text-dark">
                                    <i class="fa-regular fa-pen-to-square me-2"></i>
                                    Edit Profile Name
                                    <span class="float-end text-muted">
                                        <i class="fa-solid fa-chevron-right rotate-item"></i>
                                    </span>
                                </button>
                                <div id="edit-name-content" class="collapse bg-light">
                                    <form class="p-3" id="edit-name-form">
                                        <div class="input-group">
                                            <input name="name" type="text" class="form-control"
                                                placeholder="Enter profile name" autocomplete="username">
                                            <input id="profile-name-save-btn" type="submit" class="btn btn-primary"
                                                value="Save">
                                        </div>
                                    </form>
                                </div>
                                <button id="saved-photo-button" onclick="rotate(this, 90)" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#edit-photo-content"
                                    class="list-group-item list-group-item-action text-dark">
                                    <i class="fa-regular fa-circle-user me-2"></i>
                                    Edit Profile Photo
                                    <span class="float-end text-muted">
                                        <i class="fa-solid fa-chevron-right rotate-item"></i>
                                    </span>
                                </button>
                                <div id="edit-photo-content" class="collapse bg-light">
                                    <form class="p-3" id="edit-photo-form">
                                        <div class="input-group">
                                            <input type="file" class="form-control" name="editPhoto">
                                            <input type="submit" id="profile-photo-save-btn" class="btn btn-primary"
                                                value="Upload">
                                        </div>
                                    </form>
                                </div>
                                <button id="change-password" type="button"
                                    class="list-group-item list-group-item-action text-dark" onclick="confirmPasswordModal()">
                                    <i class="fa-solid fa-lock me-2"></i>
                                    Change Password
                                </button>
                                <a href="{{ url('/logout')}}"
                                    onclick="event.preventDefault(); document.querySelector('#logout-form').submit();"
                                    class="list-group-item list-group-item-action text-danger">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                                    logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">@csrf
                                </form>
                            </div>
                            <div class="list-group rounded-top-0">
                                <span class="list-group-item text-muted border-bottom-0" disabled>
                                    Setting
                                </span>
                                <button type="button" onclick="switchLanguage()"
                                    class="list-group-item list-group-item-action">
                                    <i class="fa-solid fa-earth-asia me-2"></i>
                                    Switch Language
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
    <!--//-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var balanceAmount;
        const csrf = document.querySelector("meta[name='csrf-token']").content;
        function rotate(x, deg) {
            const edit = x.querySelector('.rotate-item');
            if (edit) {
                if (edit.style.transform == `rotate(${deg}deg)`) {
                    edit.style.transform = `rotate(0deg)`;
                } else {
                    edit.style.transform = `rotate(${deg}deg)`;
                }
                edit.style.transition = 'transform 0.3s ease';
            }
        }
        function confirmPasswordModal() {
            Swal.fire({
                title: 'Confirm your account',
                input: 'password',
                inputLabel: 'Password',
                inputPlaceholder: 'Enter current password.',
                inputAttributes: {
                    minlength: 8,
                    autocapitalize: "off",
                    autocorrect: "off",
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then(async (result) => {
                if (result.isConfirmed && result.value) {
                    const password = result.value;
                    try {
                        const response = await fetch('/password/confirmed', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify({ password })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                title: "Change your password",
                                input: "password",
                                inputLabel: "Password",
                                inputPlaceholder: "Enter password",
                                inputAttributes: {
                                    minlength: "8",
                                    autocapitalize: "off",
                                    autocorrect: "off"
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Change',
                            }).then(async (results) => {
                                if (results.isConfirmed && results.value) {
                                    const newPassword = results.value;
                                    const responses = await fetch('password/changes', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrf,
                                        },
                                        body: JSON.stringify({ 'password': newPassword })
                                    });
                                    const resultData = await responses.json();
                                    if (resultData.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            showCancelButton: false,
                                            timer: 1500,
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Change failed',
                                            text: resultData.message || 'Please try again.'
                                        });
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Incorrect password',
                                text: data.message || 'Please try again.',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong.',
                        });
                        console.error(error);
                    }
                }
            });
        }
        function toggleEye() {
            const balance = document.querySelector('#balanceAmount');
            const eyeIcon = document.querySelector('#eye');
            const isHidden = eyeIcon.classList.contains('fa-eye');
            if (isHidden) {
                balanceAmount = balance.textContent;
                const count = String(balanceAmount).replace(/\D/g, '').length;
                balance.textContent = '*'.repeat(count);
                balance.style.letterSpacing = '2px';
            } else {
                balance.textContent = balanceAmount;
                balance.style.letterSpacing = '0px';
            }
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');

            localStorage.setItem('eye', isHidden ? 'close' : 'open');
        }
        window.addEventListener('DOMContentLoaded', function () {
            const eye = localStorage.getItem('eye');
            if (eye == 'close') {
                toggleEye();
            }
        });
        function toggleBalance() {
            const balance = document.querySelector('#balanceAmount');
            if (balance && localStorage.getItem('eye') == 'open') {
                balance.textContent = balanceAmount;
            }
        }
        function titleCase(x) {
            x.value = x.value.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        function successAlert(content, amount, flow) {
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.zIndex = '9999';
            div.style.position = 'fixed';
            div.style.top = '20px';
            div.style.left = '0';
            div.style.width = '100%';
            div.style.justifyContent = 'center';
            div.style.pointerEvents = 'none';
            if (amount) {
                amount = `You ${flow} <strong>${amount} Ks</strong>.`;
            } else {
                amount = '';
            }
            div.innerHTML = `
            <div role='alert'
             style="
                background-color: #e6f4ea;
                border: 1px solid #198754;
                color: #198754;
                padding: 1rem 1.5rem;
                border-radius: .5rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                max-width: 400px;
                width: 90%;
                text-align: left;
                pointer-events: all;
                opacity: 1;
                transition: opacity 1s ease;
             "
              id="success-alert-content"
            >
                <div style="display: flex; align-items: center; gap: .5rem; margin-bottom: .5rem;">
                    <i class="fa-regular fa-circle-check" style="font-size: 1.5rem;"></i>
                    <h3 style="font-size: 1.1rem; margin: 0;">${content} Successfully!</h3>
                </div>
                <div>
                    <span style="font-size: .9rem; color: #333;">
                        ${amount}
                    </span>
                </div>
            </div>
            `;
            document.body.appendChild(div);
            setTimeout(() => {
                const alertBox = div.querySelector('#success-alert-content');
                alertBox.style.opacity = '0';
            }, 2000);
            setTimeout(() => {
                div.remove();
            }, 3000);
        }

        function failAlert(content) {
            const div = document.createElement('div');
            div.style.cssText = `
                display: flex;
                position: fixed;
                z-index: 9999;
                top: 20px;
                left: 0;
                width: 100%;
                justify-content: center;
                pointer-events: none;
            `;
            div.innerHTML = `
                <div role="alert" style="
                    background-color: #f8d7da;
                    border: 1px solid #dc3545;
                    color: #dc3545;
                    padding: 1rem 1.5rem;
                    border-radius: .5rem;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    max-width: 400px;
                    width: 90%;
                    text-align: center;
                    pointer-events: all;
                    opacity: 1;
                    transition: opacity 1s ease;
                " id="fail-alert-content">
                    <div style="display: flex; align-items: center; justify-content: center; gap: .5rem; margin-bottom: .5rem;">
                        <i class="fa-regular fa-circle-xmark" style="font-size: 1.5rem;"></i>
                        <h3 style="font-size: 1.1rem; margin: 0;">${content}</h3>
                    </div>
                    <div>
                        <span style="font-size: .9rem; color: #333;">
                            Oops! Something went wrong.
                        </span>
                    </div>
                </div>
            `;
            document.body.appendChild(div);
            setTimeout(() => {
                const alertBox = div.querySelector('#fail-alert-content');
                alertBox.style.opacity = '0';
            }, 2000);
            setTimeout(() => {
                div.remove();
            }, 3000);
        }
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const editNameForm = document.querySelector('#edit-name-form');
                if (editNameForm) {
                    editNameForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        //saving state
                        const btn = document.querySelector('#profile-name-save-btn');
                        btn.textContent = 'Saving';
                        btn.disabled = true;
                        //

                        const formData = new FormData(editNameForm);
                        try {
                            const response = await fetch("/profileName/edit", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });
                            if (!response.ok) {
                                if (response.status == 422) {
                                    const errorData = await response.json();
                                    if (errorData.errors && errorData.errors.name) {
                                        failAlert(errorData.errors.name[0]);
                                    } else {
                                        failAlert('Username adding fail.');
                                    }
                                } else {
                                    failAlert('Username adding');
                                }
                                return;
                            }
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                document.querySelector('#display-profile-name').textContent = data.name;
                                //alert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                //
                                editNameForm.reset();
                                document.querySelector('#saved-name-button').click();
                            } else {
                                failAlert(result.message);
                            }
                        } catch (error) {
                            alert('Something went wrong.');
                        } finally {
                            btn.disabled = false;
                            btn.textContent = 'Save';
                        }
                    });
                } else {
                    console.log('edit name form');
                }
                const editPhotoForm = document.querySelector('#edit-photo-form');
                if (editPhotoForm) {
                    editPhotoForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        const saveButton = document.querySelector('#profile-photo-save-btn');
                        const originalText = saveButton.textContent;

                        // uploading state
                        saveButton.textContent = 'Uploading';
                        saveButton.disabled = true;
                        //
                        const formData = new FormData(editPhotoForm);
                        try {
                            const response = await fetch("profilePhoto/edit", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.error) {
                                failAlert('File Uploading');
                                console.error('file error');
                            } else if (result.typeError) {
                                failAlert('File Uploading');
                                console.error('type error');
                            }
                            if (result.photoUrl) {
                                document.querySelector("img[alt='Profile Photo']").src = result.photoUrl;
                                editPhotoForm.reset();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                document.querySelector('#saved-photo-button').click();
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.' + error);
                        } finally {
                            saveButton.disabled = false;
                            saveButton.textContent = originalText;
                        }
                    });
                } else {
                    console.log('edit photo form');
                }          
                const recoverPhoneForm = document.querySelector('#recover-phone-form');
                if (recoverPhoneForm) {
                    recoverPhoneForm.addEventListener('submit', async b => {
                        b.preventDefault();
                        const saveBtn = document.querySelector('#recover-phone-save-button');
                        const originalText = saveBtn.textContent;
                        // saving state
                        saveBtn.textContent = 'Saving';
                        saveBtn.disabled = true;

                        const formData = new FormData(recoverPhoneForm);
                        try {
                            const response = await fetch("recoverPhone/add", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            });
                            if (!response.ok) {
                                if (response.status == 422) {
                                    const errorData = await response.json();
                                    if (errorData.errors && errorData.errors.recoverPhone) {
                                        failAlert(errorData.errors.recoverPhone[0]);
                                    } else {
                                        failAlert('Phone number adding fail.');
                                    }
                                } else {
                                    failAlert('Phone number adding fail.');
                                }
                                return;
                            }
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                const phone = document.querySelector('#phone');
                                //alert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                }).then(() => {
                                    if(phone) {
                                        phone.textContent = data.phone;
                                    } else {
                                        location.reload();
                                    }
                                });
                                document.querySelector('#recover-phone-button').click();
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.');
                        } finally {
                            recoverPhoneForm.reset();
                            saveBtn.disabled = false;
                            saveBtn.textContent = originalText;
                        }
                    });
                } else {
                    console.log('Recover phone form');
                }
                const recoverEmailForm = document.querySelector('#recover-email-form');
                if (recoverEmailForm) {
                    recoverEmailForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        const saveBtn = document.querySelector('#recover-email-save-button');
                        const originalText = saveBtn.textContent;
                        // saving state
                        saveBtn.textContent = 'Saving';
                        saveBtn.disabled = true;
                        //
                        const formData = new FormData(recoverEmailForm);
                        try {
                            const response = await fetch("recoverEmail/add", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            });
                            if (!response.ok) {
                                if (response.status == 422) {
                                    const errorData = await response.json();
                                    if (errorData.errors.recoverEmail && errorData.errors) {
                                        failAlert(errorData.errors.recoverEmail[0]);
                                    } else {
                                        failAlert('Email editing');
                                    }
                                } else {
                                    failAlert('Email editing');
                                }
                                return;
                            }
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                const email = document.querySelector('#email');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                }).then(() => {
                                    if(email) {
                                        email.textContent = data.email;
                                    } else {
                                        location.reload();
                                    }
                                });
                                document.querySelector('#recover-email-button').click();
                            } else {
                                failAlert(result.message);
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.');
                        } finally {
                            recoverEmailForm.reset();
                            saveBtn.disabled = false;
                            saveBtn.textContent = originalText;
                        }
                    });
                } else {
                    console.log('Recover Email Form');
                }
                const topupForm = document.querySelector('#topup-form');
                if (topupForm) {
                    topupForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        //processing state
                        const processingBtn = document.querySelector('#topup-btn');
                        processingBtn.textContent = 'processing';
                        processingBtn.disabled = true;
                        //end
                        const formData = new FormData(topupForm);
                        try {
                            const response = await fetch("incomeAmount/added", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                balanceAmount = data.amount;
                                toggleBalance();
                                //alert
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Success!',
                                    text: `You added ${data.added_amount} Ks.`,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                //inbox
                                const item = document.createElement('div');
                                item.classList.add('list-group-item');
                                let inboxContent = '';
                                if (data.content) {
                                    inboxContent = `<p class="text-muted">Description: ${data.content}</p>`;
                                }
                                item.innerHTML = `
                                                <div>
                                                    <small class="text-muted">${data.created_at}</small>
                                                    <div>
                                                        <h6>Money ${data.money_flow}</h6>
                                                        <p class="text-dark">You ${data.money_flow} ${data.added_amount} Ks.</p>
                                                        ${inboxContent}
                                                    </div>
                                                </div>
                                                `;
                                const inbox = document.querySelector('#inbox-list');
                                if (inbox) {
                                    inbox.insertBefore(item, inbox.firstChild);
                                } else {
                                    console.warn('added inbox');
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...!',
                                    text: 'Something went wrong.',
                                })
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong. Error: ' + error.message);
                        } finally {
                            topupForm.reset();
                            processingBtn.disabled = false;
                            processingBtn.textContent = 'Top Up';
                        }
                    });
                } else {
                    console.log('Income Form');
                }
                const payForm = document.querySelector('#pay-form');
                if (payForm) {
                    payForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        // processing state
                        const processingBtn = document.querySelector('#pay-btn');
                        processingBtn.textContent = 'Processing';
                        processingBtn.disabled = true;
                        //end
                        const formData = new FormData(payForm);
                        try {
                            const response = await fetch("amount/used", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                balanceAmount = data.amount;
                                toggleBalance();
                                //alert
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Success!',
                                    text: `You used ${data.used_amount} Ks.`,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                //inbox
                                const item = document.createElement('div');
                                item.classList.add('list-group-item');
                                let inboxContent = '';
                                if (data.content) {
                                    inboxContent = `<p class="text-muted">Description: ${data.content}</p>`;
                                }
                                item.innerHTML = `
                                                <div>
                                                    <small class="text-muted">${data.created_at}</small>
                                                    <div>
                                                        <h6>Money ${data.money_flow}</h6>
                                                        <p class="text-dark">You ${data.money_flow} ${data.used_amount} Ks.</p>
                                                        ${inboxContent}
                                                    </div>
                                                </div>
                                                `;
                                const inbox = document.querySelector('#inbox-list');
                                if (inbox) {
                                    inbox.insertBefore(item, inbox.firstChild);
                                } else {
                                    console.warn('used inbox');
                                }
                            } else if (result.error) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Insufficient Balance!',
                                    text: 'Used amount cannot exceed current balance.',
                                    confirmButtonText: 'Got it',
                                    confirmButtonColor: '#ffc107', // Bootstrap yellow
                                    background: '#fffbe6',          // Soft yellow background
                                    color: '#856404', 
                                });
                            }
                        } catch (error) {
                            alert('Error: ' + error.message);
                        } finally {
                            payForm.reset();
                            processingBtn.disabled = false;
                            processingBtn.textContent = 'Pay';
                        }
                    });
                } else {
                    console.log('spent form')
                }
                const defOpen = document.querySelector('#defaultOpen');
                if (defOpen) {
                    defOpen.click();
                }
            } catch (err) {
                console.error('DOM error:', err);
            }
        });
        function switchLanguage() {
            Swal.fire({
                title: 'Choose Language',
                input: 'radio',
                inputOptions: {
                    en: 'English',
                    my: 'မြန်မာ',
                },
                inputValue: 'en',
                confirmButtonText: 'Switch',
                showCancelButton: true,
            });
        }
    </script>
</body>

</html>