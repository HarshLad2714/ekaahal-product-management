@props(['field', 'theme' => 'light'])

@error($field)
    <p @class([
        'mt-1.5 text-sm font-medium',
        'text-red-600' => $theme === 'light',
        'text-red-400' => $theme === 'dark',
    ]) role="alert">{{ $message }}</p>
@enderror
