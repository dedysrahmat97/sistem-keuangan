<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-medium text-white hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150']) }}>
    {{ $slot }}
</button>
