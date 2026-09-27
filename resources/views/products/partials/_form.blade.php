@csrf
@if($mode === 'edit') @method('PUT') @endif
<input type="hidden" name="_method" value="{{ $mode === 'edit' ? 'PUT' : 'POST' }}">

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Left: image --}}
    <div class="card p-5">
        <label class="label">Product Photo</label>
        <div class="rounded-2xl border-2 border-dashed border-gray-200 p-4 text-center">
            <img id="product-image-preview"
                 src="{{ $mode === 'edit' && $product->image ? $product->image_url : '' }}"
                 class="w-full h-40 object-cover rounded-xl mb-3 {{ $mode === 'edit' && $product->image ? '' : 'hidden' }}">
            <label class="btn-secondary cursor-pointer inline-block">
                📷 Choose Photo
                <input type="file" id="product-image-input" name="image" accept="image/png,image/jpeg,image/webp" class="hidden">
            </label>
            <p class="help-text">JPG, PNG or WEBP. Max 2MB. Optional — a placeholder is used otherwise.</p>
            @error('image') <p class="error-text">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Right: fields --}}
    <div class="card p-5 lg:col-span-2 space-y-4">
        <div data-field>
            <label class="label">Product Name *</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                   class="input @error('name') input-error @enderror" data-validate="required|min:2|max:100" placeholder="e.g. Cappuccino">
            <p data-error class="error-text {{ $errors->has('name') ? '' : 'hidden' }}">{{ $errors->first('name') }}</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div data-field>
                <label class="label">Category *</label>
                <div class="flex gap-2">
                    <select name="category_id" id="category_id" class="input @error('category_id') input-error @enderror" data-validate="required">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="open-add-category" class="btn-secondary !px-3" title="Add new category">+</button>
                </div>
                <p data-error class="error-text {{ $errors->has('category_id') ? '' : 'hidden' }}">{{ $errors->first('category_id') }}</p>

                {{-- Inline "add category" panel --}}
                <div id="add-category-panel" class="hidden mt-2 p-3 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                    <input type="text" id="new-category-name" class="input !py-2" placeholder="New category name">
                    <div class="flex items-center gap-2">
                        <input type="color" id="new-category-color" value="#17b167" class="w-9 h-9 rounded-lg border border-gray-200">
                        <button type="button" id="submit-add-category" class="btn-primary !py-2 !px-3 !text-xs">Add</button>
                        <button type="button" id="cancel-add-category" class="btn-secondary !py-2 !px-3 !text-xs">Cancel</button>
                    </div>
                </div>
            </div>

            <div data-field>
                <label class="label">Price *</label>
                <div class="relative">
                    <input type="text" name="price" value="{{ old('price', $product->price ?? '') }}"
                    class="input @error('price') input-error @enderror"
                    data-validate="required|number|positive" data-numeric-only="decimal" placeholder="0.00" inputmode="decimal">
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">{{ \App\Models\Setting::get('currency_symbol', '$') }}</span>
                </div>
                <p data-error class="error-text {{ $errors->has('price') ? '' : 'hidden' }}">{{ $errors->first('price') }}</p>
            </div>
        </div>

        <div data-field>
            <label class="label">SKU / Code <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                   class="input @error('sku') input-error @enderror" data-validate="max:50" placeholder="e.g. CAP-001">
            <p data-error class="error-text {{ $errors->has('sku') ? '' : 'hidden' }}">{{ $errors->first('sku') }}</p>
        </div>

        <div data-field>
            <label class="label">Description <span class="text-gray-400 font-normal">(optional)</span></label>
            <textarea name="description" rows="3" class="input @error('description') input-error @enderror" data-validate="max:500" placeholder="Short description shown to staff">{{ old('description', $product->description ?? '') }}</textarea>
            <p data-error class="error-text {{ $errors->has('description') ? '' : 'hidden' }}">{{ $errors->first('description') }}</p>
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                   @checked(old('is_available', $product->is_available ?? true))>
            <span class="text-sm text-gray-700">Available for sale right now</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" data-label="{{ $mode === 'edit' ? 'Update Product' : 'Save Product' }}" class="btn-primary btn-lg">
                {{ $mode === 'edit' ? 'Update Product' : 'Save Product' }}
            </button>
            <a href="{{ route('products.index') }}" class="btn-secondary btn-lg">Cancel</a>
        </div>
    </div>
</div>
