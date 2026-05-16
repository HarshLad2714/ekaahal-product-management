@php
    $product = $product ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-5 lg:col-span-2">
        <div>
            <label for="title" class="admin-label">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $product?->title) }}"
                   @class([
                       'admin-input',
                       'input-error' => $errors->has('title'),
                   ])>
            @include('partials.field-error', ['field' => 'title'])
        </div>

        <div>
            <label for="description" class="admin-label">Description</label>
            <div @class(['rounded-lg', 'ring-2 ring-red-500 ring-offset-1' => $errors->has('description')])>
                <input id="description" type="hidden" name="description" value="{{ old('description', $product?->description) }}">
                <trix-editor input="description" class="trix-content"></trix-editor>
            </div>
            @include('partials.field-error', ['field' => 'description'])
        </div>
    </div>

    <div>
        <label for="price" class="admin-label">Price (USD)</label>
        <input type="text" inputmode="decimal" id="price" name="price"
               value="{{ old('price', $product?->price) }}"
               @class([
                   'admin-input',
                   'input-error' => $errors->has('price'),
               ])>
        @include('partials.field-error', ['field' => 'price'])
    </div>

    <div>
        <label for="date_available" class="admin-label">Date available</label>
        <input type="date" id="date_available" name="date_available"
               value="{{ old('date_available', $product?->date_available?->format('Y-m-d')) }}"
               @class([
                   'admin-input',
                   'input-error' => $errors->has('date_available'),
               ])>
        @include('partials.field-error', ['field' => 'date_available'])
    </div>
</div>
