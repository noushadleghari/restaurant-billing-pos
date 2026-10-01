<?php echo csrf_field(); ?>
<?php if($mode === 'edit'): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
<input type="hidden" name="_method" value="<?php echo e($mode === 'edit' ? 'PUT' : 'POST'); ?>">

<div class="grid lg:grid-cols-3 gap-6">
    
    <div class="card p-5">
        <label class="label">Product Photo</label>
        <div class="rounded-2xl border-2 border-dashed border-gray-200 p-4 text-center">
            <img id="product-image-preview"
                 src="<?php echo e($mode === 'edit' && $product->image ? $product->image_url : ''); ?>"
                 class="w-full h-40 object-cover rounded-xl mb-3 <?php echo e($mode === 'edit' && $product->image ? '' : 'hidden'); ?>">
            <label class="btn-secondary cursor-pointer inline-block">
                📷 Choose Photo
                <input type="file" id="product-image-input" name="image" accept="image/png,image/jpeg,image/webp" class="hidden">
            </label>
            <p class="help-text">JPG, PNG or WEBP. Max 2MB. Optional — a placeholder is used otherwise.</p>
            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="error-text"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    
    <div class="card p-5 lg:col-span-2 space-y-4">
        <div data-field>
            <label class="label">Product Name *</label>
            <input type="text" name="name" value="<?php echo e(old('name', $product->name ?? '')); ?>"
                   class="input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required|min:2|max:100" placeholder="e.g. Cappuccino">
            <p data-error class="error-text <?php echo e($errors->has('name') ? '' : 'hidden'); ?>"><?php echo e($errors->first('name')); ?></p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div data-field>
                <label class="label">Category *</label>
                <div class="flex gap-2">
                    <select name="category_id" id="category_id" class="input <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required">
                        <option value="">Select category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php if(old('category_id', $product->category_id ?? '') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" id="open-add-category" class="btn-secondary !px-3" title="Add new category">+</button>
                </div>
                <p data-error class="error-text <?php echo e($errors->has('category_id') ? '' : 'hidden'); ?>"><?php echo e($errors->first('category_id')); ?></p>

                
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
                    <input type="text" name="price" value="<?php echo e(old('price', $product->price ?? '')); ?>"
                    class="input <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    data-validate="required|number|positive" data-numeric-only="decimal" placeholder="0.00" inputmode="decimal">
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"><?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?></span>
                </div>
                <p data-error class="error-text <?php echo e($errors->has('price') ? '' : 'hidden'); ?>"><?php echo e($errors->first('price')); ?></p>
            </div>
        </div>

        <div data-field>
            <label class="label">SKU / Code <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="text" name="sku" value="<?php echo e(old('sku', $product->sku ?? '')); ?>"
                   class="input <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="max:50" placeholder="e.g. CAP-001">
            <p data-error class="error-text <?php echo e($errors->has('sku') ? '' : 'hidden'); ?>"><?php echo e($errors->first('sku')); ?></p>
        </div>

        <div data-field>
            <label class="label">Description <span class="text-gray-400 font-normal">(optional)</span></label>
            <textarea name="description" rows="3" class="input <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="max:500" placeholder="Short description shown to staff"><?php echo e(old('description', $product->description ?? '')); ?></textarea>
            <p data-error class="error-text <?php echo e($errors->has('description') ? '' : 'hidden'); ?>"><?php echo e($errors->first('description')); ?></p>
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                   <?php if(old('is_available', $product->is_available ?? true)): echo 'checked'; endif; ?>>
            <span class="text-sm text-gray-700">Available for sale right now</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" data-label="<?php echo e($mode === 'edit' ? 'Update Product' : 'Save Product'); ?>" class="btn-primary btn-lg">
                <?php echo e($mode === 'edit' ? 'Update Product' : 'Save Product'); ?>

            </button>
            <a href="<?php echo e(route('products.index')); ?>" class="btn-secondary btn-lg">Cancel</a>
        </div>
    </div>
</div>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/products/partials/_form.blade.php ENDPATH**/ ?>