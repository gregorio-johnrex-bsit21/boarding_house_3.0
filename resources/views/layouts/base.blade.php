<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'BoardersHub')</title>
<script>(()=>{try{if(location.pathname.startsWith('/admin')&&localStorage.getItem('bh-admin-theme')==='dark')document.documentElement.classList.add('bh-dark')}catch(e){}})();</script>
{{-- Zero-setup CDNs. For production, compile Tailwind/Alpine with Vite instead (Breeze already ships with it). --}}
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<script src="{{ asset('js/bh-map-bridge.js') }}"></script>
<style>
  [x-cloak]{display:none!important}
  body{background:#f3f5f8}
  .rounded-2xl.bg-white{border:1px solid #e1e6ed;box-shadow:0 3px 10px rgba(15,23,42,.045)}
  html.bh-dark,html.bh-dark body{background:#070d1a;color:#fff;color-scheme:dark}
  html.bh-dark body{background:#070d1a}
  html.bh-dark .bg-white,html.bh-dark .bg-slate-50,html.bh-dark .bg-gray-50{background-color:#0f1830!important}
  html.bh-dark .bg-slate-100,html.bh-dark .bg-slate-200,html.bh-dark .bg-gray-100{background-color:#16223f!important}
  html.bh-dark .text-slate-900,html.bh-dark .text-slate-800,html.bh-dark .text-gray-900,html.bh-dark .text-gray-800{color:#fff!important}
  html.bh-dark .text-slate-700,html.bh-dark .text-slate-600,html.bh-dark .text-slate-500,html.bh-dark .text-slate-400,html.bh-dark .text-gray-700,html.bh-dark .text-gray-600,html.bh-dark .text-gray-500{color:#aab6d0!important}
  html.bh-dark .border-slate-100,html.bh-dark .border-slate-200,html.bh-dark .border-slate-300,html.bh-dark .border-gray-100,html.bh-dark .border-gray-200,html.bh-dark .divide-slate-200>*+*{border-color:#22314f!important}
  html.bh-dark input,html.bh-dark select,html.bh-dark textarea{background-color:#070d1a;color:#fff;border-color:#22314f}
  html.bh-dark input::placeholder,html.bh-dark textarea::placeholder{color:#aab6d0}
  html.bh-dark .hover\:bg-slate-50:hover,html.bh-dark .hover\:bg-slate-100:hover{background-color:#16223f!important}
  html.bh-dark .bg-emerald-50{background-color:#0a2e22!important}
  html.bh-dark .text-emerald-700{color:#6ee7b7!important}
  html.bh-dark .bg-amber-50{background-color:#2e2410!important}
  html.bh-dark .text-amber-700{color:#fcd34d!important}
  html.bh-dark .bg-rose-50{background-color:#3b0f1a!important}
  html.bh-dark .text-rose-700{color:#fca5a5!important}
  html.bh-dark .bg-slate-200\/70{background-color:#16223f!important}
  html.bh-dark .resident-status-tab:not(.font-bold){color:#dbe4f3!important}
  html.bh-dark .resident-status-tab:hover{color:#fcd34d!important;background-color:#2e2410!important}
  html.bh-dark .admin-nav-item.nav-active-indicator{background:transparent!important;color:#5eead4!important;box-shadow:none!important}
  html.bh-dark .admin-nav-item.nav-active-indicator:after{display:block!important;background:#0f766e!important}
  html.bh-dark .admin-nav-item:hover{color:#5eead4!important;background-color:#0a2e2a!important}
  html.bh-dark button.bg-blue-500,html.bh-dark button.bg-blue-600,html.bh-dark button.bg-blue-700{background-color:#fbbf24!important;border-color:#fbbf24!important;color:#1b1303!important}
  html.bh-dark button.bg-blue-500:hover,html.bh-dark button.bg-blue-600:hover,html.bh-dark button.bg-blue-700:hover{background-color:#0f766e!important;border-color:#0f766e!important;color:#fff!important}
  html.bh-dark .rounded-2xl.bg-white{border-color:#22314f;box-shadow:0 3px 10px rgba(0,0,0,.2)}
  html.bh-dark .modal-panel{background-color:#0f1830;color:#fff;border-color:#22314f}
  html.bh-dark .fixed[role=dialog]{background-color:rgba(7,13,26,.62)!important;backdrop-filter:none!important;-webkit-backdrop-filter:none!important}
</style>
</head>
<body class="h-full text-slate-800 antialiased">
@yield('body')
{{-- Toast for flash messages --}}
@if(session('status') || $errors->any())
<div x-data="{o:true}" x-init="setTimeout(()=>o=false,4500)" x-show="o" x-transition x-cloak
     class="fixed left-1/2 top-3 z-[60] w-[92%] max-w-sm -translate-x-1/2 rounded-xl px-4 py-3 text-sm font-medium text-white shadow-lg {{ $errors->any() ? 'bg-rose-600' : 'bg-emerald-600' }}"
     @click="o=false">{{ session('status') ?? $errors->first() }}</div>
@endif
@stack('scripts')
</body>
</html>
