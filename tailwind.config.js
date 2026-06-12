/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './assets/js/**/*.js'
  ],
  safelist: [
    'hidden',
    'opacity-0',
    'opacity-100',
    'pointer-events-none',
    'pointer-events-auto',
    'translate-x-full',
    'translate-x-0',
    '-translate-y-full',
    'translate-y-0',
    'translate-y-16',
    'scale-50',
    'scale-100',
    'text-sm',
    'text-base',
    'text-lg',
    'text-xl',
    'text-2xl',
    'text-3xl',
    'md:text-base',
    'md:text-lg',
    'md:text-xl',
    'md:text-2xl',
    'md:text-3xl',
    'leading-relaxed',
    'leading-loose',
    'leading-8',
    'leading-9',
    'leading-10',
    'md:leading-9',
    'md:leading-10',
    'md:leading-[3rem]',
    'lg:col-span-1',
    'lg:col-span-2',
    'col-span-1'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#dc2626',
        'primary-hover': '#b91c1c',
        'background-light': '#f8f9fb',
        'text-main': '#0f172a',
        'text-sub': '#64748b',
        'border-main': '#e2e8f0'
      },
      fontFamily: {
        sans: ['IRANYekanX', 'Vazirmatn', 'system-ui', '-apple-system', 'sans-serif']
      },
      gridTemplateColumns: {
        13: 'repeat(13, minmax(0, 1fr))'
      }
    }
  }
};
