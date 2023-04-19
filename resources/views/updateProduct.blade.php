<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <form id="updateProductForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="updateModalLabel">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="number" name="update_id" id="update_id" hidden>
                    <div class="form-group my-3">
                        <label class="fw-bold mb-2">Product Name</label>
                        <input type="text" name="update_name" id="update_name"
                            class="form-control @error('update_name') is-invalid @enderror" placeholder="product name">
                        <div class="error">
                            @error('update_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group my-3">
                        <label class="fw-bold mb-2">Product Price</label>
                        <input type="number" name="update_price" id="update_price" class="form-control"
                            placeholder="product price">
                        <div class="error">
                            @error('update_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateProduct">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
