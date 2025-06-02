@include('admin.layouts.header')
@include('admin.layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-content">
    <div class="container-fluid">
        <div class="card mt-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0">Product List</h5>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addProductModal">+ Add
                    Product</button>
            </div>

            <div class="card-body">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">Filter by Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Categories</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Variations</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $key => $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if(!empty($categories) && count($categories) > 0)
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif

                                    </td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if ($product->image)
                                            <img src="{{ asset('uploads/products/' . $product->image) }}" class="img-thumbnail"
                                                width="50">
                                        @else
                                            <span class="badge bg-light text-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse ($product->variations as $variation)
                                            <div class="border p-1 mb-1 small">
                                                <strong>SKU:</strong> {{ $variation->sku }}<br>
                                                <strong>Options:</strong>
                                                @if (!empty($variations) && count($variations) > 0)
                                                    @foreach($variations as $variation)
                                                        <div>{{ $variation->name }}</div>
                                                    @endforeach
                                                @endif
                                                <strong>Price:</strong> ₹{{ number_format($variation->price, 2) }},
                                                <strong>Stock:</strong> {{ $variation->stock }}
                                            </div>
                                        @empty
                                            <span class="text-muted">No Variations</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editProductBtn mb-2"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-description="{{ $product->description }}"
                                            data-price="{{ $product->price }}" data-status="{{ $product->status }}"
                                            data-categories="{{ implode(',', $product->categories->pluck('id')->toArray()) }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($products, 'links'))
                    <div class="mt-4">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>



<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addProductLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                    id="addProductForm">
                    @csrf

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="add_name" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="add_description" class="form-label">Description</label>
                        <textarea name="description" id="add_description" class="form-control" rows="3"
                            required></textarea>
                    </div>

                    <!-- Base Price -->
                    <div class="mb-3">
                        <label for="add_price" class="form-label">Base Price</label>
                        <input type="number" step="0.01" name="price" id="add_price" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="add_status_active" value="1"
                                checked>
                            <label class="form-check-label" for="add_status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="add_status_inactive"
                                value="0">
                            <label class="form-check-label" for="add_status_inactive">Inactive</label>
                        </div>
                    </div>

                    <!-- Categories (Multi-select) -->
                    <div class="mb-3">
                        <label class="form-label">Categories</label>
                        <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="{{ $category->id }}"
                                        id="add_category_{{ $category->id }}" name="category_ids[]">
                                    <label class="form-check-label" for="add_category_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach

                            @if(count($categories) == 0)
                                <div class="text-muted">No categories available</div>
                            @endif
                        </div>
                        <!-- Display for selected categories -->
                        <div id="add-selected-categories" class="mt-2 small text-muted">No categories selected</div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="add_images" class="form-label">Upload Images</label>
                        <input type="file" name="images[]" id="add_images" class="form-control" multiple>
                        <small class="text-muted">You can upload multiple images.</small>
                    </div>

                    <!-- Variations -->
                    <div class="mb-3">
                        <label class="form-label">Variations</label>
                        <div id="add_variations_container">
                            <!-- Variation fields will be appended here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" id="add_variation_button">+ Add
                            Variation</button>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editProductLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="editProductForm">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="product_id" id="edit_product_id">

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"
                            required></textarea>
                    </div>

                    <!-- Base Price -->
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Base Price</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="edit_status_active"
                                value="1">
                            <label class="form-check-label" for="edit_status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="edit_status_inactive"
                                value="0">
                            <label class="form-check-label" for="edit_status_inactive">Inactive</label>
                        </div>
                    </div>

                    <!-- Categories (Multi-select) -->
                    <div class="mb-3">
                        <label class="form-label">Categories</label>
                        <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input edit-category-checkbox" type="checkbox"
                                        value="{{ $category->id }}" id="edit_category_{{ $category->id }}"
                                        name="category_ids[]">
                                    <label class="form-check-label" for="edit_category_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach

                            @if(count($categories) == 0)
                                <div class="text-muted">No categories available</div>
                            @endif
                        </div>
                        <!-- Display for selected categories -->
                        <div id="edit-selected-categories" class="mt-2 small text-muted">No categories selected</div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="edit_images" class="form-label">Additional Images</label>
                        <input type="file" name="images[]" id="edit_images" class="form-control" multiple>
                        <div id="current_image_preview" class="mt-2"></div>
                        <small class="text-muted">Leave empty to keep existing images.</small>
                    </div>

                    <!-- Variations -->
                    <div class="mb-3">
                        <label class="form-label">Variations</label>
                        <div id="edit_variations_container">
                            <!-- Existing variations will be populated here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" id="edit_add_variation_button">+ Add
                            Variation</button>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Product</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@include('admin.layouts.footer')