<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>AJAX CRUD</title>
    <style>
        .box {
            min-height: 250px;
            margin: 12px 0;
            padding: 15px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 3px 8px;
            transition: 0.3s;
        }

        .box:hover {
            box-shadow: rgba(0, 0, 0, 0.7) 0px 3px 8px;
        }

        #productList img {
            transition: 0.3s;
        }

        #productList img:hover {
            transform: scale(0.9);
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center alert alert-success my-5">
            <h5 class="fw-bold">Total price <span id="totalPrice"></span></h5>
            <h5 class="fw-bold">Product List</h5>
            <h5 class="fw-bold"><span>Products </span><span id="total"></span></h5>
        </div>

        <div class="d-flex justify-content-between">
            <button id="addProductBtn" class="btn btn-primary fw-bold" data-bs-toggle="modal"
                data-bs-target="#addModal">Add Product</button>
            <input type="text" class="form-control w-25 py-2 shadow" id="search" placeholder="search here">
        </div>
        <div id="productList" class="row mt-3"></div>
        <div id="pagination" class="mt-3"></div>
    </div>

    @include('addProduct')
    @include('updateProduct')
    @include('deleteProduct')

    {{-- bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/5301593776.js" crossorigin="anonymous"></script>
    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            function getProductList(page = 1, searchTerm = '') {
                $.ajax({
                    type: "GET",
                    url: "{{ route('get.product') }}?page=" + page + "&search=" + searchTerm,
                    success: function(response) {
                        $("#productList").empty();
                        $.each(response.data.data, function(index, product) {
                            let productCard =
                                '<div class="col-md-4" style="cursor: pointer" >' +
                                '<div class="box">' +
                                '<img src="' + product.image +
                                '" alt="img" style="width:100%; height: 250px; border-radius: 10px">' +
                                '<div class="fw-bold alert alert-primary d-flex justify-content-between mt-3"><span>' +
                                product.name + '</span>' +
                                '<span><span>' + product.price +
                                '$</span><i data-bs-toggle="modal" data-bs-target="#updateModal" class="fas fa-edit ms-2 updateProductBtn" data-id="' +
                                product.id + '" data-name="' + product.name + '" data-price="' +
                                product.price +
                                '" style="font-size:20px;color:green"></i><i data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="' +
                                product.id +
                                '" style="font-size:24px;color:red" class="deleteProductBtn ms-2 fas fa-times"></i></span></div>' +
                                '</div></div>';
                            $("#productList").append(productCard);
                        });
                        let products = response.products;
                        let totalPrice = 0;
                        for (let i = 0; i < products.length; i++) {
                            totalPrice += products[i].price;
                        }
                        $('#totalPrice').text(totalPrice);
                        $('#total').text(products.length);

                        // Add pagination links
                        let currentPage = response.current_page;
                        let lastPage = response.last_page;
                        let paginationHtml = '';
                        if (lastPage > 1) {
                            paginationHtml += '<ul class="pagination">';
                            if (currentPage > 1) {
                                paginationHtml +=
                                    '<li class="page-item"><a class="page-link" href="#" data-page="' +
                                    (currentPage - 1) + '">Previous</a></li>';
                            }
                            for (let i = 1; i <= lastPage; i++) {
                                if (i == currentPage) {
                                    paginationHtml +=
                                        '<li class="page-item active"><span class="page-link">' + i +
                                        '</span></li>';
                                } else {
                                    paginationHtml +=
                                        '<li class="page-item"><a class="page-link" href="#" data-page="' +
                                        i + '">' + i + '</a></li>';
                                }
                            }
                            if (currentPage < lastPage) {
                                paginationHtml +=
                                    '<li class="page-item"><a class="page-link" href="#" data-page="' +
                                    (currentPage + 1) + '">Next</a></li>';
                            }
                            paginationHtml += '</ul>';
                        }
                        $('#pagination').html(paginationHtml);
                    },
                    error: function(err) {
                        alert(err)
                    }
                });
            }
            // Handle pagination clicks
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                getProductList(page, '');
            });
            // Handle search 
            $('#search').on('keyup', function() {
                let searchTerm = $(this).val();
                getProductList(1, searchTerm);
            });
            // Load initial product list
            getProductList(1, '');

            //save product
            $("#saveProductBtn").click(function() {
                let formData = $("#addProductForm").serialize();
                console.log(formData)
                $.ajax({
                    type: "POST",
                    url: "{{ route('add.product') }}",
                    data: formData,
                    success: function(res) {
                        getProductList(1, '');
                        $("#addModal").modal("hide");
                        $('#addProductForm')[0].reset();
                    },
                    error: function(err) {
                        let error = err.responseJSON;
                        $('.error').empty();
                        $('.is-invalid').removeClass('is-invalid');
                        $.each(error.errors, (index, value) => {
                            $('#' + index).addClass('is-invalid');
                            $('#' + index).parent().find('.error').append(
                                '<span class="text-danger">' + value + '</span>');
                        });
                    }
                });
            });
            // get value for update product
            $(document).on('click', '.updateProductBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let price = $(this).data('price');

                $('#update_id').val(id)
                $('#update_name').val(name)
                $('#update_price').val(price)
            });
            // update product
            $(document).on('click', '#updateProduct', function(e) {
                e.preventDefault();
                let id = $('#update_id').val();
                let name = $('#update_name').val();
                let price = $('#update_price').val();
                $.ajax({
                    url: "{{ route('update.product') }}",
                    method: 'post',
                    data: {
                        id: id,
                        name: name,
                        price: price
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#updateModal').modal('hide');
                            $('#updateProductForm')[0].reset();
                            getProductList(1, '');
                        }
                    },
                    error: function(err) {
                        alert(err)
                        $('#updateProductForm')[0].reset();
                        getProductList(1, '');    
                    }
                })
            });
            // get id for delete product
            $(document).on('click', '.deleteProductBtn', function() {
                let id = $(this).data('id');
                $('#delete_id').val(id)
            });
            //delete product
            $(document).on('click', '#deleteProduct', function(e) {
                e.preventDefault();
                let id = $('#delete_id').val();
                $.ajax({
                    url: "{{ route('delete.product') }}",
                    method: 'post',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            getProductList(1, '');
                            $('#deleteModal').modal('hide');
                            $('#deleteProductForm')[0].reset();
                        }
                    },
                    error: function(err) {
                        alert("something error!")
                        $('#updateProductForm')[0].reset();
                        getProductList(1, '');
                    }
                })
            });
        });
    </script>

</body>

</html>
