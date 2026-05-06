@props([
    'error' => null,
    'baseClass' => 'input-compact',
    'errorClass' => 'border-red-500',
])

<input {{ $attributes->class([
    $baseClass,
    $errorClass => filled($error) && isset($errors) && $errors->has($error),
]) }}>
