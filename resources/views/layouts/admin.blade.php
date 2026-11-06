@extends('layouts.base')
@section('body')
<style>@media(max-width:767px){html.bh-dark .admin-top-header{background-color:#070d1a!important;border-color:#22314f!important}}</style>
<header x-data="{ profileOpen: false, darkMode: document.documentElement.classList.contains('bh-dark'), toggleDarkMode() { this.darkMode = !this.darkMode; document.documentElement.classList.toggle('bh-dark', this.darkMode); localStorage.setItem('bh-admin-theme', this.darkMode ? 'dark' : 'light'); const map = document.querySelector('iframe#admin-map'); if (map) { const url = new URL(map.src); url.searchParams.set('theme', this.darkMode ? 'dark' : 'light'); map.src = url.toString(); } } }" x-init="if (darkMode) { const map = document.querySelector('iframe#admin-map'); if (map) { const url = new URL(map.src); url.searchParams.set('theme', 'dark'); map.src = url.toString(); } }" @keydown.escape.window="profileOpen = false" class="admin-top-header fixed inset-x-0 top-0 z-50 border-b border-blue-700 bg-blue-600 text-white md:border-white/10 md:bg-slate-900">
  <div class="absolute inset-0 hidden bg-cover bg-center opacity-50 md:block" style="background-image: url('/images/sally_bh.jpg')"></div>
  <div class="absolute inset-0 hidden bg-slate-950/45 md:block"></div>
  <div class="relative mx-auto flex min-h-[48px] max-w-7xl items-center justify-between px-4 py-2 md:min-h-[90px] md:py-3">
    {{-- Authentic Brand SVG Logo --}}
    <div class="flex items-center gap-2.5">
      <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/20 text-white shadow-sm md:h-9 md:w-9">
        <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
      </div>
      <div>
          <p class="text-sm font-bold leading-none text-white">BoardersHub</p>
        <p class="text-[11px] text-white/80">Landlord dashboard</p>
      </div>
    </div>

    <div class="relative" @click.outside="profileOpen = false">
      <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen" aria-label="Open profile menu" class="flex h-8 w-8 items-center justify-center rounded-full border border-white/60 bg-white/15 text-white transition hover:bg-white/25 md:h-10 md:w-10">
        <svg class="h-5 w-5 md:h-6 md:w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 00-16 0m8-10a4 4 0 100-8 4 4 0 000 8z"/></svg>
      </button>
      <div x-cloak x-show="profileOpen" x-transition class="absolute right-0 top-full z-50 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-2 text-sm text-slate-700 shadow-xl">
        <button type="button" class="w-full rounded-lg px-3 py-2 text-left hover:bg-slate-50">Profile settings</button>
        <button type="button" @click="toggleDarkMode()" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left hover:bg-slate-50"><span x-text="darkMode ? 'Light mode' : 'Dark mode'"></span><span class="text-xs text-slate-500" x-text="darkMode ? 'On' : 'Off'"></span></button>
        <button type="button" class="w-full rounded-lg px-3 py-2 text-left hover:bg-slate-50">Security</button>
        <div class="my-1 border-t border-slate-100"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="w-full rounded-lg px-3 py-2 text-left font-semibold text-rose-600 hover:bg-rose-50">Log out</button>
        </form>
      </div>
    </div>
  </div>
</header>

<main class="mx-auto max-w-7xl px-4 pb-4 pt-[48px] md:pt-[90px]">
  @yield('content')
</main>
@endsection
