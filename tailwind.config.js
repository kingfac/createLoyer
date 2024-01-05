/** @type {import('tailwindcss').Config} */
import preset from './vendor/filament/support/tailwind.config.preset'

module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/views/filament/*.blade.php",
    './resources/views/filament/**/*.blade.php',
    "./resources/views/livewire/*.blade.php",
    "./resources/views/livewire/components/*.blade.php",
    "./resources/**/*.js",
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
