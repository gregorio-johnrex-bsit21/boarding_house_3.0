@extends('layouts.tenant')
@section('title','Home · BoardersHub')
@section('content')
<section x-data="applianceRequestInbox()" x-init="init()" x-show="requests.length" x-cloak class="space-y-3">
  <template x-for="request in requests" :key="request.id">
    <article class="rounded-2xl border border-blue-200 bg-white p-4 shadow-sm">
      <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Appliance charge request</p>
      <p class="mt-2 text-sm text-slate-700"><strong x-text="request.initiatorName"></strong> wants to split the <strong x-text="request.agreement.name"></strong> bill with you.</p>
      <p class="mt-1 text-sm text-slate-500">Your monthly share: <strong class="text-slate-900" x-text="'₱' + (request.agreement.fee / request.agreement.tenantIds.length).toFixed(2)"></strong></p>
      <div class="mt-3 flex gap-2">
        <button type="button" @click="respond(request.id, 'accepted')" class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white">Agree</button>
        <button type="button" @click="respond(request.id, 'rejected')" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700">Reject</button>
      </div>
    </article>
  </template>
</section>
@if(! $bed)
  <div class="rounded-2xl bg-white p-6 text-center shadow-sm"><p class="text-3xl">🛏️</p><p class="mt-2 font-bold">No bed assigned yet</p>
  <p class="text-sm text-slate-500">Your landlord will assign you a bed. You'll see it here.</p></div>
@else
  @php
    $periodStart = $billing['period']->copy()->startOfMonth();
    $periodEnd = $periodStart->copy()->addMonthNoOverflow();
  @endphp
  <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
      <div><p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Current stay</p><h1 class="mt-1 text-xl font-bold text-slate-900">Room {{ $bed->room->number }} · {{ $bed->label }}</h1><p class="mt-1 text-sm text-slate-500">{{ $bed->bunk === 'A' ? 'Top bunk' : ($bed->bunk === 'B' ? 'Bottom bunk' : 'Assigned bed') }}</p></div>
      <x-badge :status="$billing['status']" />
    </div>

    <div class="mt-5 border-t border-slate-100 pt-4">
      <div class="flex flex-wrap items-end justify-between gap-2"><div><h2 class="font-bold text-slate-900">Billing</h2><p class="mt-1 text-sm text-slate-500">{{ $periodStart->format('M j, Y') }} – {{ $periodEnd->format('M j, Y') }}</p></div><p class="text-sm text-slate-500">Due {{ $billing['due']->format('M j, Y') }}</p></div>
      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Monthly rent</p><p class="mt-1 text-lg font-bold text-slate-900">₱{{ number_format($billing['amount'], 2) }}</p></div>
        <div class="rounded-xl bg-emerald-50 p-3"><p class="text-xs text-emerald-700">Paid this period</p><p class="mt-1 text-lg font-bold text-emerald-800">₱{{ number_format($billing['paid_amount'], 2) }}</p></div>
        <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Remaining balance</p><p class="mt-1 text-lg font-bold text-slate-900">₱{{ number_format($billing['remaining'], 2) }}</p></div>
      </div>
      @if($roommates->isNotEmpty())<p class="mt-4 text-xs text-slate-500">Roommates: {{ $roommates->join(', ') }}</p>@endif
    </div>
  </section>
@endif
<script>
window.applianceRequestInbox = function () {
  return {
    tenantId: @js(auth()->id()),
    requests: [],
    init() {
      this.read();
      window.addEventListener('storage', event => { if (event.key === 'bh-appliance-requests') this.read(); });
    },
    read() {
      let all = [];
      try { all = JSON.parse(localStorage.getItem('bh-appliance-requests') || '[]'); } catch {}
      this.requests = all.filter(request => request.status === 'pending' && request.responses?.[this.tenantId] === 'pending');
    },
    respond(requestId, decision) {
      let all = [];
      try { all = JSON.parse(localStorage.getItem('bh-appliance-requests') || '[]'); } catch {}
      const request = all.find(item => item.id === requestId);
      if (!request) return;
      request.responses[this.tenantId] = decision;
      if (decision === 'rejected') request.status = 'rejected';
      else if (request.recipientIds.every(id => request.responses[id] === 'accepted')) {
        request.status = 'accepted';
        const agreements = JSON.parse(localStorage.getItem('bh-appliance-agreements') || '[]');
        agreements.push(request.agreement);
        localStorage.setItem('bh-appliance-agreements', JSON.stringify(agreements));
      }
      localStorage.setItem('bh-appliance-requests', JSON.stringify(all));
      this.read();
    }
  };
};
</script>
@endsection
