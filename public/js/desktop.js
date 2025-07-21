        document.addEventListener('DOMContentLoaded', function () {
            try {
                console.log('Desktop.js');
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const editNameForm = document.querySelector('#edit-name-form');
                editNameForm.addEventListener('submit', async e => {
                    document.querySelector('#profile-name-save-btn').value = 'Saving';
                    e.preventDefault();
                    const formData = new FormData(editNameForm);
                    const newProfileName = formData.get('editName');
                    try {
                        const response = await fetch("/profileName/edit", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            document.querySelector('#display-profile-name').textContent = newProfileName;
                            editNameForm.reset();
                            document.querySelector('#profile-name-save-btn').value = 'Save';
                            document.querySelector('#saved-name-button').click();
                        } else {
                            alert(result.message);
                        }
                    } catch (error) {
                        alert('Something went wrong.');
                    }
                });
                const editPhotoForm = document.querySelector('#edit-photo-form');
                editPhotoForm.addEventListener('submit', async e => {
                    e.preventDefault();
                    const saveButton = document.querySelector('#profile-photo-save-btn');
                    const originalText = saveButton.textContent;

                    // uploading state
                    saveButton.textContent = 'Uploading...';
                    saveButton.disabled = true;

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
                            alert('File error');
                        } else if (result.typeError) {
                            alert('Photo type error');
                        }
                        if (result.photoUrl) {
                            document.querySelector("img[alt='Profile Photo']").src = result.photoUrl;
                            editPhotoForm.reset();
                            document.querySelector('#saved-photo-button').click();
                        }
                    } catch (error) {
                        alert('Oops! Something went wrong.');
                    } finally {
                        saveButton.disabled = false;
                        saveButton.textContent = originalText;
                    }
                });
                const changePasswordForm = document.querySelector('#change-password-form');
                changePasswordForm.addEventListener('submit', async a => {
                    a.preventDefault();
                    const changeButton = document.querySelector('#change-button');
                    const originalText = changeButton.textContent;
                    //changing state
                    changeButton.textContent = 'Changing';
                    changeButton.disabled = true;

                    const formData = new FormData(changePasswordForm);
                    try {
                        const response = await fetch("changePassword/change", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            changePasswordForm.reset();
                            document.querySelector('#change-password-button').click();
                        } else {
                            alert('Form uploading filed.')
                        }
                    } catch (error) {
                        alert('Oops! Something went wrong.');
                    } finally {
                        changeButton.disabled = false;
                        changeButton.textContent = originalText;
                    }
                })
                //error have between two.
                const recoverPhoneForm = document.querySelector('#recover-phone-form');
                if (recoverPhoneForm) {
                    recoverPhoneForm.addEventListener('submit', async b => {
                        b.preventDefault()
                        const saveBtn = document.querySelector('#recover-phone-save-button');
                        const originalText = saveBtn.textContent;
                        // saving state
                        saveBtn.textContent = 'Saving';
                        saveBtn.disabled = true;

                        const formData = new FormData(recoverPhoneForm);
                        const phone = formData.get('recoverPhone');
                        try {
                            const response = await fetch("recoverPhone/add", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.success) {
                                document.querySelector('#phone').textContent = phone;
                                recoverPhoneForm.reset();
                                document.querySelector('#recover-phone-button').click();

                            } else {
                                alert('Form uploading filed.');
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.');
                        } finally {
                            saveBtn.disabled = false;
                            saveBtn.textContent = 'Save';
                        }
                    });
                } else {
                    console.log('Recover phone form');
                }
                const recoverEmailForm = document.querySelector('#recover-email-form-lol');
                if (recoverEmailForm) {
                    recoverEmailForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        const saveBtn = document.querySelector('#recover-email-save-button');
                        const originalText = saveBtn.textContent;

                        saveBtn.textContent = 'Saving';
                        saveBtn.disabled = true;

                        const formData = new FormData(recoverEmailForm);
                        const email = formData.get('recoverEmail');
                        try {
                            const response = await fetch("recoverEmail/add", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.success) {
                                document.querySelector('#email').textContent = email;
                                recoverEmailForm.reset();
                                document.querySelector('#recover-email-button').click();
                            } else {
                                alert('Form submission failed.');
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong.');
                        } finally {
                            saveBtn.disabled = false;
                            saveBtn.textContent = originalText;
                        }
                    });
                } else {
                    console.log('Recover Email Form');
                }
                const desktopForm = document.querySelector('#topup-form');
                if (desktopForm) {
                    desktopForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        //processing state
                        const processingBtn = document.querySelector('#topup-btn');
                        processingBtn.textContent = 'processing';
                        processingBtn.disabled = true;
                        //end
                        const formData = new FormData(desktopForm);
                        try {
                            const response = await fetch("incomeAmount/added", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                balanceAmount = data.amount;
                                toggleBalance();
                                //inbox
                                const item = document.createElement('div');
                                item.classList.add('list-group-item', 'list-group-item-action');
                                let inboxContent = '';
                                if (data.content) {
                                    inboxContent = `<p>Earn: ${data.content}</p>`;
                                }
                                item.innerHTML = `
                                                <div>
                                                    <div class="text-dark">
                                                        <small>${data.created_at}</small>
                                                    </div>
                                                    <div>
                                                        <h6>Money ${data.money_flow}</h6>
                                                        <p>You ${data.money_flow} ${data.added_amount} Ks.</p>
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
                                alert('Sever Error!');
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong. Error: ' + error.message);
                        } finally {
                            desktopForm.reset();
                            processingBtn.disabled = false;
                            processingBtn.textContent = 'Top Up';
                        }
                    });
                } else {
                    console.log('Income Form');
                }
                const desktopSpentForm = document.querySelector('#desktop-spent-form');
                if (desktopSpentForm) {
                    desktopSpentForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        // processing state
                        const processingBtn = document.querySelector('#pay-btn');
                        processingBtn.textContent = 'Processing';
                        processingBtn.disabled = true;
                        //end
                        const formData = new FormData(desktopSpentForm);
                        try {
                            const response = await fetch("amount/used", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: formData
                            });
                            const result = await response.json();
                            if (result.success) {
                                const data = result.data;
                                balanceAmount = data.amount;
                                toggleBalance();
                                //inbox
                                const item = document.createElement('div');
                                item.classList.add('list-group-item', 'list-group-item-action');
                                let inboxContent = '';
                                if (data.content) {
                                    inboxContent = `<p>Description: ${data.content}</p>`;
                                }
                                item.innerHTML = `
                                                <div>
                                                    <div class="text-dark">
                                                        <small>${data.created_at}</small>
                                                    </div>
                                                    <div>
                                                        <h6>Money ${data.money_flow}</h6>
                                                        <p>You ${data.money_flow} ${data.used_amount} Ks.</p>
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
                                alert('Used amount cannot exceed current balance');
                            } else {
                                alert('Sever Error!');
                            }
                        } catch (error) {
                            alert('Oops! Something went wrong. Error: ' + error.message);
                        } finally {
                            desktopSpentForm.reset();
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