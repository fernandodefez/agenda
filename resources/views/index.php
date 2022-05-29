<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- Icon --->
    <link rel="icon" href="/assets/images/icon.svg">


   <!-- Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

   <title>Agenda</title>
</head>

<body class="">
   <header class="col-12 col-sm-11 col-md-10 col-lg-9 col-xl-8 p-4 mx-auto">
      <nav class="navbar navbar-light">
         <div class="container-fluid px-0 d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="">
               <img src="/assets/images/icon.svg" alt="" loading="lazy" width="40" height="40" class="d-inline-block">
                &nbsp;
                Agenda
            </a>
             <!-- Button trigger modal -->
             <button type="button" class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#new-modal">
                 <i class="bi bi-plus-lg"></i>
                 New
             </button>
         </div>
      </nav>
   </header>
   <main class="col-12 col-sm-11 col-md-10 col-lg-9 col-xl-8 p-4 mx-auto mt-2">
       <div class="row g-4" id="contacts-list">
       </div>
   </main>

   <!-- Modal -->
   <div class="modal fade" id="new-modal" tabindex="-1" aria-labelledby="new-modal-label" aria-hidden="true">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">
                       New contact
                   </h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <form class="" id="form" action="api/v1/contact" method="post">
                   <div class="modal-body">
                       <div class="row mb-3">
                           <div class="col-12 mb-3 mb-lg-0 col-lg-6" id="name-group">
                               <label for="name" class="form-label">
                                   Name
                               </label>
                               <input type="text" class="form-control form-control-sm" id="name" placeholder="Enter your name" name="name">
                           </div>
                           <div class="col-12 col-lg-6" id="lastname-group">
                               <label for="lastname" class="form-label">
                                   Lastname
                               </label>
                               <input type="text" class="form-control form-control-sm" id="lastname" placeholder="Enter your lastname" name="lastname">
                           </div>
                       </div>
                       <div class="col-12 mb-3" id="email-group">
                           <label for="email" class="form-label">
                               Email
                           </label>
                           <input type="email" class="form-control form-control-sm" id="email" placeholder="Enter your email" name="email">
                       </div>
                       <div class="mb-3" id="phone-group">
                           <label for="phone" class="form-label">
                               Phone number
                           </label>
                           <input type="text" class="form-control form-control-sm" id="phone" placeholder="Enter your phone number" name="phone">
                       </div>
                       <div class="mb-3" id="thumbnail-group">
                           <label for="thumbnail" class="form-label" lang="en">
                               Select a thumbnail
                           </label>
                           <input class="form-control form-control-sm" id="thumbnail" type="file" name="thumbnail" lang="en">
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                           Close
                       </button>
                       <button type="submit" class="btn btn-primary fw-bold d-block" id="submit">
                           Submit
                       </button>
                       <button class="btn btn-primary d-none" type="button" disabled id="spinner">
                           <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                           Sending...
                       </button>
                   </div>
               </form>
           </div>
       </div>
   </div>

   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- JavaScript Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>


    <script type="text/javascript">

        const BASE_URL = window.origin;
        const request = new XMLHttpRequest();

        fetchContacts();

        let form = document.getElementById('form');

        const modal = new bootstrap.Modal(document.getElementById('new-modal'), {
            keyboard: true
        });

        // Reset form and remove all validation feedback
        document.getElementById('new-modal').addEventListener('show.bs.modal', (event) => {
            form.reset();
            removeValidationFeedback();
        });

        // Abort request when hiding the modal
        document.getElementById('new-modal').addEventListener('hide.bs.modal', (event) => {
            request.abort();
        });

        function removeValidationFeedback() {
            document.getElementById("name").classList.remove('is-invalid');
            document.getElementById("lastname").classList.remove('is-invalid');
            document.getElementById("email").classList.remove('is-invalid');
            document.getElementById("phone").classList.remove('is-invalid');
            document.getElementById("thumbnail").classList.remove('is-invalid');
            const errors = document.querySelectorAll('.invalid-feedback');
            errors.forEach(error => {
                error.remove();
            });
            document.getElementById('submit').classList.replace('d-none', 'd-block');
            document.getElementById('spinner').classList.replace('d-block', 'd-none');
        }

        /**
         * @callback form on submit
         * @param event
         * */
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            removeValidationFeedback();

            let formData = new FormData(this);
            const thumbnail = document.getElementById("thumbnail").files[0];
            formData.append('thumbnail', thumbnail);

            document.getElementById('submit').classList.replace('d-block', 'd-none');
            document.getElementById('spinner').classList.replace('d-none', 'd-block');

            request.open("POST", BASE_URL + '/api/v1/contacts');
            request.send(formData);
            request.onload = function () {
                document.getElementById('submit').classList.replace('d-none', 'd-block');
                document.getElementById('spinner').classList.replace('d-block', 'd-none');
            }
            request.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    let data = JSON.parse(this.response);
                    if (!data.success) {
                        if (data.errors.out_of_bounds) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.errors.out_of_bounds,
                            });
                        }
                        if (data.errors.name) {
                            document.getElementById("name").classList.toggle("is-invalid");
                            document.getElementById("name-group").insertAdjacentHTML
                            ('beforeend', `<div class="invalid-feedback mb-1 text-small"> ${data.errors.name} </div>`);
                        }
                        if (data.errors.lastname) {
                            document.getElementById("lastname").classList.toggle("is-invalid");
                            document.getElementById("lastname-group").insertAdjacentHTML
                            ('beforeend', `<div class="invalid-feedback mb-1 text-small"> ${data.errors.lastname} </div>`);
                        }

                        if (data.errors.email) {
                            document.getElementById("email").classList.toggle("is-invalid");
                            document.getElementById("email-group").insertAdjacentHTML
                            ('beforeend', `<div class="invalid-feedback text-small"> ${data.errors.email} </div>`);
                        }
                        if (data.errors.phone) {
                            document.getElementById("phone-group").insertAdjacentHTML
                            ('beforeend', `<div class="invalid-feedback text-small"> ${data.errors.phone} </div>`);
                            document.getElementById("phone").classList.toggle("is-invalid");
                        }
                        if (data.errors.thumbnail) {
                            document.getElementById("thumbnail-group").insertAdjacentHTML
                            ('beforeend', `<div class="invalid-feedback text-small"> ${data.errors.thumbnail} </div>`);
                            document.getElementById("thumbnail").classList.toggle("is-invalid");
                        }
                    } else {
                        modal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Done',
                            text: data.message,
                        }).then(() => {
                            fetchContacts();
                        });
                    }
                }
            }
            request.onerror = function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            }
            request.onabort = function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Request canceled',
                    text: 'Your request was canceled!',
                });
            }
        });

        /**
         * @author Fernando Defez
         * @function fetchContacts
         * @async
         * */
        async function fetchContacts() {
            const response = await fetch(BASE_URL + '/api/v1/contacts');
            const contacts = await response.json();

            if (!response.ok) {
                location.reload();
                return;
            }

            let html = "";

            // Set card's placeholders skeletons
            setPlaceholders(contacts.length);

            setTimeout(function () {
                if (contacts.length === 0) {
                    html =
                        `<div class="col-12 d-flex p-2 py-5 justify-content-center">
                            <p class="fw-bold mb-0"> You don't have contacts yet!</p>
                        </div>`;
                } else {
                    for (const contact of contacts) {
                        html +=
                            `<div class="col col-12 col-sm-6 col-lg-4">
                            <div class="card">
                                <div class="col-12 p-0 card-img-top overflow-hidden" style="height: 200px; position: relative">
                                    <img src="storage/contacts/${contact.thumbnail}"
                                        loading="lazy"
                                        class="col-12" alt="..."
                                        style="object-fit: cover; height: 100%; position: absolute; top: 0;"
                                    >
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"> ${contact.name + " " + contact.lastname} </h5>
                                    <p class="card-text mb-0">
                                        ${contact.email}
                                    </p>
                                    <p class="card-text fw-light mb-0">
                                        ${contact.phone}
                                    </p>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-danger btn-sm align-right" onclick="remove(${contact.id})">
                                            <i class="bi bi-trash-fill"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                }
                document.getElementById("contacts-list").innerHTML = "";
                document.getElementById('contacts-list').innerHTML = html;
            }, 1000);
        }

        /**
         * @author Fernando Defez
         * @function remove handles the remove contact request
         * @param id is contact id
         * */
        async function remove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    let req = new XMLHttpRequest();
                    req.open("DELETE", BASE_URL + '/api/v1/contacts');

                    req.onreadystatechange = function () {
                        if (this.readyState === 4) {
                            const data = JSON.parse(this.response);
                        }
                    }
                    req.onerror = function () {

                    }

                    req.send("id="+id);
                }
                fetchContacts();
            })
            // Refresh the contacts list
        }

        /**
         * @author Fernando Defez
         * @function setPlaceholder draw placeholder within the DOM
         * */
        function setPlaceholders(length) {
            let html = "";
            for (let i = 0; i < length; i++) {
                html +=
                `<div class="col col-12 col-sm-6 col-lg-4 contact-placeholder">
                    <div class="card">
                        <div class="col-12 placeholder-wave p-0 card-img-top overflow-hidden" style="height: 200px; position: relative">
                            <div class="col-12 bg-secondary placeholder" style="height: 200px;">
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="placeholder-wave">
                                <span class="placeholder col-6"></span>
                            </p>
                            <p class="placeholder-wave">
                                <span class="placeholder col-9"></span>
                            </p>
                            <p class="placeholder-wave">
                                <span class="placeholder col-4"></span>
                            </p>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="" tabindex="-1" class="btn btn-danger btn-sm disabled placeholder col-4 text-end"></a>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            }
            document.getElementById("contacts-list").innerHTML = html;
        }
    </script>

</body>

</html>