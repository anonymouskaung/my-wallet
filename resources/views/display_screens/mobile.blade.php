<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: #f8fafc;
        }

        .wallet-header {
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .wallet-balance {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-tabs {
            background: #fff;
        }

        .nav-link {
            color: gray;
        }

        .tab-content {
            padding: 1rem 0;
        }

        .profile-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        textarea {
            text-transform: capitalize;
        }

        textarea::placeholder {
            text-transform: none;
        }

        .offcanvas-custom-speed {
            transition: none;
        }

        .nav-index {
            z-index: 1;
        }

        .layout-header {
            padding: 8px;
        }

        .layout-body {
            padding: 16px;
        }

        .layout-body-title {
            padding-top: 8px;
            padding-bottom: 16px;
        }

        .layout-footer {
            padding: 8px;
        }
        #password-change.offcanvas {
            z-index: 1060;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-2 pt-2">
        <!-- Header -->
        <div class="card wallet-header mb-2">
            <div class="card-body d-flex align-items-center">
                <img src="{{ asset('images/profiles/' . (auth()->user()->photo ?? 'profile.png')) }}"
                    class="rounded-circle profile-img me-3" alt="Profile Photo">
                <div>
                    <div id="profile-name" class="fw-bold" style="font-family: 'Times New Roman', Times, serif;">{{
                        auth()->user()->name
                        ?? 'nickname' }}</div>
                    <div>
                        <i class="fa-solid fa-wallet" style="font-size: 1.2rem; font-weight: bold;"></i>
                        <span class="wallet-balance">{{ $balance }}</span> Ks
                        <span onclick="toggleEye()">
                            <i class="fa-regular fa-eye eye" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content" id="walletTabContent" style="min-height: 80vh;">
            <!-- Wallet Tab -->
            <div class="tab-pane fade show active" id="wallet" role="tabpanel">
                <!-- Top Up Form -->
                <form id="topup-form" class="mb-3">
                    <div class="card">
                        <div class="card-header text-center">Top Up</div>
                        <div class="card-body">
                            <label for="mobile-amountID" class="form-label">Amount</label>
                            <input id="mobile-amountID" type="number" class="form-control mb-2" name="addedAmount"
                                placeholder="Enter the amount">
                            <label for="mobile-descriptionID" class="form-label">Description</label>
                            <textarea id="mobile-descriptionID" class="form-control mb-2" name="topupDescription"
                                placeholder="e.g. Weekly Pass" onblur="titleCase(this)"></textarea>
                        </div>
                        <div class="card-footer">
                            <button id="topup-btn" type="submit" class="btn btn-success w-100">Top Up</button>
                        </div>
                    </div>
                </form>
                <!-- Pay Form -->
                <form id="pay-form">
                    <div class="card">
                        <div class="card-header text-center">Pay</div>
                        <div class="card-body">
                            <label for="mobile-payID" class="form-label">Amount</label>
                            <input id="mobile-payID" type="number" name="usedAmount" class="form-control mb-2"
                                placeholder="Enter the amount">
                            <label for="mobile-descriptionPayID" class="form-label">Description</label>
                            <textarea id="mobile-descriptionPayID" class="form-control" name="payDescription"
                                placeholder="e.g. Phone Bill" onblur="titleCase(this)"></textarea>
                        </div>
                        <div class="card-footer">
                            <button id="pay-btn" type="submit" class="btn btn-primary w-100">Pay</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Inbox Tab -->
            <div class="tab-pane fade" id="inbox" role="tabpanel">
                @if(isset($inboxes))
                <div id="inbox-list" class="list-group">
                    @foreach($inboxes as $inbox)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">{{ $inbox->created_at->format('j M Y') }}</small>
                                <div>
                                    <h6>Money {{ $inbox->money_flow }}</h6>
                                    <p class="text-dark">You {{ $inbox->money_flow }} {{ $inbox->amount }} Ks.</p>
                                    @if($inbox->content)
                                    <p class="text-muted">Description: {{ $inbox->content }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <!-- Profile Tab -->
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="list-group mb-3 rounded-bottom-0">
                    <li class="list-group-item text-muted">User Info</li>
                    @if(isset(auth()->user()->email))
                    <li id="email" class="list-group-item">{{ auth()->user()->email }}</li>
                    @endif
                    @if(isset(auth()->user()->phone))
                    <li id="phone" class="list-group-item">{{ auth()->user()->phone }}</li>
                    @endif
                </ul>
                <div class="list-group mb-3 rounded-top-0">
                    <span class="list-group-item text-muted">Account</span>
                    <button id="recover-phone" type="button" onclick="rotate(this, 90)" data-bs-toggle="collapse"
                        data-bs-target="#recover-phone-content" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-square-phone me-2"></i>
                        Edit Phone Number
                        <span class="float-end text-muted">
                            <i class="fa-solid fa-chevron-right rotate-item"></i>
                        </span>
                    </button>
                    <div id="recover-phone-content" class="collapse bg-light">
                        <form class="p-3" id="edit-recover-phone-form">
                            <div class="input-group">
                                <input type="number" name="recoverPhone" class="form-control"
                                    placeholder="Enter Recover Phone Number" required>
                                <button id="recover-phone-submit" type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                    <button id="recover-email" type="button" onclick="rotate(this, 90)"
                        class="list-group-item list-group-item-action" data-bs-toggle="collapse"
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
                                <button id="recover-email-form-submit" type="submit"
                                    class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                    <button id="edit-profile-name" type="button" onclick="rotate(this, 90)" data-bs-toggle="collapse"
                        data-bs-target="#edit-name-content" class="list-group-item list-group-item-action">
                        <i class="fa-regular fa-pen-to-square me-2"></i>
                        Edit Profile Name
                        <span class="float-end text-muted">
                            <i class="fa-solid fa-chevron-right rotate-item"></i>
                        </span>
                    </button>
                    <div id="edit-name-content" class="collapse bg-light">
                        <form class="p-3" id="edit-profile-name-form">
                            <div class="input-group">
                                <input name="name" type="text" class="form-control" placeholder="Enter profile name"
                                    autocomplete="username">
                                <input id="profile-name-save-btn" type="submit" class="btn btn-primary" value="Save">
                            </div>
                        </form>
                    </div>
                    <button id="edit-profile-photo" onclick="rotate(this, 90)" type="button" data-bs-toggle="collapse"
                        data-bs-target="#edit-photo-content" class="list-group-item list-group-item-action">
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
                                <input type="submit" id="profile-photo-save-btn" class="btn btn-primary" value="Upload">
                            </div>
                        </form>
                    </div>
                    <button id="change-password" type="button" class="list-group-item list-group-item-action"
                        data-bs-toggle="modal" data-bs-target="#change-password-home">
                        <i class="fa-solid fa-lock"></i>
                        Change Password
                    </button>
                    <div id="change-password-home" class="modal" tabindex="-1">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <form id="password-confirm-form">
                                    <div class="layout-header">
                                        <button type="reset" class="btn p-1" style="font-size: x-large; font-weight: bold;"
                                            data-bs-dismiss="modal">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </button>
                                    </div>
                                    <div class="layout-body">
                                        <h1 class="mb-5">
                                            Confirm your account
                                        </h1>
                                        <div class="mb-3 position-relative">
                                            <input id="password-confirm" type="password" name="password" class="form-control form-control-lg pe-5"
                                                placeholder="Enter current password.">
                                            <button type="button" class="btn btn-lg position-absolute top-50 end-0 translate-middle-y border-0"
                                            onclick="togglePasswordVisibility('password-confirm', this)">
                                                <i class="fa-regular fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="layout-footer">
                                        <button id="password-confirm-submit" type="submit"
                                            class="btn btn-primary w-100 rounded-5 text-white">
                                            Next
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas offcanvas-end bg-white" tabindex="-1" id="password-change"
                        style="height: 100vh;">
                        <form id="password-change-form">
                            <div class="layout-header">
                                <button type="reset" class="btn p-1" style="font-size: x-large; font-weight: bold;"
                                    data-bs-dismiss="offcanvas">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                            </div>
                            <div class="layout-body">
                                <div class="layout-body-title">
                                    <h1>
                                        Change Password
                                    </h1>
                                    <p class="text-muted">You can now change your password.</p>
                                </div>
                                <div class="position-relative">
                                    <input id="password" type="password" name="password" class="form-control form-control-lg pe-5"
                                        placeholder="Enter password" autofocus required minlength="8">
                                    <button type="button"
                                        class="btn btn-lg position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                        onclick="togglePasswordVisibility('password', this)"
                                        >
                                        <i class="fa-regular fa-eye-slash"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    Password must contain at least one uppercase letter, one lowercase letter, and
                                    one number.
                                </div>
                            </div>
                            <div class="layout-footer">
                                <button id="password-change-submit" type="submit" class="btn btn-primary w-100 rounded-5">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>
                    <a id="logout" href="{{ url('/logout')}}" class="list-group-item list-group-item-action text-danger">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                        logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">@csrf
                    </form>
                </div>
                <div class="list-group rounded-top-0">
                    <span class="list-group-item text-muted">Setting</span>
                    <button type="button" class="list-group-item list-group-item-action" onclick="switchLanguage()">
                        Switch Language
                    </button>
                </div>
            </div>
        </div>
        <!-- Tabs -->
        <ul class="nav nav-tabs nav-index nav-fill sticky-bottom border-top rounded" id="walletTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet"
                    type="button" role="tab">Wallet</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button"
                    role="tab">Inbox</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab">Profile</button>
            </li>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var balanceAmount;
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
        function toggleEye() {
            const balance = document.querySelector('.wallet-balance');
            const eyeIcon = document.querySelector('.eye');
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
        function titleCase(x) {
            x.value = x.value.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }
        function toggleBalance() {
            const balance = document.querySelector('.wallet-balance');
            if (balance && localStorage.getItem('eye') == 'open') {
                balance.textContent = balanceAmount;
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
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
                                'Accept': 'application/json',
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
                                icon: 'success',
                                title: 'Success!',
                                text: `You added ${data.added_amount} Ks.`
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
                            alert('Top Up');
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
                                'Accept': 'application/json',
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
                                icon: 'success',
                                title: 'Success!',
                                text: `You used ${data.used_amount} Ks.`,
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
                                title: 'Oops...!',
                                text: 'Used amount cannot exceed the current balance'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...!',
                                text: 'Something went wrong.',
                            })
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
            const editRecoverPhone = document.querySelector('#edit-recover-phone-form');
            if (editRecoverPhone) {
                editRecoverPhone.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const result = await Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it!"
                    });
                    if (result.isConfirmed) {
                        //
                        const btn = document.querySelector('#recover-phone-submit');
                        btn.textContent = 'Saving';
                        btn.disabled = true;
                        //
                        const formData = new FormData(editRecoverPhone);
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
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Warning!',
                                            text: errorData.errors.recoverPhone[0],
                                        });
                                    } else {
                                        alert('Phone number adding fail.');
                                    }
                                } else {
                                    alert('Phone number adding fail.');
                                }
                                return;
                            }
                            const resultData = await response.json();
                            if (resultData.success) {
                                const data = resultData.data;
                                document.querySelector('#phone').textContent = data.phone;
                                editRecoverPhone.reset();
                                //alert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                });
                                document.querySelector('#recover-phone').click();
                            }
                        } catch (err) {
                            alert('Error:' + err.message);
                        } finally {
                            btn.disabled = false;
                            btn.textContent = 'Save';
                        }
                    }
                });
            } else {
                console.log('phone form');
            }
            const recoverEmailForm = document.querySelector('#recover-email-form');
            if (recoverEmailForm) {
                recoverEmailForm.addEventListener('submit', async e => {
                    e.preventDefault();
                    const result = await Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it!"
                    });
                    if (result.isConfirmed) {
                        const saveBtn = document.querySelector('#recover-email-form-submit');
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
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Warning!',
                                            text: errorData.errors.recoverEmail[0],
                                        });
                                    } else {
                                        alert('Oops! Something went wrong.');
                                    }
                                } else {
                                    alert('Oops! Something went wrong.');
                                }
                                return;
                            }
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                document.querySelector('#email').textContent = data.email;
                                recoverEmailForm.reset();
                                //alert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                });
                                //
                                document.querySelector('#recover-email').click();
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.' + error.message);
                        } finally {
                            saveBtn.disabled = false;
                            saveBtn.textContent = 'Save';
                        }
                    }
                });
            } else {
                console.log('Recover Email Form');
            }
            const editNameForm = document.querySelector('#edit-profile-name-form');
            if (editNameForm) {
                editNameForm.addEventListener('submit', async e => {
                    e.preventDefault();
                    const result = await Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it!"
                    });
                    if (result.isConfirmed) {
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
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Warning',
                                            text: errorData.errors.name[0],
                                        });
                                    } else {
                                        alert('Oops!');
                                    }
                                } else {
                                    alert('Oops!');
                                }
                                return;
                            }
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                document.querySelector('#profile-name').textContent = data.name;
                                //alert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                });
                                //
                                editNameForm.reset();
                                document.querySelector('#edit-profile-name').click();
                            }
                        } catch (error) {
                            alert('Error:' + error.message);
                        } finally {
                            btn.disabled = false;
                            btn.textContent = 'Save';
                        }
                    }
                });
            } else {
                console.log('profile name form');
            }
            const editPhotoForm = document.querySelector('#edit-photo-form');
            if (editPhotoForm) {
                editPhotoForm.addEventListener('submit', async e => {
                    e.preventDefault();
                    const result = await Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it!"
                    });
                    if (result.isConfirmed) {
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
                                alert('File Uploading fail!');
                                console.error('file error');
                            } else if (result.typeError) {
                                alert('File Uploading fail!');
                                console.error('type error');
                            }
                            if (result.photoUrl) {
                                document.querySelector("img[alt='Profile Photo']").src = result.photoUrl;
                                editPhotoForm.reset();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                });
                                document.querySelector('#edit-profile-photo').click();
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.' + error);
                        } finally {
                            saveButton.disabled = false;
                            saveButton.textContent = originalText;
                        }
                    }
                });
            } else {
                console.log('profile photo form');
            }
            const logout = document.querySelector('#logout');
            const logoutForm = document.querySelector('#logout-form');
            if (logout && logoutForm) {
                logout.addEventListener('click', async function (e) {
                    e.preventDefault();
                    const result = await Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it!"
                    });
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            } else {
                console.log('logout & logout form');
            }
            const passwordConfirmForm = document.querySelector('#password-confirm-form');
            if (passwordConfirmForm) {
                passwordConfirmForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    //
                    const btn = document.querySelector('#password-confirm-submit');
                    btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    `;
                    btn.disabled = true;
                    //
                    const formData = new FormData(passwordConfirmForm);
                    try {
                        const response = await fetch('/password/confirmed', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                const offcanvas = new bootstrap.Offcanvas('#password-change', {
                                    backdrop: false
                                });
                                offcanvas.show();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sorry!',
                                text: 'The password is not correct.',
                            }).then(() => {
                                passwordConfirmForm.reset();
                            });
                        }
                    } catch (err) {
                        alert('Error:' + err.message);
                    } finally {
                        passwordConfirmForm.reset();
                        btn.disabled = false;
                        btn.textContent = 'Next';
                    }
                });
            } else {
                console.log('password confirm form');
            }
            const passwordChangeForm = document.querySelector('#password-change-form');
            if(passwordChangeForm) {
                passwordChangeForm.addEventListener('submit', async e => {
                    e.preventDefault();
                    //
                    const btn = document.querySelector('#password-change-submit');
                    btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    `;
                    btn.disabled = true;
                    //
                    const formData = new FormData(passwordChangeForm);
                    try {
                        const response = await fetch('password/changes', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });
                        if(!response.ok) {
                            if(response.status == 422) {
                                const errorData = await response.json();
                                if(errorData.errors && errorData.errors.password) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Sorry!',
                                        text: errorData.errors.password[0],
                                    });
                                } else {
                                    alert('Password Changing fail.');
                                }
                            } else {
                                alert('Password Changing fail.');
                            }
                            return;
                        }
                        const result = await response.json();
                        if(result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                const offcanvasElement = document.querySelector('#password-change');
                                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                                if(offcanvas) {
                                    offcanvas.hide();
                                }
                                const modalElement = document.querySelector('#change-password-home');
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                if(modal) {
                                    modal.hide();
                                }
                            });
                        }
                    } catch (err) {
                        alert('Error:' + err.message);
                    } finally {
                        passwordChangeForm.reset();
                        btn.disabled = false;
                        btn.textContent = 'Next';
                    }
                });
            } 
        });
        function togglePasswordVisibility(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if(input.type == 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }
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