@extends('layouts.admin')
@section('title','Dashboard · BoardersHub')
@section('content')
@php
  $cards = [
    [
      'label' => 'Total beds', 
      'value' => $stats['total'], 
      'bg' => 'from-emerald-500 via-teal-500 to-cyan-500', 
      'svg' => 'M3 10h18M3 14h18M5 6v12M19 6v12M5 10v4m14-4v4'
    ],
    [
      'label' => 'Occupied', 
      'value' => $stats['occupied'], 
      'bg' => 'from-rose-500 via-rose-400 to-pink-400', 
      'svg' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
    ],
    [
      'label' => 'Available', 
      'value' => $stats['available'], 
      'bg' => 'from-emerald-400 via-green-500 to-teal-600', 
      'svg' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
    ],
    [
      'label' => 'Pending payments', 
      'value' => $stats['pendingPayments'], 
      'bg' => 'from-amber-400 via-orange-400 to-amber-500', 
      'svg' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'
    ],
    [
      'label' => 'Open repairs', 
      'value' => $stats['openIssues'], 
      'bg' => 'from-blue-600 via-blue-500 to-sky-500', 
      'svg' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'
    ]
  ];

  $monthlyCollected = $paymentResidents->sum(fn($resident) => (float) ($resident['billing']['paid_amount'] ?? 0));
  $monthlyOutstanding = $paymentResidents->sum(fn($resident) => (float) ($resident['billing']['remaining'] ?? 0));
  $occupancyRate = $stats['total'] ? round(($stats['occupied'] / $stats['total']) * 100) : 0;

  $tabs = [
    'dashboard' => ['Dashboard', 0, 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1'],
    'tenants' => ['Tenants', 0, 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
    'sales' => ['Insights', 0, 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m7-4a7 7 0 11-14 0 7 7 0 0114 0z'],
    'reports' => ['Reports', 0, 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z'],
    'payments' => ['Payments', $stats['pendingPayments'], 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
    'map' => ['Map', 0, 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.818V8.042a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
    'maintenance' => ['Repairs', $stats['openIssues'], 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'],
    'settings' => ['Settings', 0, 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z']
  ];

  $salesTotal = $monthlyCollected + $monthlyOutstanding;
  $collectionRate = $salesTotal > 0 ? round(($monthlyCollected / $salesTotal) * 100) : 0;
  $paymentStatusCounts = $paymentResidents->countBy(fn($resident) => $resident['billing']['status']);
  $paidCount = $paymentStatusCounts->get('paid', 0);
  $pendingCount = $paymentStatusCounts->get('pending', 0);
  $dueCount = $paymentStatusCounts->get('due', 0) + $paymentStatusCounts->get('partial', 0);
  $residentCount = max(1, $paymentResidents->count());
  $desktopTabs = array_diff_key($tabs, array_flip(['sales', 'reports']));
  $mobileTabs = array_intersect_key($tabs, array_flip(['dashboard', 'payments', 'map', 'maintenance', 'settings']));

  $stColor = ['available'=>'#30a46c', 'occupied'=>'#e5484d', 'reserved'=>'#3e8ef7', 'ready'=>'#e5a800'];
@endphp

<div x-data="adminApp()" class="pb-16 md:pb-0">
  <style>
    .nav-active-indicator { position: relative; }
    .nav-active-indicator::after { content: ''; position: absolute; left: 0; right: 0; bottom: -8px; height: 4px; border-radius: 9999px; background: #2563eb; }
    @media (min-width: 768px) and (prefers-reduced-motion: no-preference) {
    @keyframes desktopPageEnter { from { opacity: 0; } to { opacity: 1; } }
      @keyframes desktopBarRise { from { transform: scaleY(0); } to { transform: scaleY(1); } }
      @keyframes desktopLineDraw { to { stroke-dashoffset: 0; } }
    @keyframes desktopModalEnter { from { opacity: 0; transform: translateY(8px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .desktop-page-transition:not([style*="display: none"]) { animation: desktopPageEnter 240ms ease-out; }
      .desktop-chart-bar { transform-origin: bottom; animation: desktopBarRise 650ms cubic-bezier(.2,.7,.2,1) both; }
      .desktop-payment-chart rect { transform-box: fill-box; transform-origin: bottom; animation: desktopBarRise 650ms cubic-bezier(.2,.7,.2,1) both; }
      .desktop-chart-line { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: desktopLineDraw 900ms ease-out 100ms forwards; }
    .fixed[role="dialog"]:not([style*="display: none"]) > div { animation: desktopModalEnter 200ms ease-out both; }
    .fixed[role="dialog"] { z-index: 100 !important; backdrop-filter: none; -webkit-backdrop-filter: none; }
    }
    @media (max-width: 767px) and (prefers-reduced-motion: no-preference) {
      @keyframes mobilePageEnter { from { opacity: 0; } to { opacity: 1; } }
      @keyframes mobileModalEnter { from { opacity: 0; transform: translateY(10px) scale(.985); } to { opacity: 1; transform: translateY(0) scale(1); } }
      @keyframes mobileBarRise { from { transform: scaleY(0); } to { transform: scaleY(1); } }
      @keyframes mobileLineDraw { to { stroke-dashoffset: 0; } }
      .mobile-page-transition:not([style*="display: none"]) { animation: mobilePageEnter 220ms ease-out; }
      .mobile-page-transition:has(.fixed[role="dialog"]:not([style*="display: none"])) { position: relative; z-index: 110; }
      body:has(.fixed[role="dialog"]:not([style*="display: none"])) header { z-index: 0 !important; }
      .desktop-chart-bar { transform-origin: bottom; animation: mobileBarRise 520ms cubic-bezier(.2,.7,.2,1); }
      .desktop-payment-chart rect { transform-box: fill-box; transform-origin: bottom; animation: mobileBarRise 520ms cubic-bezier(.2,.7,.2,1); }
      .desktop-chart-line { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: mobileLineDraw 750ms ease-out 80ms forwards; }
      .fixed[role="dialog"]:not([style*="display: none"]) > div { animation: mobileModalEnter 200ms ease-out; }
    }
  </style>

  {{-- Top Navigation Bar (Desktop) --}}
  <div class="sticky top-[90px] z-40 -mx-4 mb-2 hidden justify-center border-b border-slate-200 bg-white px-4 py-2 shadow-md md:flex">
    <div class="flex w-full max-w-5xl justify-around gap-1">
      @foreach($desktopTabs as $k=>[$l,$n,$svg])
        <button @click="go('{{ $k }}')" :class="tab==='{{ $k }}' ? 'nav-active-indicator text-blue-700' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700'" class="admin-nav-item flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $svg }}"/></svg>
          {{ $l }} 
          @if($n)<span class="rounded-full bg-rose-500 px-1.5 py-0.2 text-[10px] text-white font-bold">{{ $n }}</span>@endif
        </button>
      @endforeach
    </div>
  </div>

  <nav x-show="['dashboard', 'tenants', 'sales'].includes(tab)" x-cloak class="sticky top-[48px] z-40 -mx-4 mb-2 grid grid-cols-3 gap-0 border-b border-slate-200 bg-white px-4 py-2 shadow-md md:hidden">
    @foreach($tabs as $k => [$label, $count, $svg])
      @if(in_array($k, ['dashboard', 'tenants', 'sales']))
        <button @click="go('{{ $k }}')" :class="tab==='{{ $k }}' ? 'nav-active-indicator text-blue-700' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700'" class="admin-nav-item w-full rounded-lg px-4 py-2 text-center text-sm font-semibold">{{ $label }}</button>
      @endif
    @endforeach
  </nav>

  <section x-show="tab==='dashboard'" x-cloak class="desktop-page-transition mobile-page-transition space-y-4">
    <div class="flex justify-end">
      <button type="button" @click="reportOpen=true" class="inline-flex rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Generate report</button>
    </div>
    {{-- Quick stats cards --}}
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div class="min-h-[280px] rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:min-h-[420px] md:p-6">
        <h3 class="text-sm font-semibold text-slate-900">Bed Occupancy Overview</h3>
        <div class="mt-4 flex h-40 items-end justify-around gap-4 border-b border-slate-200 md:h-48">
          @foreach([['Total', $stats['total'], 'bg-slate-700'], ['Available', $stats['available'], 'bg-emerald-500'], ['Occupied', $stats['occupied'], 'bg-blue-500']] as [$label, $value, $color])
            <div class="flex h-full flex-1 flex-col items-center justify-end">
              <span class="mb-1 text-xs font-semibold text-slate-700">{{ $value }}</span>
              <div class="flex h-28 w-10 items-end rounded-t-lg bg-slate-100"><div class="desktop-chart-bar w-full rounded-t-lg {{ $color }}" style="height: {{ $stats['total'] ? ($value / $stats['total']) * 100 : 0 }}%"></div></div>
              <span class="py-2 text-xs text-slate-500">{{ $label }}</span>
            </div>
          @endforeach
        </div>
        <div class="mt-4 grid grid-cols-3 divide-x divide-slate-200 border-t border-slate-200 pt-4">
          <div class="pr-2"><p class="text-xs font-medium text-slate-500">Total</p><p class="mt-1 text-xl font-bold text-slate-900">{{ $stats['total'] }}</p><p class="mt-1 text-[10px] text-slate-500">As of {{ now()->format('M j, Y') }}</p></div>
          <div class="px-2"><p class="text-xs font-medium text-slate-500">Available</p><p class="mt-1 text-xl font-bold text-slate-900">{{ $stats['available'] }}</p><p class="mt-1 text-[10px] text-slate-500">As of {{ now()->format('M j, Y') }}</p></div>
          <div class="pl-2"><p class="text-xs font-medium text-slate-500">Occupied</p><p class="mt-1 text-xl font-bold text-slate-900">{{ $stats['occupied'] }}</p><p class="mt-1 text-[10px] text-slate-500">As of {{ now()->format('M j, Y') }}</p></div>
        </div>
      </div>
      <div class="min-h-[280px] rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:min-h-[420px] md:p-6">
        <div><h3 class="text-sm font-semibold text-slate-900">Payments Overview</h3><p class="mt-1 text-xs text-slate-500">Tenant payment status</p></div>
        <div class="mt-6 grid grid-cols-3 divide-x divide-slate-200 border-t border-slate-200 pt-5">
          <div class="min-w-0 pr-2 sm:pr-4"><p class="text-xs font-medium text-slate-500">Pending</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ $pendingCount }}</p><p class="mt-2 text-[10px] text-slate-500">As of {{ now()->format('M j, Y') }}</p></div>
          <div class="min-w-0 px-2 sm:px-4"><p class="text-xs font-medium text-slate-500">Paid</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ $paidCount }}</p><p class="mt-2 text-[10px] text-slate-500">As of {{ now()->format('M j, Y') }}</p></div>
          <div class="min-w-0 pl-2 sm:pl-4"><p class="text-xs font-medium text-slate-500">Due</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ $dueCount }}</p><p class="mt-2 text-[10px] text-slate-500">As of {{ now()->format('M j, Y') }}</p></div>
        </div>
        <div class="mt-5">
          <div class="flex flex-wrap items-center justify-between gap-2"><p class="text-xs font-semibold text-slate-700">Monthly payment activity</p><p class="text-[10px] text-slate-400">Sample trend</p></div>
          <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-[10px] text-slate-500"><span><i class="mr-1 inline-block h-2 w-2 rounded-sm bg-blue-600"></i>Paid</span><span><i class="mr-1 inline-block h-2 w-2 rounded-sm bg-cyan-500"></i>Pending</span><span><i class="mr-1 inline-block h-2 w-2 rounded-sm bg-amber-400"></i>Due</span><span><i class="mr-1 inline-block h-0.5 w-3 align-middle bg-rose-500"></i>Collection rate</span></div>
          <svg viewBox="0 0 360 145" class="desktop-payment-chart mt-2 h-32 w-full" role="img" aria-label="Sample monthly payment activity from January through June">
            <path d="M20 112H340" stroke="#e2e8f0"/>
            <g fill="#2563eb"><rect x="27" y="74" width="9" height="38" rx="2"/><rect x="81" y="62" width="9" height="50" rx="2"/><rect x="135" y="50" width="9" height="62" rx="2"/><rect x="189" y="54" width="9" height="58" rx="2"/><rect x="243" y="38" width="9" height="74" rx="2"/><rect x="297" y="22" width="9" height="90" rx="2"/></g>
            <g fill="#06b6d4"><rect x="38" y="84" width="9" height="28" rx="2"/><rect x="92" y="92" width="9" height="20" rx="2"/><rect x="146" y="88" width="9" height="24" rx="2"/><rect x="200" y="95" width="9" height="17" rx="2"/><rect x="254" y="97" width="9" height="15" rx="2"/><rect x="308" y="102" width="9" height="10" rx="2"/></g>
            <g fill="#fbbf24"><rect x="49" y="77" width="9" height="35" rx="2"/><rect x="103" y="82" width="9" height="30" rx="2"/><rect x="157" y="85" width="9" height="27" rx="2"/><rect x="211" y="91" width="9" height="21" rx="2"/><rect x="265" y="93" width="9" height="19" rx="2"/><rect x="319" y="98" width="9" height="14" rx="2"/></g>
            <polyline class="desktop-chart-line" points="42,70 96,60 150,68 204,50 258,42 312,30" fill="none" stroke="#f43f5e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            <g fill="#f43f5e"><circle cx="42" cy="70" r="3"/><circle cx="96" cy="60" r="3"/><circle cx="150" cy="68" r="3"/><circle cx="204" cy="50" r="3"/><circle cx="258" cy="42" r="3"/><circle cx="312" cy="30" r="3"/></g>
            <g fill="#94a3b8" font-size="9" text-anchor="middle"><text x="42" y="132">Jan</text><text x="96" y="132">Feb</text><text x="150" y="132">Mar</text><text x="204" y="132">Apr</text><text x="258" y="132">May</text><text x="312" y="132">Jun</text></g>
          </svg>
        </div>
      </div>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hidden"><div class="flex items-center justify-between"><div><h3 class="font-semibold text-slate-900">Overview</h3><p class="text-xs text-slate-500">{{ $occupancyRate }}% occupied</p></div><span class="text-xs text-slate-500">Current beds</span></div><div class="mt-4 flex h-36 items-end justify-around gap-4 border-b border-slate-100 px-4"><div class="flex h-full w-16 flex-col items-center justify-end"><span class="mb-1 text-xs font-semibold">{{ $stats['occupied'] }}</span><div class="w-10 rounded-t-lg bg-blue-500" style="height: {{ $stats['total'] ? max(8, ($stats['occupied'] / $stats['total']) * 100) : 8 }}%"></div><span class="py-2 text-xs text-slate-500">Occupied</span></div><div class="flex h-full w-16 flex-col items-center justify-end"><span class="mb-1 text-xs font-semibold">{{ $stats['available'] }}</span><div class="w-10 rounded-t-lg bg-emerald-500" style="height: {{ $stats['total'] ? max(8, ($stats['available'] / $stats['total']) * 100) : 8 }}%"></div><span class="py-2 text-xs text-slate-500">Available</span></div><div class="flex h-full w-16 flex-col items-center justify-end"><span class="mb-1 text-xs font-semibold">{{ $stats['openIssues'] }}</span><div class="w-10 rounded-t-lg bg-amber-500" style="height: {{ $stats['total'] ? max(8, ($stats['openIssues'] / $stats['total']) * 100) : 8 }}%"></div><span class="py-2 text-xs text-slate-500">Repairs</span></div></div></div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <div x-data="{
        range: 'month',
        data: {
          week: { labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], boys: [13,14,14,15,16,17,18], girls: [9,10,10,11,11,12,12], delta: {boys:5,girls:-3,total:2} },
          month: { labels: ['W1','W2','W3','W4','Now'], boys: [13,14,15,16,18], girls: [9,10,10,11,12], delta: {boys:4,girls:1,total:5} },
          year: { labels: ['Jan','Mar','May','Jul','Sep','Now'], boys: [8,10,12,14,16,18], girls: [7,8,9,10,11,12], delta: {boys:12,girls:8,total:20} }
        },
        linePoints(key) {
          const values = key === 'total' ? this.data[this.range].boys.map((v,i) => v + this.data[this.range].girls[i]) : this.data[this.range][key];
          const max = 40;
          return values.map((v,i) => `${30 + i * (540 / Math.max(1, values.length - 1))},${190 - (v / max) * 160}`).join(' ');
        }
      }" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3"><div><h3 class="font-semibold text-slate-900">Tenant overview</h3><p class="mt-1 text-xs text-slate-500">Sample analytics</p></div><select x-model="range" class="rounded-lg border-slate-200 py-1.5 text-xs"><option value="week">This week</option><option value="month">This month</option><option value="year">This year</option></select></div>
        <div class="mt-4 grid grid-cols-3 gap-2">
          <div><p class="text-[11px] text-slate-500">Boys</p><strong class="text-lg text-slate-900" x-text="data[range].boys[data[range].boys.length-1]"></strong><span class="ml-1 rounded px-1.5 py-0.5 text-[10px] font-semibold" :class="data[range].delta.boys >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'" x-text="(data[range].delta.boys > 0 ? '+' : '') + data[range].delta.boys"></span></div>
          <div><p class="text-[11px] text-slate-500">Girls</p><strong class="text-lg text-slate-900" x-text="data[range].girls[data[range].girls.length-1]"></strong><span class="ml-1 rounded px-1.5 py-0.5 text-[10px] font-semibold" :class="data[range].delta.girls >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'" x-text="(data[range].delta.girls > 0 ? '+' : '') + data[range].delta.girls"></span></div>
          <div><p class="text-[11px] text-slate-500">Total tenants</p><strong class="text-lg text-slate-900" x-text="data[range].boys[data[range].boys.length-1] + data[range].girls[data[range].girls.length-1]"></strong><span class="ml-1 rounded px-1.5 py-0.5 text-[10px] font-semibold" :class="data[range].delta.total >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'" x-text="(data[range].delta.total > 0 ? '+' : '') + data[range].delta.total"></span></div>
        </div>
        <svg viewBox="0 0 600 220" class="mt-4 h-40 w-full" role="img" aria-label="Sample tenant trend line chart"><path d="M30 190H570" stroke="#e2e8f0"/><polyline class="desktop-chart-line" :points="linePoints('boys')" fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><polyline class="desktop-chart-line" :points="linePoints('girls')" fill="none" stroke="#ec4899" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><polyline class="desktop-chart-line" :points="linePoints('total')" fill="none" stroke="#0ea5e9" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <div class="flex justify-between px-2 text-[10px] text-slate-400"><template x-for="label in data[range].labels" :key="label"><span x-text="label"></span></template></div>
        <div class="mt-3 flex flex-wrap gap-4 text-[11px] text-slate-600"><span><i class="mr-1 inline-block h-2 w-2 rounded-full bg-blue-500"></i>Boys</span><span><i class="mr-1 inline-block h-2 w-2 rounded-full bg-pink-500"></i>Girls</span><span><i class="mr-1 inline-block h-2 w-2 rounded-full bg-sky-500"></i>Total</span></div>
      </div>
      <div class="hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:block">
        <div class="flex items-start justify-between"><div><h3 class="font-semibold text-slate-900">Sales overview</h3><p class="mt-1 text-xs text-slate-500">Current billing cycle</p></div><span class="text-xs text-slate-500">Total billed · ₱{{ number_format($salesTotal, 2) }}</span></div>
        <div class="mt-5 flex flex-wrap items-center justify-center gap-8">
          <div class="relative h-36 w-36 shrink-0"><svg viewBox="0 0 42 42" class="h-full w-full -rotate-90" role="img" aria-label="Collection breakdown"><circle cx="21" cy="21" r="15.9155" fill="none" stroke="#e2e8f0" stroke-width="6"/><circle cx="21" cy="21" r="15.9155" fill="none" stroke="#10b981" stroke-width="6" stroke-dasharray="{{ $collectionRate }} 100" stroke-linecap="round"/></svg><div class="absolute inset-0 flex flex-col items-center justify-center"><span class="text-2xl font-bold text-slate-900">{{ $collectionRate }}%</span><span class="text-[10px] text-slate-500">collected</span></div></div>
          <div class="min-w-48 space-y-4 text-sm"><div class="flex justify-between gap-6"><span class="flex items-center gap-2 text-slate-600"><i class="h-3 w-3 rounded-full bg-emerald-500"></i>Collected</span><strong>₱{{ number_format($monthlyCollected, 2) }}</strong></div><div class="flex justify-between gap-6"><span class="flex items-center gap-2 text-slate-600"><i class="h-3 w-3 rounded-full bg-slate-300"></i>Outstanding</span><strong>₱{{ number_format($monthlyOutstanding, 2) }}</strong></div></div>
        </div>
      </div>
    </div>
  </section>

  <div x-show="reportOpen" x-cloak @keydown.escape.window="reportOpen=false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-900/50 p-4">
    <div @click.outside="reportOpen=false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
      <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-wide text-blue-600">BoardersHub</p><h2 class="mt-1 text-xl font-bold text-slate-900">Boarding House Report</h2><p class="mt-1 text-sm text-slate-500">Summary as of {{ now()->format('M j, Y') }}</p></div><button type="button" @click="reportOpen=false" class="text-2xl leading-none text-slate-500" aria-label="Close report">&times;</button></div>
      <table id="generated-report-content" class="mt-5 w-full border-collapse text-left text-sm"><tbody>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Total beds</th><td class="border-b border-slate-200 py-2 font-semibold">{{ $stats['total'] }}</td></tr>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Occupied</th><td class="border-b border-slate-200 py-2 font-semibold">{{ $stats['occupied'] }}</td></tr>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Available</th><td class="border-b border-slate-200 py-2 font-semibold">{{ $stats['available'] }}</td></tr>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Open repairs</th><td class="border-b border-slate-200 py-2 font-semibold">{{ $stats['openIssues'] }}</td></tr>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Paid / pending / due accounts</th><td class="border-b border-slate-200 py-2 font-semibold">{{ $paidCount }} / {{ $pendingCount }} / {{ $dueCount }}</td></tr>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Total billed</th><td class="border-b border-slate-200 py-2 font-semibold">₱{{ number_format($salesTotal, 2) }}</td></tr>
        <tr><th class="border-b border-slate-200 py-2 text-slate-600">Collected</th><td class="border-b border-slate-200 py-2 font-semibold">₱{{ number_format($monthlyCollected, 2) }}</td></tr>
        <tr><th class="py-2 text-slate-600">Outstanding</th><td class="py-2 font-semibold">₱{{ number_format($monthlyOutstanding, 2) }}</td></tr>
      </tbody></table>
      <div class="mt-6 flex justify-end gap-2"><button type="button" @click="exportReport()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Export CSV</button><button type="button" @click="printReport()" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Print report</button></div>
    </div>
  </div>

  <section x-show="tab==='sales'" x-cloak class="mobile-page-transition space-y-4 md:hidden">
    <div><h2 class="text-xl font-bold text-slate-900">Insights</h2><p class="text-sm text-slate-500">Collection performance for the current billing cycle.</p></div>
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4"><div><h3 class="font-semibold text-slate-900">Billing collection</h3><p class="mt-1 text-xs text-slate-500">Collected compared with the amount still due.</p></div><div class="text-right"><p class="text-xs font-medium text-slate-500">Total billed</p><p class="mt-1 text-lg font-bold text-slate-900">₱{{ number_format($salesTotal, 2) }}</p></div></div>
      <div class="mt-5 grid items-center gap-5 sm:grid-cols-[auto_1fr] sm:justify-center">
        <div class="relative mx-auto h-36 w-36">
          <svg viewBox="0 0 42 42" class="h-full w-full -rotate-90" role="img" aria-label="Collection breakdown donut chart"><circle cx="21" cy="21" r="15.9155" fill="none" stroke="#ffe4e6" stroke-width="6"/><circle cx="21" cy="21" r="15.9155" fill="none" stroke="#10b981" stroke-width="6" stroke-dasharray="{{ $collectionRate }} 100" stroke-linecap="round"/></svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center"><span class="text-2xl font-bold text-slate-900">{{ $collectionRate }}%</span><span class="text-[10px] font-medium text-slate-500">collected</span></div>
        </div>
        <div class="space-y-4 text-sm sm:min-w-64">
          <div class="flex items-center justify-between gap-6"><span class="flex items-center gap-2 text-slate-600"><i class="h-3 w-3 rounded-full bg-emerald-500"></i>Collected</span><strong class="text-slate-900">₱{{ number_format($monthlyCollected, 2) }}</strong></div>
          <div class="flex items-center justify-between gap-6"><span class="flex items-center gap-2 text-slate-600"><i class="h-3 w-3 rounded-full bg-rose-300"></i>Outstanding</span><strong class="text-slate-900">₱{{ number_format($monthlyOutstanding, 2) }}</strong></div>
        </div>
      </div>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="flex items-center justify-between gap-3"><div><h3 class="font-semibold text-slate-900">Reports</h3><p class="mt-1 text-xs text-slate-500">Current boarding house summary.</p></div><button type="button" onclick="{const rows=[['Metric','Value'],['Total beds','{{ $stats['total'] }}'],['Occupied','{{ $stats['occupied'] }}'],['Available','{{ $stats['available'] }}'],['Collected','{{ number_format($monthlyCollected, 2, '.', '') }}'],['Outstanding','{{ number_format($monthlyOutstanding, 2, '.', '') }}']];const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([rows.map(r=>r.join(',')).join('\\n')],{type:'text/csv'}));a.download='boardershub-report.csv';a.click();URL.revokeObjectURL(a.href)}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Export CSV</button></div>
      <div class="mt-4 grid grid-cols-2 gap-3"><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Total beds</p><strong>{{ $stats['total'] }}</strong></div><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Occupied</p><strong>{{ $stats['occupied'] }}</strong></div><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Available</p><strong>{{ $stats['available'] }}</strong></div><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Collected</p><strong>₱{{ number_format($monthlyCollected, 2) }}</strong></div><div class="col-span-2 rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Outstanding</p><strong>₱{{ number_format($monthlyOutstanding, 2) }}</strong></div></div>
    </div>
    <button @click="go('payments')" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Open payments</button>
  </section>

  <section x-show="tab==='reports'" x-cloak class="space-y-4 md:hidden"><div><h2 class="text-xl font-bold text-slate-900">Reports</h2><p class="text-sm text-slate-500">Current boarding house summary.</p></div><div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="flex justify-between border-b border-slate-100 p-4"><span class="text-slate-500">Total beds</span><strong>{{ $stats['total'] }}</strong></div><div class="flex justify-between border-b border-slate-100 p-4"><span class="text-slate-500">Occupied</span><strong>{{ $stats['occupied'] }}</strong></div><div class="flex justify-between border-b border-slate-100 p-4"><span class="text-slate-500">Available</span><strong>{{ $stats['available'] }}</strong></div><div class="flex justify-between border-b border-slate-100 p-4"><span class="text-slate-500">Collected this cycle</span><strong>₱{{ number_format($monthlyCollected, 2) }}</strong></div><div class="flex justify-between p-4"><span class="text-slate-500">Outstanding</span><strong>₱{{ number_format($monthlyOutstanding, 2) }}</strong></div></div><button type="button" onclick="{const rows=[['Metric','Value'],['Total beds','{{ $stats['total'] }}'],['Occupied','{{ $stats['occupied'] }}'],['Available','{{ $stats['available'] }}'],['Collected','{{ number_format($monthlyCollected, 2, '.', '') }}'],['Outstanding','{{ number_format($monthlyOutstanding, 2, '.', '') }}']];const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([rows.map(r=>r.join(',')).join('\n')],{type:'text/csv'}));a.download='boardershub-report.csv';a.click();URL.revokeObjectURL(a.href)}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Export report (CSV)</button></section>

  {{-- ============ MAP (Full-width edge-to-edge canvas) ============ --}}
  <section x-show="tab==='map'" x-cloak class="desktop-page-transition mobile-page-transition -mx-4 space-y-3 px-4 sm:mx-0">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div><h2 class="text-lg font-bold text-slate-900">Room map</h2><p class="text-sm text-slate-500">Check room and bed availability at a glance.</p></div>
      <div class="inline-flex rounded-xl border border-slate-200 bg-slate-100 p-1">
        <button type="button" @click="setMapMode('simple')" :class="mapMode==='simple' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white'" class="rounded-lg px-3 py-2 text-xs font-semibold transition">Simple</button>
        <button type="button" @click="setMapMode('interactive')" :class="mapMode==='interactive' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white'" class="rounded-lg px-3 py-2 text-xs font-semibold transition">Interactive 3D / 2D</button>
      </div>
    </div>
    <div x-show="mapMode==='simple'" class="grid gap-4 lg:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.85fr)]">
      <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-5">
        
        <div class="mb-3 flex flex-wrap gap-x-4 gap-y-2 text-xs text-slate-600">
          <span class="inline-flex items-center gap-1.5"><i class="h-3 w-3 rounded-sm bg-slate-500"></i>Room</span><span class="inline-flex items-center gap-1.5"><i class="h-3 w-3 rounded-sm border-2 border-teal-400 bg-white"></i>Selected room</span><span class="text-slate-500">Bed colors show availability in the room panel.</span>
        </div>
        <svg viewBox="0 0 780 500" class="block h-auto w-full rounded-xl bg-slate-50" role="group" aria-label="Room availability map" @click="const addEl=$event.target.closest('[data-room-add]'); if(addEl) addSimpleRoom(addEl.dataset.roomAdd); const roomEl=$event.target.closest('[data-room]'); if(roomEl) openSimpleRoom(roomEl.dataset.room)" @keydown.enter="const roomEl=$event.target.closest('[data-room]'); if(roomEl) openSimpleRoom(roomEl.dataset.room)">
          <rect x="14" y="10" width="752" height="480" rx="14" fill="none" stroke="currentColor" class="text-slate-300" stroke-width="2" stroke-dasharray="8 6"/>
          <text x="390" y="270" text-anchor="middle" class="fill-slate-400" font-size="16">Common area</text>
          <g x-html="simpleRoomsSvg()"></g><g x-html="simpleRoomAddSvg()"></g>
        </svg>
        <p class="mt-3 text-xs text-slate-500">Select a room to see its bunk status. Select a bunk to manage it.</p>
      </div>
      <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex items-start justify-between gap-3">
          <div><p class="text-sm font-bold uppercase tracking-wide text-blue-600">Selected room</p><h3 class="mt-1 text-2xl font-bold text-slate-900" x-text="'Room ' + simpleSelectedRoom"></h3></div>
          <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700" x-text="simpleRoomSummary(simpleSelectedRoom)"></span></div>        <div class="mt-4 flex flex-wrap gap-2"><button type="button" @click="startRoomEdit()" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit room name</button><button type="button" @click="startTenantAssignment()" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Assign tenant</button><button type="button" @click="removeSimpleRoom()" class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Remove room</button></div><div class="mt-6 grid grid-cols-3 gap-3" x-show="simpleBunks(simpleSelectedRoom).length">
          <template x-for="bunk in simpleBunks(simpleSelectedRoom)" :key="bunk.number">
            <div class="text-center">
              <div class="relative mx-auto flex h-20 w-full max-w-[92px] flex-col justify-center gap-2 rounded-xl bg-slate-50 px-2">
                <template x-for="(bed, bedIndex) in [bunk.top, bunk.bottom]" :key="bed ? bed.mesh_id : ('empty-' + bedIndex)">
                  <div class="relative h-4 w-full"><button type="button" @click="bed && open([bed.mesh_id])" :disabled="!bed" :title="bed ? bed.label + ' · ' + bed.status : 'No bed assigned'" :style="'background:' + (bed ? (BH.colors[bed.status] || '#94a3b8') : '#e2e8f0')" class="h-4 w-full rounded-md border-2 border-white/70 shadow-sm transition hover:brightness-110 disabled:cursor-default" :aria-label="bed ? 'Manage ' + bed.label : 'Unassigned bunk position'"></button></div>
                </template>
              </div>
              <p class="mt-2 text-xs font-medium text-slate-600" x-text="'Bunk ' + bunk.number"></p>
            </div>
          </template>
        </div>
        <div x-show="!simpleBunks(simpleSelectedRoom).length" class="mt-6 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No bed assignments are available for this room.</div>
        <div class="mt-5 border-t border-slate-200 pt-4">
          <p class="mb-3 text-sm font-semibold text-slate-800">Bed status</p>
          <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-slate-600"><span class="inline-flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-emerald-500"></i>Available</span><span class="inline-flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-rose-500"></i>Occupied</span><span class="inline-flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-blue-500"></i>Reserved</span><span class="inline-flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-amber-400"></i>Ready</span></div>
        </div>
      </aside>
    </div>
    <div x-show="mapMode==='interactive'" x-cloak class="h-[calc(100vh-11rem)] min-h-[420px] w-full overflow-hidden rounded-2xl bg-slate-100">
      <iframe x-ref="map" id="admin-map" src="about:blank" class="block h-full w-full border-0" title="3D map" allow="fullscreen" allowfullscreen></iframe>
    </div>
  </section>

  {{-- ============ TENANTS ============ --}}
  <section x-show="tab==='tenants'" x-cloak x-data="{ tenantView: 'cards' }" class="desktop-page-transition mobile-page-transition space-y-4">
    {{-- Filter, Search & Add Tenant Action Bar --}}
    <div x-data="{ mobileSearchOpen: false }" class="relative flex w-full items-center justify-between gap-2">
      
      {{-- Mobile Search Overlay --}}
      <div 
        x-show="mobileSearchOpen" 
        x-cloak 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="absolute inset-0 z-10 flex items-center gap-2 bg-slate-50 sm:hidden"
      >
        <form method="GET" action="{{ route('admin.dashboard') }}#tenants" class="relative flex-1">
          @if($roomFilter)<input type="hidden" name="room" value="{{ $roomFilter }}">@endif
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <input 
            type="search" 
            name="q" 
            value="{{ $q }}" 
            placeholder="Search name, room, email…" 
            class="w-full rounded-xl border-slate-300 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500"
            x-ref="mobileSearchInput"
          >
        </form>
        <button 
          type="button" 
          @click="mobileSearchOpen = false" 
          class="rounded-xl bg-slate-200 p-2 text-slate-600 hover:bg-slate-300 transition"
          title="Close search"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Room Filter Dropdown --}}
      <form method="GET" action="{{ route('admin.dashboard') }}#tenants" class="min-w-0">
        <input type="hidden" name="q" value="{{ $q }}">
        <label for="tenant-room-filter" class="sr-only">Filter by room</label>
        <select id="tenant-room-filter" name="room" onchange="this.form.submit()" class="w-32 max-w-full rounded-xl border-slate-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 sm:w-40">
          <option value="">All rooms</option>
          @foreach(range(1, 17) as $roomNumber)
            <option value="{{ $roomNumber }}" @selected((string) $roomFilter === (string) $roomNumber)>Room {{ $roomNumber }}</option>
          @endforeach
        </select>
      </form>

      {{-- Right Side Actions: Inline Search + Add Tenant Button --}}
      <div class="flex min-w-0 flex-1 items-center gap-2 sm:flex-none">
        <button 
          type="button" 
          @click="mobileSearchOpen = true; $nextTick(() => $refs.mobileSearchInput.focus())" 
          class="flex h-9 min-w-0 flex-1 items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 text-left text-slate-500 shadow-sm hover:bg-slate-50 sm:hidden transition"
          title="Search tenants"
        >
          <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <span class="truncate text-sm">Search tenants...</span>
        </button>

        <form method="GET" action="{{ route('admin.dashboard') }}#tenants" class="relative hidden sm:block w-48 md:w-64">
          @if($roomFilter)<input type="hidden" name="room" value="{{ $roomFilter }}">@endif
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <input 
            type="search" 
            name="q" 
            value="{{ $q }}" 
            placeholder="Search name, room..." 
            class="w-full rounded-xl border-slate-300 bg-white pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
          >
        </form>

        <button @click="addTenantModal = true" class="shrink-0 flex items-center justify-center gap-1.5 h-9 w-auto px-3 sm:h-auto sm:px-3.5 sm:py-2 rounded-xl bg-blue-600 text-white shadow-sm hover:bg-blue-500 transition" title="Add Tenant">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          <span class="text-xs font-semibold sm:hidden">Add</span>
          <span class="hidden sm:inline sm:ml-1.5 text-xs font-semibold">Add tenant</span>
        </button>
      </div>
    </div>

    <div class="flex items-center justify-between gap-3">
      <p class="text-xs font-medium text-slate-500">{{ $tenants->count() }} {{ \Illuminate\Support\Str::plural('resident', $tenants->count()) }}{{ $roomFilter ? ' in Room '.$roomFilter : '' }}</p>
      <div class="inline-flex rounded-xl bg-slate-100 p-1 sm:hidden" aria-label="Tenant list view">
        <button type="button" @click="tenantView='cards'" :class="tenantView==='cards' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500'" class="rounded-lg px-3 py-1.5 text-xs font-medium">Cards</button>
        <button type="button" @click="tenantView='list'" :class="tenantView==='list' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500'" class="rounded-lg px-3 py-1.5 text-xs font-medium">List</button>
      </div>
    </div>

    {{-- Occupied tenant roster --}}
    <div :class="tenantView==='cards' ? 'overflow-x-auto overflow-y-hidden whitespace-nowrap scrollbar-hide' : 'flex flex-col overflow-visible whitespace-normal'" class="w-full py-1 sm:overflow-visible sm:whitespace-normal sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4">
      @forelse($tenants as $x) @php $u=$x['user']; @endphp
        <div x-data="{detailOpen:false,editTenantOpen:false,deleteTenantOpen:false,tenantActive:true,tenantRecord:@js(['name'=>$u->name,'email'=>$u->email,'phone'=>$u->phone ?: '']),editTenantDraft:@js(['name'=>$u->name,'email'=>$u->email,'phone'=>$u->phone ?: ''])}" :class="tenantView==='cards' ? 'inline-block w-64 shrink-0 mr-3 p-5 text-center' : 'flex w-full items-center justify-between p-3 text-left'" class="mb-2 align-top rounded-2xl bg-white shadow-sm hover:shadow-md transition border border-slate-100 whitespace-normal sm:w-auto sm:mr-0 sm:mb-0 sm:flex sm:flex-col sm:justify-between sm:items-center sm:p-5 sm:text-center">
          <div x-show="tenantView==='cards' || window.matchMedia('(min-width: 640px)').matches" class="flex flex-col items-center w-full min-w-0">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full border-2 border-slate-100 bg-slate-50 text-slate-400 mb-3 shadow-inner mx-auto">
              <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0A17.9 17.9 0 0112 22.5a17.9 17.9 0 01-7.5-2.4z"/></svg>
            </div>
            
            <h3 class="w-full truncate font-bold text-slate-900 text-base" :title="tenantRecord.name" x-text="tenantRecord.name"></h3>
            <span x-show="!tenantActive" x-cloak class="mt-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-500">Inactive · history retained</span>
            <span class="inline-block px-2.5 py-0.5 mt-1 rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600 truncate max-w-full">
              {{ $u->bed?->full_name ?? 'No bed assigned' }}
            </span>

            <div class="mt-3 w-full space-y-1 text-xs text-slate-500">
              <p class="truncate"><span class="font-medium text-slate-400">Email:</span> <span x-text="tenantRecord.email"></span></p>
              <p class="truncate"><span class="font-medium text-slate-400">Phone:</span> <span x-text="tenantRecord.phone || 'N/A'"></span></p>
            </div>
          </div>

          <div x-show="tenantView==='cards' || window.matchMedia('(min-width: 640px)').matches" class="mt-5 w-full pt-3 border-t border-slate-100">
            <button type="button" @click="detailOpen=true" class="w-full rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm">
              View profile
            </button>
          </div>

          <div x-show="tenantView==='list'" class="flex min-w-0 flex-1 items-center gap-3 sm:hidden">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
            <div class="min-w-0 flex-1">
              <p class="truncate text-sm font-semibold text-slate-900" x-text="tenantRecord.name"></p>
              <p class="mt-0.5 truncate text-xs text-slate-500">{{ $u->bed?->full_name ?? 'No bed assigned' }}</p>
            </div>
            <button type="button" @click="detailOpen=true" aria-label="View {{ $u->name }} profile" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-blue-600">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.25 9.75-6.25S21.75 12 21.75 12 18.25 18.25 12 18.25 2.25 12 2.25 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>
            </button>
          </div>

          <x-detail-modal title="Resident profile" mobile-fullscreen>
            <div class="mb-6 flex flex-col items-center gap-3 text-center">
              <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-400" aria-label="No profile photo">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0A17.9 17.9 0 0112 22.5a17.9 17.9 0 01-7.5-2.4z"/></svg>
              </div>
              <div class="min-w-0"><h4 class="break-words text-lg font-bold text-slate-900" x-text="tenantRecord.name"></h4><p class="break-all text-sm text-slate-500" x-text="tenantRecord.email"></p></div>
            </div>
            <dl class="space-y-3 text-left">
              <div class="grid grid-cols-3 divide-x divide-slate-200 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="pr-2"><dt class="text-xs font-medium text-slate-500">Room</dt><dd class="mt-1 font-semibold text-slate-900">{{ $u->bed?->room?->number ?? '—' }}</dd></div>
                <div class="px-2"><dt class="text-xs font-medium text-slate-500">Bed</dt><dd class="mt-1 font-semibold text-slate-900">{{ $u->bed?->bed_no ? 'Bed '.$u->bed->bed_no : '—' }}</dd></div>
                <div class="pl-2"><dt class="text-xs font-medium text-slate-500">Bunk</dt><dd class="mt-1 font-semibold text-slate-900">{{ $u->bed?->bunk === 'A' ? 'Top' : ($u->bed?->bunk === 'B' ? 'Bottom' : '—') }}</dd></div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3"><dt class="text-xs font-medium text-slate-500">Phone</dt><dd class="mt-1 break-words font-semibold text-slate-900">{{ $u->phone ?: '—' }}</dd></div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3"><dt class="text-xs font-medium text-slate-500">Move-in date</dt><dd class="mt-1 font-semibold text-slate-900">{{ $u->move_in_date?->format('M j, Y') ?? '—' }}</dd></div>
              </div>
            </dl>
            <div class="mt-5 grid grid-cols-2 gap-3">
              <button type="button" @click="editTenantDraft={...tenantRecord}; editTenantOpen=true" class="rounded-xl border border-blue-200 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-50">Edit</button>
              <button type="button" @click="deleteTenantOpen=true" :disabled="!tenantActive" class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-50">Deactivate</button>
            </div>
          </x-detail-modal>

          <x-detail-modal title="Edit tenant" open="editTenantOpen" mobile-fullscreen>
            <p class="mb-4 text-sm text-slate-500">Local preview only. Updates apply to this page until it is refreshed.</p>
            <form @submit.prevent="tenantRecord={...editTenantDraft}; editTenantOpen=false" class="space-y-3">
              <label class="block text-sm font-medium text-slate-700">Name<input x-model.trim="editTenantDraft.name" required class="mt-1 w-full rounded-xl border-slate-300 text-sm"></label>
              <label class="block text-sm font-medium text-slate-700">Email<input type="email" x-model.trim="editTenantDraft.email" required class="mt-1 w-full rounded-xl border-slate-300 text-sm"></label>
              <label class="block text-sm font-medium text-slate-700">Phone<input x-model.trim="editTenantDraft.phone" class="mt-1 w-full rounded-xl border-slate-300 text-sm"></label>
              <div class="flex gap-2 pt-2">
                <button type="button" @click="editTenantOpen=false" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700">Cancel</button>
                <button class="flex-1 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Save changes</button>
              </div>
            </form>
          </x-detail-modal>

          <x-detail-modal title="Deactivate tenant?" open="deleteTenantOpen">
            <p class="text-sm text-slate-600">This local preview marks the tenant inactive and keeps the record visible. Backend login access and history are not changed.</p>
            <div class="mt-5 flex justify-end gap-2">
              <button type="button" @click="deleteTenantOpen=false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">Cancel</button>
              <button type="button" @click="tenantActive=false; deleteTenantOpen=false; detailOpen=false" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white">Deactivate tenant</button>
            </div>
          </x-detail-modal>
        </div>
      @empty 
        <div class="col-span-full p-8 text-center text-sm text-slate-500 bg-white rounded-2xl">No tenants match.</div> 
      @endforelse
    </div>
</section>

  {{-- ============ PAYMENTS ============ --}}
  <section x-show="tab==='payments'" x-cloak class="desktop-page-transition mobile-page-transition space-y-6 pt-4 md:pt-0"
         x-data="{ 
           activeView: 'variation1', 
           modalOpen: false, 
           activeMethod: 'cash', 
           paymentAmount: '', 
           selectedTx: null, 
           detailModalOpen: false 
         }">

  <!-- ================================================================= -->
  <!-- GLOBAL TOP CONTROL BAR (VIEW SWITCHER + SEARCH & FILTER)          -->
  <!-- ================================================================= -->
  <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-2">
    
    <div class="flex w-full flex-col gap-3 self-start sm:w-auto sm:flex-row sm:items-center">
      <div class="inline-flex w-full rounded-xl bg-slate-200/70 p-1 border border-slate-200/80 sm:w-auto">
        <button type="button" @click="activeView = 'variation1'" :class="activeView === 'variation1' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'" class="resident-status-tab flex-1 rounded-lg px-2 py-2 text-xs transition-all duration-150 sm:flex-none sm:px-4">Resident Status List</button>
        <button type="button" @click="activeView = 'variation2'" :class="activeView === 'variation2' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'" class="resident-status-tab flex-1 rounded-lg px-2 py-2 text-xs transition-all duration-150 sm:flex-none sm:px-4">Payment Terminal</button>
      </div>
      <button type="button" @click="applianceSetupOpen=true" class="inline-flex items-center justify-center gap-2 w-full rounded-xl border border-blue-200 bg-white px-4 py-2 text-xs font-semibold text-blue-700 sm:w-auto">
  <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M7 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 0a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM7 13c-3.31 0-6 2.69-6 6a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1c0-3.31-2.69-6-6-6Zm10 0c-1.38 0-2.63.47-3.64 1.25a1 1 0 0 0-.25 1.38 1 1 0 0 0 1.38.25C15.28 15.3 16.1 15 17 15c2.21 0 4 1.79 4 4a1 1 0 0 0 2 0c0-3.31-2.69-6-6-6Z"/>
  </svg>
  <span>Appliance charges</span>
</button>
    </div>
    <div x-data="{ search: '{{ request('search') }}', status: '{{ $paymentFilter }}' }" 
         class="flex items-center gap-2.5 w-full sm:w-auto">
      
      <div class="relative flex-1 sm:w-56 shrink-0 sm:shrink">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <input type="text" x-model="search" placeholder="Search..." class="w-full rounded-xl border-slate-200 bg-white pl-9 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
      </div>

      <div class="w-28 sm:w-36 shrink-0">
        <select x-model="status" @change="window.location.href = '{{ route('admin.dashboard') }}?payment_status=' + status + '#payments'" class="w-full rounded-xl border-slate-200 bg-white py-2 px-2.5 text-xs text-slate-700 font-medium focus:border-blue-500 focus:ring-blue-500 shadow-sm">
          <option value="all">All</option>
          <option value="due">Due</option>
          <option value="pending">Pending</option>
          <option value="paid">Paid</option>
        </select>
      </div>

    </div>

  </div>


  <!-- ================================================================= -->
  <!-- VARIATION 1: RESIDENT BILLING STATUS CARDS                       -->
  <!-- ================================================================= -->
  <style>
    @media (max-width: 767px) {
      .mobile-payment-modal > [role="dialog"], .mobile-terminal-modal > [role="dialog"], .mobile-appliance-modal > [role="dialog"] { inset: 0 !important; padding: 0 !important; align-items: stretch !important; background: #f1f5f9 !important; backdrop-filter: none !important; }
      .mobile-payment-modal > [role="dialog"] > div, .mobile-terminal-modal > [role="dialog"] > div, .mobile-appliance-modal > [role="dialog"] > div { width: 100% !important; max-width: none !important; min-height: 100dvh !important; margin: 0 !important; border-radius: 0 !important; }
    }
  </style>
  <div x-show="activeView === 'variation1'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      @forelse($paymentResidents as $resident)
        @php
          $u = $resident['user']; $b = $resident['billing']; $last = $resident['latest_payment'];
          $badgeStatus = match($b['status']) { 'paid' => 'paid', 'pending' => 'pending', 'partial' => 'partial', default => 'due' };
          $rentAmount = (float) ($b['rent_amount'] ?? $b['amount']);
          $rentRemaining = max(0, $rentAmount - (float) $b['paid_amount']);
          $cycleStart = $b['period']->copy()->startOfMonth();
          $cycleEnd = $cycleStart->copy()->addMonthNoOverflow();

          $badgeStyle = match($badgeStatus) {
            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
            'pending', 'partial' => 'bg-amber-50 text-amber-700 border-amber-200/60',
            default => 'bg-rose-50 text-rose-700 border-rose-200/60',
          };
          
          $roomNumber = $u->room?->room_number ?? $u->bed?->room?->room_number ?? 'N/A';
        @endphp

        <article x-data="{
                   cardPaymentOpen: false, mobileDetailOpen: false, mobilePaymentOpen: false,
                   cardMethod: 'cash', 
                   cardAmount: {{ $rentRemaining }}, 
                   baseRemainingBalance: {{ $rentRemaining }}, cardApplianceShare: 0,
                   extraStayDays: 0,
                   remainingBalance: {{ $rentRemaining }},
                   totalRent: {{ $rentAmount }},
                   paidAmount: {{ (float) $b['paid_amount'] }},
                   step: 'form', 
                   signing: false, 
                   signatureData: '', 
                   ctx: null,

                   initCanvas() {
                     setTimeout(() => {
                       const c = this.$refs.signatureCanvas;
                       if (!c) return;
                       const rect = c.getBoundingClientRect();
                       c.width = rect.width;
                       c.height = rect.height;
                       this.ctx = c.getContext('2d');
                       this.ctx.lineWidth = 2.5;
                       this.ctx.lineCap = 'round';
                       this.ctx.strokeStyle = '#0f172a';
                     }, 100);
                   },

                   getPos(e) {
                     const rect = this.$refs.signatureCanvas.getBoundingClientRect();
                     const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                     const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                     return {
                       x: clientX - rect.left,
                       y: clientY - rect.top
                     };
                   },

                   startDrawing(e) {
                     e.preventDefault();
                     this.signing = true;
                     const pos = this.getPos(e);
                     this.ctx.beginPath();
                     this.ctx.moveTo(pos.x, pos.y);
                   },

                   draw(e) {
                     if (!this.signing) return;
                     e.preventDefault();
                     const pos = this.getPos(e);
                     this.ctx.lineTo(pos.x, pos.y);
                     this.ctx.stroke();
                     this.signatureData = this.$refs.signatureCanvas.toDataURL('image/png');
                   },

                   stopDrawing() {
                     this.signing = false;
                   },

                   clearSignature() {
                     if (this.ctx) {
                       this.ctx.clearRect(0, 0, this.$refs.signatureCanvas.width, this.$refs.signatureCanvas.height);
                     }
                     this.signatureData = '';
                   },

                   submitPayment() {
                     this.step = 'sign';
                     this.initCanvas();
                   },

                   completePayment() {
                     this.step = 'success';
                     setTimeout(() => {
                       this.$refs.paymentForm.submit();
                     }, 1500);
                   }
                 }" 
                 @click="if (window.matchMedia('(max-width: 767px)').matches && !$event.target.closest('button')) mobileDetailOpen=true"
                 class="min-w-0 rounded-2xl bg-white p-3 md:p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">

          <div class="hidden md:block">
          <div class="space-y-4">
            <div class="flex items-center justify-between gap-3">
              <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700 font-bold text-sm border border-slate-200">
                  {{ strtoupper(substr($u->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                  <h3 class="font-semibold text-sm text-slate-900 truncate" title="{{ $u->name }}">{{ $u->name }}</h3>
                  <p class="text-xs text-slate-500 truncate mt-0.5">Room {{ $roomNumber }}</p>
                </div>
              </div>

              <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-semibold capitalize {{ $badgeStyle }}">
                {{ $badgeStatus }}
              </span>
            </div>

            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
              <div class="grid grid-cols-3 divide-x divide-slate-200/80 text-center">
                <div class="px-1">
                  <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Current bill</span>
                  <span class="block mt-1 text-xs font-bold text-slate-700">₱{{ number_format($b['amount'], 2) }}</span>
                </div>
                <div class="px-1">
                  <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Paid</span>
                  <span class="block mt-1 text-xs font-bold text-slate-700">₱{{ number_format($b['paid_amount'], 2) }}</span>
                </div>
                <div class="px-1">
                  <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Remaining</span>
                  <span class="block mt-1 text-xs font-extrabold text-blue-600">₱{{ number_format($b['remaining'], 2) }}</span>
                </div>
              </div>
            </div>

            <div class="space-y-1.5 text-xs text-slate-600">
              <div class="flex justify-between">
                <span class="text-slate-400">Billing Period</span>
                <span class="font-medium text-slate-700">{{ $cycleStart->format('M j') }} – {{ $cycleEnd->format('M j, Y') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Due Date</span>
                <span class="font-medium text-slate-700">{{ $b['due']->format('M j, Y') }}</span>
              </div>
            </div>
          </div>

          @if($b['remaining'] > 0)
            <div class="mt-4 grid grid-cols-2 gap-2 border-t border-slate-100 pt-3">
              <button type="button" @click="cardMethod='cash'; extraStayDays=0; cardApplianceShare=applianceShare({{ $u->id }}); remainingBalance=baseRemainingBalance+cardApplianceShare; cardAmount=remainingBalance; step='form'; cardPaymentOpen=true" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition active:scale-[0.98]">
                Record Cash
              </button>
              <button type="button" @click="cardMethod='e_wallet'; extraStayDays=0; cardApplianceShare=applianceShare({{ $u->id }}); remainingBalance=baseRemainingBalance+cardApplianceShare; cardAmount=remainingBalance; step='form'; cardPaymentOpen=true" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition active:scale-[0.98]">
                E-Wallet / Bank
              </button>
            </div>
          @endif
          </div>

          <div class="flex items-center gap-3 md:hidden">
            @if($u->avatar_url ?? false)
              <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="h-12 w-12 shrink-0 rounded-full object-cover border border-slate-200">
            @else
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700 font-bold border border-slate-200">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
            @endif
            <div class="min-w-0 flex-1">
              <h3 class="truncate font-semibold text-sm text-slate-900">{{ $u->name }}</h3>
              <div class="mt-2 space-y-1.5">
                <div class="flex items-center justify-between gap-2">
                  <p class="text-sm font-bold text-slate-900">₱{{ number_format($b['remaining'], 2) }}</p>
                  <span class="inline-flex shrink-0 rounded-full border px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wide {{ $badgeStyle }}">{{ $badgeStatus }}</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                  <p class="text-[10px] text-slate-500">{{ $b['due']->format('M j, Y') }} · Room {{ $roomNumber }}</p>
                  <button type="button" @click="mobileDetailOpen=true" class="flex shrink-0 items-center justify-center gap-1 rounded-full border border-slate-300 bg-white px-3 py-1 text-[11px] font-medium text-slate-700 whitespace-nowrap active:bg-slate-50">View Details <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15 15 5M6 5h9v9"/></svg></button>
                </div>
              </div>
            </div>
          </div>

          <div x-show="mobileDetailOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" @click.stop class="fixed inset-0 z-[80] overflow-y-auto bg-slate-50 md:hidden">
            <div class="sticky top-0 z-10 flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-3">
              <button type="button" @click="mobileDetailOpen=false" class="text-2xl leading-none text-slate-700" aria-label="Back">←</button>
              <h2 class="font-semibold text-slate-900">Resident payment</h2>
            </div>
            <div class="space-y-4 p-4">
              <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex items-center gap-3">
                  @if($u->avatar_url ?? false)
                    <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="h-14 w-14 rounded-full object-cover border border-slate-200">
                  @else
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-700 border border-slate-200">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                  @endif
                  <div><h3 class="font-bold text-slate-900">{{ $u->name }}</h3><p class="text-sm text-slate-500">Room {{ $roomNumber }}</p></div>
                  <span class="ml-auto rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $badgeStyle }}">{{ $badgeStatus }}</span>
                </div>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <h3 class="mb-3 text-sm font-semibold text-slate-900">Payment details</h3>
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between"><span class="text-slate-500">Current bill</span><strong>₱{{ number_format($b['amount'], 2) }}</strong></div>
                  <div class="flex justify-between"><span class="text-slate-500">Paid</span><strong>₱{{ number_format($b['paid_amount'], 2) }}</strong></div>
                  <div class="flex justify-between border-t border-slate-100 pt-3"><span class="text-slate-700 font-semibold">Remaining</span><strong class="text-blue-600">₱{{ number_format($b['remaining'], 2) }}</strong></div>
                  <div class="flex justify-between"><span class="text-slate-500">Billing period</span><span>{{ $cycleStart->format('M j') }} – {{ $cycleEnd->format('M j, Y') }}</span></div>
                  <div class="flex justify-between"><span class="text-slate-500">Due date</span><span>{{ $b['due']->format('M j, Y') }}</span></div>
                </div>
              </div>
              @if($b['remaining'] > 0)
                <div class="grid grid-cols-2 gap-3">
                  <button type="button" @click="mobileDetailOpen=false; cardMethod='cash'; extraStayDays=0; cardApplianceShare=applianceShare({{ $u->id }}); remainingBalance=baseRemainingBalance+cardApplianceShare; cardAmount=remainingBalance; step='form'; mobilePaymentOpen=true; cardPaymentOpen=true" class="rounded-xl bg-emerald-600 px-3 py-3 text-sm font-semibold text-white">Record Cash</button>
                  <button type="button" @click="mobileDetailOpen=false; cardMethod='e_wallet'; extraStayDays=0; cardApplianceShare=applianceShare({{ $u->id }}); remainingBalance=baseRemainingBalance+cardApplianceShare; cardAmount=remainingBalance; step='form'; mobilePaymentOpen=true; cardPaymentOpen=true" class="rounded-xl bg-blue-600 px-3 py-3 text-sm font-semibold text-white">E-Wallet</button>
                </div>
              @endif
            </div>
          </div>

          <!-- RECORD PAYMENT MODAL -->
          <div class="mobile-payment-modal">
          <x-detail-modal title="Record Payment" open="cardPaymentOpen">
            <div class="space-y-4 text-slate-800">
              <button type="button" @click="cardPaymentOpen=false; mobilePaymentOpen=false; mobileDetailOpen=true" class="mb-2 text-sm font-semibold text-slate-600 md:hidden">← Back to resident</button>

              <!-- STEP 1: FORM WITH LIVE BALANCES -->
              <div x-show="step === 'form'">
                <form x-ref="paymentForm" method="POST" action="{{ route('admin.tenants.cash', $u) }}" enctype="multipart/form-data" @submit.prevent="submitPayment()" class="space-y-4">
                  @csrf
                  <input type="hidden" name="payment_method" :value="cardMethod">

                  <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-200/80 space-y-2 text-xs">
                    <p class="font-bold text-slate-900 border-b border-slate-200/60 pb-1.5 flex justify-between">
                      <span>{{ $u->name }}</span>
                      <span class="text-slate-500 font-normal">Room {{ $roomNumber }}</span>
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-slate-600">
                      <div>Total bill: <strong class="text-slate-800" x-text="'₱' + (totalRent + cardApplianceShare + extraStayDays * prorateDailyRate).toFixed(2)"></strong></div>
                      <div>Monthly appliance share: <strong class="text-slate-800" x-text="'₱' + cardApplianceShare.toFixed(2)"></strong></div>
                      <template x-for="item in applianceBreakdown({{ $u->id }})" :key="item.name">
                        <div class="col-span-2 flex items-center justify-between text-slate-600"><span><span x-text="item.name"></span> <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700" x-text="item.type === 'personal' ? 'Personal' : 'Split'"></span></span><strong class="text-slate-800" x-text="'₱' + item.share.toFixed(2)"></strong></div>
                      </template>
                      <div>Already Paid: <strong class="text-slate-800">₱{{ number_format($b['paid_amount'], 2) }}</strong></div>
                      <div class="col-span-2 flex justify-between pt-1 border-t border-slate-200/60 font-semibold text-slate-900">
                        <span>Current Due / Balance:</span>
                        <span class="text-rose-600" x-text="'₱' + (remainingBalance + extraStayDays * prorateDailyRate).toFixed(2)"></span>
                      </div>
                    </div>
                  </div>

                  <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-left">
                    <p class="text-xs font-semibold text-slate-800">Extra stay</p>
                    <label class="mt-2 block text-xs text-slate-600">Additional days
                      <input type="number" min="0" x-model.number="extraStayDays" @input="cardAmount=remainingBalance + extraStayDays * prorateDailyRate" class="mt-1 w-full rounded-xl border-slate-300 text-xs">
                    </label>
                    <p class="mt-2 text-xs text-slate-700"><span x-text="extraStayDays + ' days × ₱' + Number(prorateDailyRate).toFixed(2)"></span> = <strong x-text="'₱' + (extraStayDays * prorateDailyRate).toFixed(2)"></strong></p>
                  </div>

                  <div>
                    <label class="block text-xs font-semibold text-slate-700">Amount Received</label>
                    <div class="mt-1 flex items-center rounded-xl border border-slate-300 px-3 py-2 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 bg-white shadow-sm">
                      <span class="text-xs font-bold text-slate-400">₱</span>
                      <input name="amount" type="number" min="0.01" :max="remainingBalance + extraStayDays * prorateDailyRate" step="0.01" x-model.number="cardAmount" required class="w-full border-0 px-1 text-xs font-bold text-slate-900 focus:ring-0">
                    </div>
                  </div>

                  <div class="rounded-xl bg-blue-50/60 border border-blue-100 p-3 text-xs flex items-center justify-between">
                    <span class="font-medium text-blue-900">New Remaining Balance:</span>
                    <span class="font-extrabold text-blue-700" x-text="'₱' + Math.max(0, remainingBalance + extraStayDays * prorateDailyRate - (cardAmount || 0)).toFixed(2)"></span>
                  </div>

                  <div x-show="cardMethod==='e_wallet'" class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700">Proof of Payment / Camera Shot</label>
                    <input type="file" name="payment_proof" accept="image/*" capture="environment" :required="cardMethod==='e_wallet'" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl">
                  </div>

                  <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-blue-700 transition">
                    Save Payment
                  </button>
                </form>
              </div>

              <!-- STEP 2: SIGNATURE PROMPT -->
              <div x-show="step === 'sign'">
                <div class="space-y-4">
                  <div class="text-center">
                    <h4 class="text-sm font-bold text-slate-900">Confirm Payment by Signing</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Please sign below to authorize this payment of <strong class="text-blue-600" x-text="'₱' + Number(cardAmount).toFixed(2)"></strong>.</p>
                  </div>

                  <div class="space-y-2">
                    <canvas x-ref="signatureCanvas" 
                            @mousedown="startDrawing($event)" 
                            @mousemove="draw($event)" 
                            @mouseup="stopDrawing()" 
                            @mouseleave="stopDrawing()" 
                            @touchstart="startDrawing($event)" 
                            @touchmove="draw($event)" 
                            @touchend="stopDrawing()" 
                            class="h-36 w-full rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 touch-none cursor-crosshair"></canvas>
                    
                    <input type="hidden" name="tenant_signature" :value="signatureData">
                    
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-slate-500" x-text="signatureData ? 'Signature captured' : 'Draw signature above'"></span>
                      <button type="button" @click="clearSignature()" class="font-semibold text-blue-700 underline">Clear signature</button>
                    </div>
                  </div>

                  <div class="grid grid-cols-2 gap-2 pt-2">
                    <button type="button" @click="step='form'" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Back</button>
                    <button type="button" :disabled="!signatureData" @click="completePayment()" class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 disabled:opacity-50 transition">Confirm & Save</button>
                  </div>
                </div>
              </div>

              <!-- STEP 3: SUCCESS ANIMATION -->
              <div x-show="step === 'success'">
                <div class="py-8 flex flex-col items-center justify-center text-center space-y-3">
                  <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 animate-bounce">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="text-base font-bold text-slate-900">Payment Confirmed!</h4>
                    <p class="text-xs text-slate-500 mt-1">Updating balances and saving transaction...</p>
                  </div>
                </div>
              </div>

            </div>
          </x-detail-modal>
          </div>

        </article>
      @empty
        <p class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-xs text-slate-500 md:col-span-2 xl:col-span-3">No residents found matching the search criteria.</p>
      @endforelse
    </div>
  </div>


  <!-- ================================================================= -->
  <!-- VARIATION 2: PAYMENT TERMINAL & TRANSACTIONS                      -->
  <!-- ================================================================= -->
  <div x-show="activeView === 'variation2'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

    <div class="rounded-2xl bg-white border border-slate-200/80 p-6 shadow-sm space-y-5">
      <div>
        <h3 class="text-base font-bold text-slate-900">Direct Payment Terminal</h3>
        <p class="text-xs text-slate-500 mt-0.5">Select a payment method to begin fast direct checkout.</p>
      </div>

      <div class="grid grid-cols-2 gap-3 pt-1">
        <button type="button" @click="activeMethod='cash'; modalOpen=true" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 bg-slate-50/50 hover:bg-slate-100/80 hover:border-blue-500 transition group active:scale-[0.98]">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 mb-2 group-hover:bg-emerald-600 group-hover:text-white transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          </div>
          <span class="text-xs font-bold text-slate-800">Cash Payment</span>
        </button>

        <button type="button" @click="activeMethod='e_wallet'; modalOpen=true" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 bg-slate-50/50 hover:bg-slate-100/80 hover:border-blue-500 transition group active:scale-[0.98]">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-700 mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
          </div>
          <span class="text-xs font-bold text-slate-800">E-Wallet / Bank</span>
        </button>
      </div>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 shadow-sm space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900">Recent Transactions</h3>
        <span class="text-xs text-slate-400">Click for breakdown</span>
      </div>

      <div class="divide-y divide-slate-100">
        @forelse($paymentResidents as $resident)
          @php $last = $resident['latest_payment']; @endphp
          @if($last)
            <div @click="selectedTx = {{ json_encode($last) }}; detailModalOpen = true" class="flex items-center justify-between py-3 cursor-pointer hover:bg-slate-50 px-2 rounded-xl transition">
              <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                  {{ strtoupper(substr($resident['user']->name, 0, 1)) }}
                </div>
                <div>
                  <p class="text-xs font-bold text-slate-800">{{ $resident['user']->name }}</p>
                  <p class="text-[11px] text-slate-400">{{ $last->created_at?->format('M j, Y - h:i A') ?? 'Recent' }}</p>
                </div>
              </div>

              <div class="text-right">
                <p class="text-xs font-bold text-slate-900">₱{{ number_format($last->amount ?? 0, 2) }}</p>
                <span class="text-[10px] font-semibold uppercase text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                  {{ str_replace('_', ' ', $last->payment_method) }}
                </span>
              </div>
            </div>
          @endif
        @empty
          <p class="text-center text-xs text-slate-400 py-4">No recent transactions.</p>
        @endforelse
      </div>
    </div>

  </div>

  <template x-teleport="body">
  <div class="mobile-appliance-modal">
  <x-detail-modal title="Monthly appliance charges" open="applianceSetupOpen">
    <div class="space-y-4 text-sm">
      <p class="text-slate-600">Set up each appliance once. Choose shared or personal billing; each selected tenant must confirm. Charges repeat monthly.</p>
      <div class="space-y-3 rounded-xl border border-slate-200 p-3">
        <label class="block text-xs font-semibold text-slate-700">Billing type
          <select x-model="newApplianceMode" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            <option value="split">Shared — split among tenants</option>
            <option value="personal">Personal — full fee for one tenant</option>
          </select>
        </label>
        <label x-show="newApplianceMode === 'split'" class="block text-xs font-semibold text-slate-700">Tenant agreement
          <select x-model="newConsentMode" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            <option value="manual">Manual — tenants agreed in person</option>
            <option value="request">Send request to tenants</option>
          </select>
        </label>
        <label class="block text-xs font-semibold text-slate-700">Appliance / surcharge
          <select x-model="newApplianceIndex" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
            <option value="">Choose appliance</option>
            <template x-for="(device, index) in applianceOptions" :key="index"><option :value="index" x-text="device.name + ' · ₱' + Number(device.fee).toFixed(2) + '/month'"></option></template>
          </select>
        </label>
        <p class="text-xs text-slate-500">Add appliance types and monthly fees in Settings → Device surcharges.</p>
        <div class="max-h-48 space-y-2 overflow-y-auto">
          <template x-for="resident in applianceResidents" :key="resident.id">
            <div class="rounded-lg bg-slate-50 p-2">
              <label class="flex items-center gap-2 text-xs text-slate-800"><input type="checkbox" :value="resident.id" x-model="newShareTenantIds" :disabled="newApplianceMode === 'personal' && newShareTenantIds.length > 0 && !newShareTenantIds.some(id => id == resident.id)"><span x-text="resident.name + ' · Room ' + resident.room"></span></label>
              <label x-show="newApplianceMode === 'split' && newConsentMode === 'manual' && newShareTenantIds.some(id => id == resident.id)" class="ml-6 mt-2 flex items-center gap-2 text-xs font-medium text-blue-700"><input type="checkbox" :value="resident.id" x-model="confirmedShareTenantIds"> Tenant agreed in person</label>
            </div>
          </template>
        </div>
        <label x-show="newApplianceMode === 'split' && newConsentMode === 'request' && newShareTenantIds.length > 1" class="block text-xs font-semibold text-slate-700">Request started by
          <select x-model="newRequestFromTenantId" class="mt-1 w-full rounded-xl border-slate-300 text-sm"><option value="">Choose requesting tenant</option><template x-for="resident in applianceResidents.filter(r => newShareTenantIds.some(id => id == r.id))" :key="resident.id"><option :value="resident.id" x-text="resident.name"></option></template></select>
        </label>
        <button type="button" @click="saveApplianceAgreement()" :disabled="!newApplianceIndex || !newShareTenantIds.length || (newApplianceMode === 'personal' && newShareTenantIds.length !== 1) || (newApplianceMode === 'split' && newShareTenantIds.length < 2) || (newApplianceMode === 'split' && newConsentMode === 'manual' && !newShareTenantIds.every(id => confirmedShareTenantIds.some(ok => ok == id))) || (newApplianceMode === 'split' && newConsentMode === 'request' && (!newRequestFromTenantId || !newShareTenantIds.some(id => id == newRequestFromTenantId)))" class="w-full rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white disabled:opacity-40" x-text="newApplianceMode === 'personal' ? 'Save personal appliance' : (newConsentMode === 'request' ? 'Send tenant requests' : 'Save monthly split')"></button>
      </div>
      <div class="space-y-2">
        <template x-for="(agreement, index) in applianceAgreements" :key="index">
          <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-3 text-xs"><span><strong x-text="agreement.name"></strong><span class="block text-slate-500" x-text="agreement.type === 'personal' ? '₱' + Number(agreement.fee).toFixed(2) + ' charged to one tenant each month' : '₱' + Number(agreement.fee).toFixed(2) + ' split among ' + agreement.tenantIds.length + ' tenants each month'"></span></span><button type="button" @click="removeApplianceAgreement(index)" class="text-rose-600">Remove</button></div>
        </template>
        <p x-show="!applianceAgreements.length" class="text-xs text-slate-500">No appliance charges configured.</p>
      </div>
    </div>
  </x-detail-modal>
  </div>
  </template>
  <!-- PROCESS PAYMENT TERMINAL MODAL -->
  <template x-teleport="body">
  <div class="mobile-terminal-modal">
  <x-detail-modal title="Process Payment" open="modalOpen">
    <div x-data="{
           termStep: 'form',
           selectedTenant: null,
            termAmount: 0, termApplianceShare: 0, extraStayDays: 0,
           signing: false,
           termSignatureData: '',
           termCtx: null,
           residents: {{ json_encode($paymentResidents->map(fn($r) => [
             'id' => $r['user']->id,
             'name' => $r['user']->name,
             'room' => $r['user']->room?->room_number ?? $r['user']->bed?->room?->room_number ?? 'N/A',
             'total' => (float)$r['billing']['rent_amount'],
             'paid' => (float)$r['billing']['paid_amount'],
             'remaining' => max(0, (float)$r['billing']['rent_amount'] - (float)$r['billing']['paid_amount'])
           ])) }},

           initTermCanvas() {
             setTimeout(() => {
               const c = this.$refs.termCanvas;
               if (!c) return;
               const rect = c.getBoundingClientRect();
               c.width = rect.width;
               c.height = rect.height;
               this.termCtx = c.getContext('2d');
               this.termCtx.lineWidth = 2.5;
               this.termCtx.lineCap = 'round';
               this.termCtx.strokeStyle = '#0f172a';
             }, 100);
           },

           getTermPos(e) {
             const rect = this.$refs.termCanvas.getBoundingClientRect();
             const clientX = e.touches ? e.touches[0].clientX : e.clientX;
             const clientY = e.touches ? e.touches[0].clientY : e.clientY;
             return {
               x: clientX - rect.left,
               y: clientY - rect.top
             };
           },

           startTermDrawing(e) {
             e.preventDefault();
             this.signing = true;
             const pos = this.getTermPos(e);
             this.termCtx.beginPath();
             this.termCtx.moveTo(pos.x, pos.y);
           },

           drawTerm(e) {
             if (!this.signing) return;
             e.preventDefault();
             const pos = this.getTermPos(e);
             this.termCtx.lineTo(pos.x, pos.y);
             this.termCtx.stroke();
             this.termSignatureData = this.$refs.termCanvas.toDataURL('image/png');
           },

           stopTermDrawing() {
             this.signing = false;
           },

           clearTermSig() {
             if (this.termCtx) {
               this.termCtx.clearRect(0, 0, this.$refs.termCanvas.width, this.$refs.termCanvas.height);
             }
             this.termSignatureData = '';
           },

           submitTermForm() {
             this.termStep = 'sign';
             this.initTermCanvas();
           },

           completeTermPayment() {
             this.termStep = 'success';
             setTimeout(() => {
               this.$refs.termForm.submit();
             }, 1500);
           }
         }" class="space-y-4 text-slate-800">

      <!-- STEP 1: FORM WITH ROOM DROPDOWN & LIVE BALANCES -->
      <div x-show="termStep === 'form'">
        <form x-ref="termForm" method="POST" action="{{ route('admin.tenants.cash', 'tenant_id') }}" enctype="multipart/form-data" @submit.prevent="submitTermForm()" class="space-y-4">
          @csrf
          <input type="hidden" name="payment_method" :value="activeMethod">

          <div class="space-y-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700">Select Resident & Room</label>
              <select name="tenant_id" required @change="selectedTenant = residents.find(r => r.id == $event.target.value); termApplianceShare = selectedTenant ? applianceShare(selectedTenant.id) : 0; extraStayDays=0; termAmount = selectedTenant ? selectedTenant.remaining + termApplianceShare : 0" class="mt-1 w-full rounded-xl border-slate-300 text-xs focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Choose Resident / Room --</option>
                <template x-for="r in residents" :key="r.id">
                  <option :value="r.id" x-text="r.name + ' (Room ' + r.room + ')'"></option>
                </template>
              </select>
            </div>

            <template x-if="selectedTenant">
              <div class="rounded-xl bg-slate-50 p-3 border border-slate-200/80 space-y-1.5 text-xs">
                <div class="flex justify-between">
                  <span class="text-slate-500">Total Monthly Rent:</span>
                  <span class="font-bold text-slate-800" x-text="'₱' + (selectedTenant.total + termApplianceShare).toFixed(2)"></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Already Paid:</span>
                  <span class="font-bold text-slate-800" x-text="'₱' + selectedTenant.paid.toFixed(2)"></span>
                </div>
                <template x-for="item in applianceBreakdown(selectedTenant.id)" :key="item.name">
                  <div class="flex items-center justify-between text-slate-600"><span><span x-text="item.name"></span> <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700" x-text="item.type === 'personal' ? 'Personal' : 'Split'"></span></span><strong class="text-slate-800" x-text="'₱' + item.share.toFixed(2)"></strong></div>
                </template>
                <div class="flex justify-between pt-1 border-t border-slate-200/60 font-semibold">
                  <span class="text-slate-900">Current Due Balance:</span>
                  <span class="text-rose-600" x-text="'₱' + (selectedTenant.remaining + termApplianceShare + extraStayDays * prorateDailyRate).toFixed(2)"></span>
                </div>
              </div>
            </template>

            <template x-if="selectedTenant">
              <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-left">
                <p class="text-xs font-semibold text-slate-800">Extra stay</p>
                <label class="mt-2 block text-xs text-slate-600">Additional days
                  <input type="number" min="0" x-model.number="extraStayDays" @input="termAmount=selectedTenant.remaining + termApplianceShare + extraStayDays * prorateDailyRate" class="mt-1 w-full rounded-xl border-slate-300 text-xs">
                </label>
                <p class="mt-2 text-xs text-slate-700"><span x-text="extraStayDays + ' days × ₱' + Number(prorateDailyRate).toFixed(2)"></span> = <strong x-text="'₱' + (extraStayDays * prorateDailyRate).toFixed(2)"></strong></p>
              </div>
            </template>

            <div>
              <label class="block text-xs font-semibold text-slate-700">Amount Received</label>
              <input type="number" step="0.01" min="0.01" name="amount" x-model.number="termAmount" required placeholder="0.00" class="mt-1 w-full rounded-xl border-slate-300 text-xs focus:border-blue-500 focus:ring-blue-500">
            </div>

            <template x-if="selectedTenant">
              <div class="rounded-xl bg-blue-50/60 border border-blue-100 p-3 text-xs flex items-center justify-between">
                <span class="font-medium text-blue-900">New Remaining Balance:</span>
                <span class="font-extrabold text-blue-700" x-text="'₱' + Math.max(0, selectedTenant.remaining + termApplianceShare + extraStayDays * prorateDailyRate - (termAmount || 0)).toFixed(2)"></span>
              </div>
            </template>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-slate-700">Start Date</label>
                <input type="date" name="start_date" required class="mt-1 w-full rounded-xl border-slate-300 text-xs focus:border-blue-500 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700">End Date</label>
                <input type="date" name="end_date" required class="mt-1 w-full rounded-xl border-slate-300 text-xs focus:border-blue-500 focus:ring-blue-500">
              </div>
            </div>
          </div>

          <div x-show="activeMethod==='e_wallet'" class="space-y-2 pt-2 border-t border-slate-100">
            <label class="block text-xs font-semibold text-slate-700">Proof of Payment / Take Photo</label>
            <input type="file" name="payment_proof" accept="image/*" capture="environment" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 border border-slate-200 rounded-xl">
          </div>

          <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-blue-700 transition">Save Payment</button>
        </form>
      </div>

      <!-- STEP 2: SIGNATURE CONFIRMATION PROMPT -->
      <div x-show="termStep === 'sign'">
        <div class="space-y-4">
          <div class="text-center">
            <h4 class="text-sm font-bold text-slate-900">Confirm Payment by Signing</h4>
            <p class="text-xs text-slate-500 mt-0.5">Please ask resident to sign below to confirm payment of <strong class="text-blue-600" x-text="'₱' + Number(termAmount).toFixed(2)"></strong>.</p>
          </div>

          <div class="space-y-2">
            <canvas x-ref="termCanvas" 
                    @mousedown="startTermDrawing($event)" 
                    @mousemove="drawTerm($event)" 
                    @mouseup="stopTermDrawing()" 
                    @mouseleave="stopTermDrawing()" 
                    @touchstart="startTermDrawing($event)" 
                    @touchmove="drawTerm($event)" 
                    @touchend="stopTermDrawing()" 
                    class="h-36 w-full rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 touch-none cursor-crosshair"></canvas>
            
            <input type="hidden" name="tenant_signature" :value="termSignatureData">
            
            <div class="flex items-center justify-between text-xs">
              <span class="text-slate-500" x-text="termSignatureData ? 'Signature captured' : 'Draw signature above'"></span>
              <button type="button" @click="clearTermSig()" class="font-semibold text-blue-700 underline">Clear signature</button>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-2 pt-2">
            <button type="button" @click="termStep='form'" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Back</button>
            <button type="button" :disabled="!termSignatureData" @click="completeTermPayment()" class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 disabled:opacity-50 transition">Confirm & Save</button>
          </div>
        </div>
      </div>

      <!-- STEP 3: CHECKMARK ANIMATION -->
      <div x-show="termStep === 'success'">
        <div class="py-8 flex flex-col items-center justify-center text-center space-y-3">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 animate-bounce">
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <h4 class="text-base font-bold text-slate-900">Payment Confirmed!</h4>
            <p class="text-xs text-slate-500 mt-1">Updating balances and saving transaction...</p>
          </div>
        </div>
      </div>

    </div>
  </x-detail-modal>
  </div>
  </template>

  <!-- TRANSACTION BREAKDOWN MODAL -->
  <template x-teleport="body">
  <x-detail-modal title="Transaction Breakdown" open="detailModalOpen">
    <template x-if="selectedTx">
      <div class="space-y-3 text-xs text-slate-700">
        <div class="rounded-xl bg-slate-50 p-3 space-y-2 border border-slate-100">
          <div class="flex justify-between"><span class="text-slate-400">Transaction ID:</span><span class="font-mono font-semibold text-slate-800" x-text="'#TX-' + selectedTx.id"></span></div>
          <div class="flex justify-between"><span class="text-slate-400">Amount Paid:</span><span class="font-bold text-slate-900" x-text="'₱' + Number(selectedTx.amount).toFixed(2)"></span></div>
          <div class="flex justify-between"><span class="text-slate-400">Method:</span><span class="capitalize font-semibold text-slate-800" x-text="selectedTx.payment_method"></span></div>
          <div class="flex justify-between"><span class="text-slate-400">Date Processed:</span><span class="font-medium text-slate-700" x-text="selectedTx.created_at"></span></div>
        </div>
      </div>
    </template>
  </x-detail-modal>
  </template>

</section>

  {{-- ============ MAINTENANCE ============ --}}
  <section x-show="tab==='maintenance'" x-cloak class="desktop-page-transition mobile-page-transition min-w-0 w-full space-y-5 pt-4 md:pt-0">
  @php
    $repairMonthlyTotals = collect(range(1, 12))->map(function ($month) use ($requests) {
      return (float) $requests->whereNotNull('cost')->filter(fn ($repair) => $repair->created_at && $repair->created_at->year === now()->year && (int) $repair->created_at->format('n') === $month)->sum('cost');
    });
    $repairChartMax = max(1, $repairMonthlyTotals->max() ?? 0);
    $repairChartPoints = collect(range(0, 11))->map(fn ($index) => sprintf('%.1f,%.1f', 12 + ($index * (336 / 11)), 84 - (($repairMonthlyTotals[$index] / $repairChartMax) * 58)))->implode(' ');
  @endphp
  <div class="mb-5 w-full rounded-2xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
    <div class="text-center">
      <p class="text-xs font-semibold text-slate-600">Recorded repair spending</p>
      <p class="mt-0.5 text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">₱{{ number_format($repairMonthlyTotals->sum(), 2) }}</p>
      <p class="text-xs text-slate-500">This year · {{ now()->year }}</p>
    </div>
    <svg viewBox="0 0 360 100" class="mx-auto mt-2 h-14 w-full max-w-5xl overflow-visible md:mt-3 md:h-20" role="img" aria-label="Monthly repair spending from January through December">
      <path d="M12 84H348" fill="none" stroke="#e2e8f0" stroke-width="1"/>
      <polyline class="desktop-chart-line" points="{{ $repairChartPoints }}" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <div class="-mt-1 mx-auto grid max-w-5xl grid-cols-12 text-center text-[8px] font-medium text-slate-400 md:text-xs">
      @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $monthLabel)<span>{{ $monthLabel }}</span>@endforeach
    </div>
    <div class="mx-auto mt-2 max-w-5xl border-t border-slate-100 pt-2 md:mt-3">
      <button type="button" @click="repairSpendingOpen=true" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View breakdown</button>
    </div>
  </div>
  <div class="grid min-w-0 w-full gap-5 md:grid-cols-2 lg:grid-cols-3">
  @forelse($requests as $m)
    <article x-data="{s:'{{ $m->status }}',cost:'{{ $m->cost ?? '' }}',busy:false,detailOpen:false,async set(n){this.busy=true;try{const body={status:n};if(n==='resolved')body.cost=this.cost;const r=await fetch(BH.base+'/admin/maintenance/{{ $m->id }}',{method:'PATCH',headers:BH.h,body:JSON.stringify(body)});if(r.ok){this.s=n;if(n==='resolved')location.reload()}}finally{this.busy=false}}}"
             class="flex h-full min-w-0 w-full flex-col justify-between break-words rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md">
      
      <div class="space-y-3">
        <!-- Header: Avatar + Tenant Info + Status Badge -->
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <!-- Profile Avatar / Initial -->
            @if($m->tenant->avatar_url ?? false)
              <img src="{{ $m->tenant->avatar_url }}" alt="{{ $m->tenant->name }}" class="h-10 w-10 shrink-0 rounded-full object-cover border border-slate-200">
            @else
              <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                {{ strtoupper(substr($m->tenant->name, 0, 1)) }}
              </div>
            @endif

            <div class="min-w-0">
              <h4 class="truncate font-bold text-slate-900 text-sm leading-snug">{{ $m->tenant->name }}</h4>
              <p class="text-xs text-slate-500 font-medium truncate">
                {{ $m->bed?->full_name ?? 'Room Unassigned' }} · {{ $m->created_at->diffForHumans() }}
              </p>
            </div>
          </div>

        </div>

        <!-- Issue Category & Description -->
        <div class="pt-1">
          <div class="flex min-w-0 items-center gap-2">
            <h3 class="min-w-0 flex-1 truncate font-bold text-slate-900 text-base">
              {{ \App\Models\MaintenanceRequest::CATEGORIES[$m->issue_category] ?? $m->issue_category }}
            </h3>
            @if($m->urgency==='urgent')
              <span class="rounded bg-rose-100 px-1.5 py-0.5 text-[10px] font-extrabold text-rose-600">URGENT</span>
            @endif
            <span x-show="s==='pending'" class="shrink-0 whitespace-nowrap rounded-full border border-amber-200/60 bg-amber-50 px-2 py-1 text-[10px] font-semibold text-amber-700">Pending</span>
            <span x-show="s==='in_progress'" x-cloak class="shrink-0 whitespace-nowrap rounded-full border border-sky-200/60 bg-sky-50 px-2 py-1 text-[10px] font-semibold text-sky-700">In progress</span>
            <span x-show="s==='resolved'" x-cloak class="shrink-0 whitespace-nowrap rounded-full border border-emerald-200/60 bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700">Resolved</span>
          </div>
          <p class="mt-1 text-sm text-slate-600 line-clamp-3 leading-relaxed">
            {{ $m->description }}
          </p>
        </div>
      </div>

      <!-- Action Button -->
      <div class="mt-5 pt-2">
        <button @click="detailOpen = true" 
                class="w-full rounded-xl bg-blue-600 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-500 active:scale-[0.98]">
          View report
        </button>
      </div>

      <!-- Detail Modal containing full report info and Action Buttons -->
      <x-detail-modal :title="\App\Models\MaintenanceRequest::CATEGORIES[$m->issue_category] ?? $m->issue_category" mobile-fullscreen>
        <dl class="mt-4 grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2">
          <div><dt class="text-xs font-medium text-slate-500">Urgency</dt><dd class="mt-1 font-semibold capitalize text-slate-900">{{ $m->urgency }}</dd></div>
          <div><dt class="text-xs font-medium text-slate-500">Status</dt><dd class="mt-1 font-semibold capitalize text-slate-900" x-text="s.replace('_',' ')"></dd></div>
          <div><dt class="text-xs font-medium text-slate-500">Tenant</dt><dd class="mt-1 break-words font-semibold text-slate-900">{{ $m->tenant->name }}</dd></div>
          <div><dt class="text-xs font-medium text-slate-500">Room / bed</dt><dd class="mt-1 font-semibold text-slate-900">{{ $m->bed?->full_name ?? '—' }}</dd></div>
          <div class="sm:col-span-2"><dt class="text-xs font-medium text-slate-500">Reported</dt><dd class="mt-1 font-semibold text-slate-900">{{ $m->created_at->format('M j, Y · g:i A') }}</dd></div>
          <div><dt class="text-xs font-medium text-slate-500">Repair cost</dt><dd class="mt-1 font-semibold text-slate-900">{{ $m->cost !== null ? '₱'.number_format($m->cost, 2) : 'Not recorded' }}</dd></div>
          <div class="sm:col-span-2"><dt class="text-xs font-medium text-slate-500">Full description</dt><dd class="mt-1 whitespace-pre-wrap break-words text-sm leading-6 text-slate-700">{{ $m->description }}</dd></div>
          <div class="sm:col-span-2">
            <dt class="text-xs font-medium text-slate-500">Images</dt>
            @if($m->image)
              <a href="{{ asset('storage/'.$m->image) }}" target="_blank" class="mt-2 block"><img src="{{ asset('storage/'.$m->image) }}" class="max-h-64 w-full rounded-xl border border-slate-200 object-cover" alt="Repair photo"></a>
            @else
              <div class="mt-2 flex min-h-24 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 text-sm text-slate-400">No images attached</div>
            @endif
          </div>
        </dl>

        <!-- Status Action Buttons in Modal -->
        <div x-show="s==='in_progress'" x-cloak class="mt-6 border-t border-slate-100 pt-4">
          <label class="block text-xs font-semibold text-slate-700">Repair cost (enter 0 if there was no expense)
            <div class="mt-1 flex items-center rounded-xl border border-slate-300 bg-white px-3 py-2"><span class="mr-2 text-slate-400">₱</span><input x-model="cost" type="number" min="0" step="0.01" placeholder="0.00" class="w-full border-0 p-0 text-sm focus:ring-0"></div>
          </label>
        </div>
        <div class="mt-4 flex gap-2 border-t border-slate-100 pt-4" :class="busy && 'opacity-50 pointer-events-none'">
          <button x-show="s==='pending'" @click="set('in_progress')" class="flex-1 rounded-xl bg-sky-600 py-2.5 text-xs font-semibold text-white transition hover:bg-sky-500">Start work</button>
          <button x-show="s==='in_progress'" x-cloak @click="set('resolved')" class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-500">Mark resolved</button>
          <button x-show="s==='resolved'" x-cloak @click="set('pending')" class="flex-1 rounded-xl border border-slate-200 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Reopen</button>
        </div>
      </x-detail-modal>
    </article>
  @empty 
    <div class="col-span-full rounded-2xl bg-white p-8 text-center text-sm text-slate-500">
      No repair requests found.
    </div> 
  @endforelse
  </div>


  </section>

  <template x-teleport="body">
    <div x-show="repairSpendingOpen" x-cloak @keydown.escape.window="repairSpendingOpen=false" @click.self="repairSpendingOpen=false" class="fixed inset-0 z-[9999] flex overflow-y-auto bg-white p-0 sm:items-center sm:justify-center sm:bg-slate-900/50 sm:p-4" role="dialog" aria-modal="true" aria-label="Repair spending breakdown">
      <div class="my-0 min-h-full w-full max-w-none bg-white p-5 shadow-none sm:my-auto sm:min-h-0 sm:max-w-lg sm:rounded-2xl sm:p-6 sm:shadow-2xl" @click.stop>
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
          <div class="min-w-0"><p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Details</p><h3 class="mt-1 text-lg font-bold text-slate-900">Repair spending breakdown</h3></div>
          <button type="button" @click="repairSpendingOpen=false" class="shrink-0 rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="Close details">✕</button>
        </div>
        <div class="mt-4 space-y-3">
          @forelse($requests->whereNotNull('cost') as $spent)
            <div class="flex min-w-0 items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
              <div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-900">{{ \App\Models\MaintenanceRequest::CATEGORIES[$spent->issue_category] ?? $spent->issue_category }} · {{ $spent->tenant->name }}</p><p class="mt-1 text-xs text-slate-500">{{ $spent->created_at->format('M j, Y') }}</p></div>
              <p class="shrink-0 text-sm font-bold {{ $spent->cost > 0 ? 'text-rose-600' : 'text-emerald-600' }}">₱{{ number_format($spent->cost, 2) }}</p>
            </div>
          @empty
            <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No repair costs recorded.</p>
          @endforelse
        </div>
      </div>
    </div>
  </template>

  {{-- ============ SETTINGS ============ --}}
  <section x-show="tab==='settings'" x-cloak class="desktop-page-transition mobile-page-transition pt-4 md:pt-0">
    <form method="POST" action="{{ route('admin.settings') }}" class="grid min-w-0 gap-5 lg:grid-cols-2" x-data="{ surcharges: @js(old('device_surcharges', $settings['device_surcharges'])) }">@csrf
      <section class="min-w-0 space-y-4 rounded-2xl bg-white p-5 shadow-sm">
        <div><h2 class="font-bold text-slate-900">Rent</h2><p class="mt-1 text-xs text-slate-500">One monthly rate applies to each room.</p></div>
        <label class="block text-sm font-medium text-slate-600">Default monthly room rate
          <input name="rent[default_monthly_rate]" type="number" min="0" step="0.01" required value="{{ old('rent.default_monthly_rate', $settings['rent']['default_monthly_rate']) }}" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </label>
        <label class="block text-sm font-medium text-slate-600">Extra-stay rate per day
          <input type="number" min="0" step="0.01" x-model.number="prorateDailyRate" value="20" class="mt-1 w-full rounded-xl border-slate-300 text-sm">
        </label>
      </section>

      <section class="min-w-0 space-y-4 rounded-2xl bg-white p-5 shadow-sm">
        <div><h2 class="font-bold text-slate-900">Due dates</h2><p class="mt-1 text-xs text-slate-500">These rules determine the tenant payment status.</p></div>
        <label class="block text-sm font-medium text-slate-600">Grace period before overdue
          <span class="mt-1 flex items-center gap-2"><input name="due_dates[grace_period_days]" type="number" min="0" max="31" required value="{{ old('due_dates.grace_period_days', $settings['due_dates']['grace_period_days']) }}" class="w-28 rounded-xl border-slate-300 text-sm"><span class="text-sm text-slate-500">days after due date</span></span>
        </label>
        <label class="block text-sm font-medium text-slate-600">Show “Due soon”
          <span class="mt-1 flex items-center gap-2"><input name="due_dates[due_soon_days]" type="number" min="0" max="31" required value="{{ old('due_dates.due_soon_days', $settings['due_dates']['due_soon_days']) }}" class="w-28 rounded-xl border-slate-300 text-sm"><span class="text-sm text-slate-500">days before the due date</span></span>
        </label>
      </section>

      <section class="min-w-0 space-y-4 rounded-2xl bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2"><div><h2 class="font-bold text-slate-900">Device surcharges</h2><p class="mt-1 text-xs text-slate-500">Set the monthly fee for each device type.</p></div>
          <button type="button" @click="surcharges.push({name:'',fee:''})" class="rounded-lg border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-50">Add device</button>
        </div>
        <template x-for="(item, index) in surcharges" :key="index">
          <div class="grid min-w-0 grid-cols-[minmax(0,1fr)_minmax(5rem,8rem)_auto] items-end gap-2">
            <label class="min-w-0 text-xs font-medium text-slate-500">Device type
              <input :name="`device_surcharges[${index}][name]`" x-model="item.name" maxlength="60" placeholder="e.g. Refrigerator" class="mt-1 w-full min-w-0 rounded-xl border-slate-300 text-sm">
            </label>
            <label class="min-w-0 text-xs font-medium text-slate-500">Monthly fee
              <input :name="`device_surcharges[${index}][fee]`" x-model="item.fee" type="number" min="0" step="0.01" class="mt-1 w-full min-w-0 rounded-xl border-slate-300 text-sm">
            </label>
            <button type="button" @click="surcharges.splice(index,1)" aria-label="Remove device surcharge" class="mb-0.5 rounded-lg px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Remove</button>
          </div>
        </template>
        <p x-show="surcharges.length===0" class="rounded-xl bg-slate-50 p-3 text-sm text-slate-500">No device surcharges added.</p>
      </section>

      <button class="rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 lg:col-span-2">Save Settings</button>
    </form>
  </section>

  {{-- ============ ADD TENANT MODAL ============ --}}
  <div x-show="addTenantModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-stretch justify-stretch overflow-y-auto bg-white p-0 sm:items-center sm:justify-center sm:bg-transparent sm:p-4">
    <div class="fixed inset-0 bg-transparent sm:bg-slate-900/40" @click="addTenantModal = false" x-transition.opacity></div>
    <div class="relative min-h-full w-full rounded-none bg-white p-5 shadow-none sm:min-h-0 sm:max-w-md sm:rounded-2xl sm:p-6 sm:shadow-2xl" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
      <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-900">Add Tenant Account</h3>
        <button @click="addTenantModal = false" class="rounded-full bg-slate-100 p-1 text-slate-500 hover:bg-slate-200">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.tenants.store') }}" class="mt-4 space-y-3">@csrf
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Full Name</label>
          <input name="name" required placeholder="John Doe" class="w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
          <input name="email" type="email" required placeholder="john@example.com" class="w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Phone Number</label>
          <input name="phone" placeholder="09123456789" class="w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Temporary Password</label>
          <input name="password" type="password" required minlength="8" placeholder="••••••••" class="w-full rounded-xl border-slate-300 text-sm">
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" @click="addTenantModal = false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
          <button class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-500">Create Tenant</button>
        </div>
      </form>
    </div>
  </div>

  {{-- FIXED MOBILE BOTTOM NAVIGATION BAR --}}
  <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white md:hidden" style="padding-bottom:env(safe-area-inset-bottom)">
    <div class="grid grid-cols-5 h-14">
      @foreach($mobileTabs as $k=>[$l,$n,$svg])
        <button @click="go('{{ $k }}')" :class="tab==='{{ $k }}' ? 'text-blue-600 font-bold' : 'text-slate-500 font-medium'" class="relative flex flex-col items-center justify-center gap-0.5 text-[10px]">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $svg }}"/></svg>
          <span>{{ $l }}</span>
          @if($n)<span class="absolute top-1 right-3 rounded-full bg-rose-500 px-1 text-[9px] leading-3 text-white font-bold">{{ $n }}</span>@endif
        </button>
      @endforeach
    </div>
  </nav>

  {{-- ============ BED SLIDE-OVER ============ --}}
  <div x-show="sel" x-cloak class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/30" @click="close()" x-transition.opacity></div>
    <aside x-show="sel" x-transition:enter="transition duration-200" x-transition:enter-start="translate-y-full md:translate-y-0 md:translate-x-full" x-transition:enter-end="translate-y-0 md:translate-x-0"
           class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto rounded-t-3xl bg-white p-5 pb-8 shadow-2xl md:inset-y-0 md:left-auto md:right-0 md:max-h-none md:w-[26rem] md:rounded-none">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-xs text-slate-500">Room <span x-text="sel?.room"></span></p>
          <h2 class="text-xl font-bold" x-text="sel?.title"></h2>
        </div>
        <button @click="close()" class="rounded-full bg-slate-100 px-3 py-1 text-sm">✕</button>
      </div>
      <template x-for="b in (sel?.bunks||[])" :key="b.id">
        <div class="mt-4 space-y-3 rounded-2xl border border-slate-200 p-4">
          <div class="flex items-center justify-between">
            <p class="font-bold" x-text="b.label.split('· ')[1] + ' bunk'"></p>
            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold text-white" :style="'background:'+BH.colors[b.status]" x-text="BH.names[b.status]"></span>
          </div>
          <div class="grid grid-cols-2 gap-1.5">
            <template x-for="(n,k) in BH.names" :key="k">
              <button @click="setStatus(b,k)" class="rounded-lg border-2 px-2 py-1.5 text-xs font-semibold" :style="b.status===k ? 'border-color:'+BH.colors[k]+';background:'+BH.colors[k]+'22' : 'border-color:transparent;background:#f1f5f9'" x-text="n"></button>
            </template>
          </div>
          <div x-show="mapMode==='simple'" class="flex gap-2"><button type="button" @click="startBedEdit(b)" class="flex-1 rounded-lg border border-slate-200 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit bed</button><button type="button" @click="removeSimpleBed(b)" class="flex-1 rounded-lg border border-rose-200 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Remove bed</button></div>
          <template x-if="b.tenant">
            <div class="space-y-2 rounded-xl bg-slate-50 p-3 text-sm">
              <p class="font-semibold" x-text="b.tenant.name"></p>
              <div class="flex gap-2">
                <form method="POST" :action="BH.base+'/admin/beds/'+b.id+'/vacate'" class="flex-1" onsubmit="return confirm('Move this tenant out?')"><input type="hidden" name="_token" :value="BH.csrf"><button class="w-full rounded-lg border border-rose-200 py-2 text-xs font-semibold text-rose-600">Move out</button></form>
              </div>
            </div>
          </template>
          <template x-if="!b.tenant && BH.unassigned.length">
            <form method="POST" :action="BH.base+'/admin/beds/'+b.id+'/assign'" class="flex gap-2"><input type="hidden" name="_token" :value="BH.csrf">
              <select name="user_id" required class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm"><option value="">Assign tenant…</option>
                <template x-for="u in BH.unassigned" :key="u.id"><option :value="u.id" x-text="u.name"></option></template></select>
              <button class="rounded-lg bg-blue-600 px-3 text-xs font-semibold text-white">Assign</button></form>
          </template>
        </div>
      </template>
    </aside>
  </div>
  <div x-show="manageEditor" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/40 p-4" @keydown.escape.window="manageEditor=null">
    <form @submit.prevent="saveManagementEdit()" class="w-full max-w-md rounded-2xl bg-white p-5 shadow-2xl">
      <div class="flex items-center justify-between"><h2 class="text-lg font-bold text-slate-900" x-text="manageEditor?.kind==='room' ? 'Edit room name' : (manageEditor?.kind==='assign' ? 'Assign tenant to bed' : 'Edit bed')"></h2><button type="button" @click="manageEditor=null" class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">✕</button></div>
      <template x-if="manageEditor?.kind==='room'"><div class="mt-4"><label class="mb-1 block text-sm font-medium text-slate-700">Room name</label><input x-model="manageEditor.name" maxlength="20" required class="w-full rounded-lg border-slate-300"></div></template>
      <template x-if="manageEditor?.kind==='bed'"><div class="mt-4 space-y-3"><div><label class="mb-1 block text-sm font-medium text-slate-700">Bed number</label><input x-model.number="manageEditor.bedNo" type="number" min="1" max="255" required class="w-full rounded-lg border-slate-300"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Bunk position</label><select x-model="manageEditor.bunk" class="w-full rounded-lg border-slate-300"><option value="A">Top</option><option value="B">Bottom</option></select></div></div></template>
      <template x-if="manageEditor?.kind==='assign'"><div class="mt-4 space-y-3"><div><label class="mb-1 block text-sm font-medium text-slate-700">Tenant</label><select x-model="manageEditor.tenantId" required class="w-full rounded-lg border-slate-300"><option value="">Choose tenant</option><template x-for="tenant in localAssignmentTenants" :key="tenant.id"><option :value="tenant.id" x-text="tenant.name"></option></template></select></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Bed</label><select x-model="manageEditor.bedNo" required class="w-full rounded-lg border-slate-300"><option value="">Choose bed</option><template x-for="bunk in simpleBunks(simpleSelectedRoom)" :key="bunk.number"><option :value="bunk.number" x-text="'Bed ' + bunk.number" :disabled="!availableBunks(simpleSelectedRoom,bunk.number).length"></option></template></select></div><div x-show="manageEditor.bedNo"><label class="mb-1 block text-sm font-medium text-slate-700">Bunk position</label><select x-model="manageEditor.bunk" required class="w-full rounded-lg border-slate-300"><option value="">Choose top or bottom</option><template x-for="position in availableBunks(simpleSelectedRoom,manageEditor.bedNo)" :key="position.key"><option :value="position.key" x-text="position.label"></option></template></select></div><p class="text-xs text-slate-500">Frontend preview only; resets on reload.</p></div></template>
      <p x-show="manageError" x-text="manageError" class="mt-3 text-sm text-rose-600"></p>
      <div class="mt-5 flex justify-end gap-2"><button type="button" @click="manageEditor=null" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">Cancel</button><button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Save changes</button></div>
    </form>
  </div></div>
@endsection

@push('scripts')
<script>
window.BH = {
  base: @json(url('/')), csrf: document.querySelector('meta[name=csrf-token]').content,
  beds: @json($bedData), statuses: @json($statuses), unassigned: @json($unassigned), tab: @json(session('tab')),
  names: {available:'Available', occupied:'Occupied', reserved:'Reserved', ready:'Ready to be available'},
  colors: @json($stColor),
};
BH.h = {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':BH.csrf};

function adminApp() {
  return {
    applianceOptions: @js($settings['device_surcharges']),
    applianceResidents: @js($paymentResidents->map(fn($r) => ['id' => $r['user']->id, 'name' => $r['user']->name, 'room' => $r['user']->room?->room_number ?? 'N/A'])->values()),
    applianceAgreements: [], applianceSetupOpen: false, newApplianceIndex: '', newApplianceMode: 'split', newConsentMode: 'manual', newRequestFromTenantId: '', newShareTenantIds: [], confirmedShareTenantIds: [],
    prorateDailyRate: 20,
      tab: (t => { t = ['dashboard','tenants','sales','reports','payments','map','maintenance','settings'].includes(t) ? t : 'dashboard'; if (t === 'reports') t = 'sales'; return window.matchMedia('(min-width: 768px)').matches && t === 'sales' ? 'dashboard' : t; })(BH.tab || location.hash.slice(1)),
    beds: BH.beds, localAssignmentTenants: [{id: 'local-tenant-1', name: 'Test Tenant 01'}, {id: 'local-tenant-2', name: 'Test Tenant 02'}, {id: 'local-tenant-3', name: 'Test Tenant 03'}], sel: null, map: null, mapMode: 'simple', simpleSelectedRoom: 17, hiddenSimpleRooms: [], manageEditor: null, manageError: '', mapUrl: @json(asset('map/boarding_house_map.html')), simpleRooms: [{n:17,x:30,y:28,w:80,h:90},{n:16,x:120,y:28,w:80,h:90},{n:15,x:210,y:28,w:80,h:90},{n:14,x:300,y:28,w:80,h:90},{n:13,x:390,y:28,w:80,h:90},{n:3,x:520,y:28,w:70,h:76},{n:2,x:600,y:28,w:70,h:76},{n:1,x:680,y:28,w:70,h:76},{n:4,x:520,y:112,w:70,h:70},{n:5,x:640,y:112,w:100,h:64},{n:6,x:640,y:226,w:110,h:96},{n:8,x:620,y:340,w:120,h:60},{n:7,x:620,y:410,w:120,h:60},{n:12,x:100,y:400,w:80,h:80},{n:11,x:190,y:400,w:80,h:80},{n:10,x:280,y:400,w:80,h:80},{n:9,x:370,y:400,w:80,h:80}], addTenantModal: false, reportOpen: false, repairSpendingOpen: false,
      init() {
      try { this.applianceAgreements = JSON.parse(localStorage.getItem('bh-appliance-agreements') || '[]'); } catch {}
      window.addEventListener('storage', event => { if (event.key === 'bh-appliance-agreements') this.applianceAgreements = JSON.parse(event.newValue || '[]'); });
      this.$watch('tab', t => {
        history.replaceState(null, '', '#' + t);
        if (t === 'map' && this.mapMode === 'interactive') this.$nextTick(() => this.refreshInteractiveMap());
        else if (t !== 'map' && this.mapMode === 'interactive' && this.$refs.map) this.$refs.map.src = 'about:blank';
      });
      this.map = new BHMap(this.$refs.map, {
        onReady: m => m.setStatuses(BH.statuses),
        onSelect: d => d.level === 'bed' ? this.open(d.meshIds) : (this.sel = null),
      });
    },
    exportReport() {
      const rows = [...document.querySelectorAll('#generated-report-content tr')].map(row => [...row.children].map(cell => '"' + cell.innerText.replaceAll('"', '""') + '"'));
      const url = URL.createObjectURL(new Blob([rows.map(row => row.join(',')).join('\n')], { type: 'text/csv' }));
      const link = document.createElement('a'); link.href = url; link.download = 'boardershub-report.csv'; link.click(); URL.revokeObjectURL(url);
    },
    printReport() {
      const printWindow = window.open('', '_blank');
      if (!printWindow) return;
      printWindow.document.write('<title>BoardersHub Report</title><style>body{font:14px Arial,sans-serif;padding:32px}table{width:100%;border-collapse:collapse}th,td{text-align:left;border-bottom:1px solid #ddd;padding:10px}th{color:#555}</style><h1>Boarding House Report</h1><p>As of {{ now()->format('M j, Y') }}</p>' + document.querySelector('#generated-report-content').outerHTML);
      printWindow.document.close(); printWindow.focus(); printWindow.print();
    },
    saveApplianceAgreement() {
      const device = this.applianceOptions[Number(this.newApplianceIndex)];
      if (!device || !this.newShareTenantIds.length || (this.newApplianceMode === 'personal' && this.newShareTenantIds.length !== 1) || (this.newApplianceMode === 'split' && this.newShareTenantIds.length < 2)) return;
      const agreement = { name: device.name, fee: Number(device.fee), type: this.newApplianceMode, tenantIds: [...this.newShareTenantIds] };
      if (this.newApplianceMode === 'split' && this.newConsentMode === 'request') {
        const initiator = this.applianceResidents.find(resident => resident.id == this.newRequestFromTenantId);
        const recipients = this.newShareTenantIds.filter(id => id != this.newRequestFromTenantId);
        if (!initiator || !recipients.length) return;
        const requests = JSON.parse(localStorage.getItem('bh-appliance-requests') || '[]');
        requests.push({ id: Date.now().toString(36) + Math.random().toString(36).slice(2), agreement, initiatorId: initiator.id, initiatorName: initiator.name, recipientIds: recipients, responses: Object.fromEntries(recipients.map(id => [id, 'pending'])), status: 'pending' });
        localStorage.setItem('bh-appliance-requests', JSON.stringify(requests));
      } else {
        if (this.newApplianceMode === 'split' && !this.newShareTenantIds.every(id => this.confirmedShareTenantIds.some(ok => ok == id))) return;
        this.applianceAgreements.push(agreement);
        localStorage.setItem('bh-appliance-agreements', JSON.stringify(this.applianceAgreements));
      }
      this.newApplianceIndex = ''; this.newApplianceMode = 'split'; this.newConsentMode = 'manual'; this.newRequestFromTenantId = ''; this.newShareTenantIds = []; this.confirmedShareTenantIds = [];
    },
    removeApplianceAgreement(index) {
      this.applianceAgreements.splice(index, 1);
      localStorage.setItem('bh-appliance-agreements', JSON.stringify(this.applianceAgreements));
    },
    applianceShare(tenantId) {
      return this.applianceAgreements.reduce((sum, item) => item.tenantIds.some(id => id == tenantId) ? sum + item.fee / item.tenantIds.length : sum, 0);
    },
    applianceBreakdown(tenantId) {
      return this.applianceAgreements.filter(item => item.tenantIds.some(id => id == tenantId)).map(item => ({ ...item, share: item.type === 'personal' ? item.fee : item.fee / item.tenantIds.length }));
    },
    simpleRoomsSvg() { return this.simpleRooms.filter(room => !this.hiddenSimpleRooms.some(hidden => String(hidden.n) === String(room.n))).map(room => { const summary = this.simpleRoomSummary(room.n); const selected = String(this.simpleSelectedRoom) === String(room.n); return '<g data-room="' + room.n + '" role="button" tabindex="0" aria-label="Select room ' + room.n + ', ' + summary + '" style="cursor:pointer"><rect x="' + room.x + '" y="' + room.y + '" width="' + room.w + '" height="' + room.h + '" rx="10" fill="' + this.simpleRoomFill(room.n) + '" stroke="' + (selected ? '#2dd4bf' : 'white') + '" stroke-width="' + (selected ? '6' : '3') + '"/><text x="' + (room.x + room.w / 2) + '" y="' + (room.y + room.h / 2 + 7) + '" text-anchor="middle" fill="white" font-size="20" font-weight="700">' + room.n + '</text></g>'; }).join(''); },
    roomBeds(room) { return Object.values(this.beds).filter(b => String(b.room) === String(room)); },
    simpleRoomSummary(room) { const list = this.roomBeds(room); return list.length ? this.roomBeds(room).filter(b => b.status === 'occupied').length + ' of ' + list.length + ' beds occupied' : 'No bed data'; },
    simpleRoomAddSvg() { return this.hiddenSimpleRooms.map(room => `<g data-room-add="${room.n}" role="button" tabindex="0" style="cursor:pointer"><circle cx="${room.x+room.w/2}" cy="${room.y+room.h/2}" r="22" fill="#2563eb"/><text x="${room.x+room.w/2}" y="${room.y+room.h/2+8}" text-anchor="middle" fill="white" font-size="25" font-weight="700">+</text></g>`).join(""); },
    simpleRoomFill(room) { return '#64748b'; },
    simpleBunks(room) { const grouped = {}; this.roomBeds(room).forEach(bed => { const match = bed.label.match(/Bed\s*(\d+)/i); const number = match ? Number(match[1]) : 1; if (!grouped[number]) grouped[number] = { number, top: null, bottom: null }; if (/bottom/i.test(bed.label)) grouped[number].bottom = bed; else grouped[number].top = bed; }); return Object.values(grouped).sort((a,b) => a.number - b.number); },
    openSimpleRoom(room) { this.simpleSelectedRoom = room; },
    startRoomEdit() { this.manageError = ''; this.manageEditor = { kind: 'room', original: String(this.simpleSelectedRoom), name: String(this.simpleSelectedRoom) }; },
    startBedEdit(bed) { this.manageError = ''; this.manageEditor = { kind: 'bed', meshId: bed.mesh_id, bedNo: bed.bed_no || Number((bed.label.match(/Bed\s*(\d+)/i)||[])[1] || 1), bunk: bed.bunk || (/bottom/i.test(bed.label) ? 'B' : 'A') }; },
    startTenantAssignment() { if (!this.localAssignmentTenants.length) { alert('No local test tenants remain to assign.'); return; } this.manageError = ''; this.manageEditor = { kind: 'assign', room: this.simpleSelectedRoom, tenantId: '', bedNo: '', bunk: '' }; },
    availableBunks(room, bedNo) { const bunk=this.simpleBunks(room).find(item=>String(item.number)===String(bedNo)); if(!bunk)return []; return [{key:'A',label:'Top',bed:bunk.top},{key:'B',label:'Bottom',bed:bunk.bottom}].filter(position=>position.bed&&!position.bed.tenant&&!['occupied','reserved'].includes(position.bed.status)); },
    saveManagementEdit() {
      if (!this.manageEditor) return;
      if (this.manageEditor.kind === 'assign') {
        const tenant = this.localAssignmentTenants.find(item => String(item.id) === String(this.manageEditor.tenantId));
        const bed = this.availableBunks(this.manageEditor.room, this.manageEditor.bedNo).find(item => item.key === this.manageEditor.bunk)?.bed;
        if (!bed || !tenant) { this.manageError = 'Choose a tenant, bed, and available bunk position.'; return; }
        bed.tenant = { id: tenant.id, name: tenant.name }; bed.status = 'occupied'; BH.statuses[bed.mesh_id] = 'occupied'; this.localAssignmentTenants = this.localAssignmentTenants.filter(item => String(item.id) !== String(tenant.id));
        if (this.map) this.map.setStatus(bed.mesh_id, 'occupied');
      } else if (this.manageEditor.kind === 'room') {
        const name = String(this.manageEditor.name || '').trim(); const oldName = this.manageEditor.original;
        if (!name) { this.manageError = 'Enter a room name.'; return; }
        if (this.simpleRooms.some(room => String(room.n) === name && String(room.n) !== oldName)) { this.manageError = 'That room name is already in use.'; return; }
        const room = this.simpleRooms.find(room => String(room.n) === oldName); if (!room) { this.manageEditor = null; return; } room.n = name;
        Object.values(this.beds).forEach(bed => { if (String(bed.room) === oldName) bed.room = name; }); this.simpleSelectedRoom = name;
      } else {
        const bed = this.beds[this.manageEditor.meshId]; if (!bed) { this.manageEditor = null; return; }
        const bedNo = Number(this.manageEditor.bedNo); const bunk = this.manageEditor.bunk;
        const duplicate = Object.values(this.beds).some(other => other.mesh_id !== bed.mesh_id && String(other.room) === String(bed.room) && Number(other.bed_no || (other.label.match(/Bed\s*(\d+)/i)||[])[1]) === bedNo && (other.bunk || (/bottom/i.test(other.label) ? 'B' : 'A')) === bunk);
        if (!Number.isInteger(bedNo) || bedNo < 1 || bedNo > 255 || duplicate) { this.manageError = duplicate ? 'That bed number and bunk already exist in this room.' : 'Enter a valid bed number.'; return; }
        bed.bed_no = bedNo; bed.bunk = bunk; bed.label = 'Bed ' + bedNo + ' · ' + (bunk === 'A' ? 'Top' : 'Bottom');
        if (this.sel) { this.sel.bunks = this.sel.bunks.map(item => item.mesh_id === bed.mesh_id ? bed : item); this.sel.title = bed.label.split(' · ')[0]; }
      }
      this.manageEditor = null; this.manageError = '';
    },
    addSimpleRoom(name) { const i=this.hiddenSimpleRooms.findIndex(room=>String(room.n)===String(name)); if(i<0)return; const room=this.hiddenSimpleRooms[i]; const newName=String(prompt("Name the new room:",room.n)||"").trim(); if(!newName)return; if(this.simpleRooms.some(item=>String(item.n)===newName)){alert("That room name is already in use.");return;} const old=String(room.n); this.hiddenSimpleRooms.splice(i,1); room.n=newName; Object.values(this.beds).forEach(bed=>{if(String(bed.room)===old)bed.room=newName;}); this.simpleSelectedRoom=newName; },
    removeSimpleRoom() {
      if (!confirm('Remove this room? Its tile will become a + where you can add a replacement room.')) return;
      const name = String(this.simpleSelectedRoom); const room = this.simpleRooms.find(item => String(item.n) === name); if (!room) return;
      this.hiddenSimpleRooms.push(room);
      this.simpleSelectedRoom = this.simpleRooms.find(item => String(item.n) !== name && !this.hiddenSimpleRooms.some(hidden => String(hidden.n) === String(item.n)))?.n || '';
      if (this.sel && String(this.sel.room) === name) this.close();
    },
    removeSimpleBed(bed) {
      if (bed.tenant || bed.status === 'occupied') { alert('Move out the assigned tenant before removing this bed.'); return; }
      if (!confirm('Remove this bed from the current view? This frontend-only change resets when the page reloads.')) return;
      delete this.beds[bed.mesh_id]; delete BH.statuses[bed.mesh_id];
      if (this.sel) { this.sel.bunks = this.sel.bunks.filter(item => item.mesh_id !== bed.mesh_id); if (!this.sel.bunks.length) this.close(); }
      if (this.map) this.map.setStatuses(BH.statuses);
    },
    setMapMode(mode) { this.mapMode = mode; if (mode === 'interactive') this.refreshInteractiveMap(); else if (this.$refs.map) this.$refs.map.src = 'about:blank'; },
    refreshInteractiveMap() { const frame = this.$refs.map; if (!frame) return; const url = new URL(this.mapUrl, location.origin); url.searchParams.set('embed', '1'); url.searchParams.set('mode', 'admin'); url.searchParams.set('theme', document.documentElement.classList.contains('bh-dark') ? 'dark' : 'light'); url.searchParams.set('refresh', Date.now()); frame.src = url.toString(); },
    go(k) { this.tab = k; window.scrollTo({ top: 0 }); },
    open(ids) {
      const bunks = ids.map(i => this.beds[i]).filter(Boolean);
      if (bunks.length) this.sel = { room: bunks[0].room, title: bunks[0].label.split(' · ')[0], bunks };
    },
    close() { this.sel = null; },
    async setStatus(b, status) {
      const r = await fetch(BH.base + '/admin/beds/' + b.id + '/status', {method:'PATCH', headers: BH.h, body: JSON.stringify({status})});
      if (r.ok) { this.beds[b.mesh_id].status = status; this.map.setStatus(b.mesh_id, status); }
    },
  };
}
</script>
@endpush
