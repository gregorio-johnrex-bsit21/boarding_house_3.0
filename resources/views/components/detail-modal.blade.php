@props(['title' => 'Details', 'open' => 'detailOpen', 'mobileFullscreen' => false])

<div x-show="{{ $open }}" x-cloak @keydown.escape.window="{{ $open }}=false" @click.self="{{ $open }}=false" @click.stop
     class="fixed inset-0 z-[70] flex overflow-y-auto backdrop-blur-sm {{ $mobileFullscreen ? 'items-stretch justify-stretch bg-white p-0 sm:items-center sm:justify-center sm:bg-slate-900/50 sm:p-4' : 'items-center justify-center bg-slate-900/50 p-4' }}" role="dialog" aria-modal="true" aria-label="{{ $title }}">
  <div class="{{ $mobileFullscreen ? 'my-0 min-h-full w-full max-w-none rounded-none bg-white p-5 shadow-none sm:my-auto sm:min-h-0 sm:max-w-lg sm:rounded-2xl sm:p-6 sm:shadow-2xl' : 'my-auto w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl sm:p-6' }}" @click.stop>
    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
      <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Details</p>
        <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $title }}</h3>
      </div>
      <button type="button" @click="{{ $open }}=false" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="Close details">✕</button>
    </div>
    <div class="mt-4">{{ $slot }}</div>
  </div>
</div>
