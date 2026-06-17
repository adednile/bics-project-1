@extends('layouts.app')
@section('title', 'SMS Parser & Unmapped Queue')

@section('content')
<div class="space-y-6">

    {{-- Header Card --}}
    <div class="premium-card rounded-2xl p-6 flex items-center justify-between flex-wrap gap-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
        <div>
            <h3 class="text-base font-bold font-title text-white">SMS Contribution Parser</h3>
            <p class="text-xs text-slate-400 mt-1">Paste M-Pesa SMS confirmation codes to auto-extract contributions.</p>
        </div>
        <button onclick="openSmsModal()" class="gold-gradient-btn px-6 py-2.5 rounded-xl font-bold flex items-center gap-1.5 text-xs shadow-md">
            <span class="material-symbols-outlined text-sm">sms</span> Parse New M-Pesa SMS
        </button>
    </div>

    {{-- Table Card --}}
    <div class="premium-card rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center">
            <h3 class="text-sm font-bold font-title text-white">Incoming Unmapped Queue</h3>
            <span class="bg-rose-500/10 border border-rose-500/30 text-rose-300 px-3 py-1 rounded-full text-[10px] font-bold">
                {{ $transactions->where('status', 'unmapped')->count() }} Action Pending
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                    <tr>
                        <th class="px-6 py-4">Received Date</th>
                        <th class="px-6 py-4">Sender Info</th>
                        <th class="px-6 py-4">Transaction Code</th>
                        <th class="px-6 py-4">Parsed Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($transactions ?? [] as $tx)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-400">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-white">{{ $tx->sender ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gold-500">{{ $tx->transaction_code ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-white">
                            Ksh {{ number_format($tx->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = $tx->status === 'mapped' ? 'text-brand-emerald bg-brand-emerald/10 border-brand-emerald/20' : 
                                               ($tx->status === 'rejected' ? 'text-brand-rose bg-brand-rose/10 border-brand-rose/20' : 'text-gold-500 bg-gold-500/10 border-gold-500/20');
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            @if($tx->status === 'unmapped')
                                <button onclick="matchTransaction({{ $tx->id }}, '{{ $tx->transaction_code }}', {{ $tx->amount }}, '{{ addslashes($tx->sender) }}')" class="bg-brand-emerald text-brand-navy px-3 py-1.5 rounded-xl text-xs font-bold transition hover:opacity-90 shadow-md">Match Member</button>
                                <button onclick="rejectTransaction({{ $tx->id }})" class="bg-brand-rose text-white px-3 py-1.5 rounded-xl text-xs font-bold transition hover:opacity-90 shadow-md">Reject</button>
                            @else
                                <span class="text-slate-500 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            <span class="material-symbols-outlined text-3xl block mb-2 opacity-50">sms</span>
                            No unmapped transaction history.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@include('partials.sms-modal')

{{-- Match Member Modal --}}
<div id="matchModal" class="fixed inset-0 bg-brand-navy/60 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-300">
    <div class="premium-card rounded-2xl max-w-md w-full mx-4 border border-white/10 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
        
        <div class="flex items-center justify-between p-6 border-b border-white/5">
            <h3 class="text-lg font-bold font-title text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-gold-500">person_add</span> Map M-Pesa Transaction
            </h3>
            <button onclick="closeMatchModal()" class="text-slate-400 hover:text-white text-2xl font-bold transition">&times;</button>
        </div>
        
        <div class="p-6 space-y-4">
            <div class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-2 text-xs text-slate-300">
                <div class="flex justify-between items-center py-1 border-b border-white/5">
                    <span class="text-slate-500 font-medium">Transaction Code</span> 
                    <span class="font-mono text-gold-500 font-bold" id="matchTxCode"></span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-white/5">
                    <span class="text-slate-500 font-medium">Extracted Amount</span> 
                    <span class="font-bold text-white text-sm" id="matchTxAmount"></span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-slate-500 font-medium">M-Pesa Sender</span> 
                    <span class="text-slate-300 font-medium" id="matchTxSender"></span>
                </div>
            </div>

            <div>
                <label for="matchMemberId" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select Group Member</label>
                <select id="matchMemberId" class="w-full bg-brand-dark border border-white/10 rounded-xl p-3 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all">
                    <option value="">-- Choose Member --</option>
                    @foreach($members ?? [] as $member)
                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button onclick="submitMatch()" class="bg-brand-emerald hover:opacity-90 text-brand-navy font-bold py-3 px-6 rounded-xl transition flex-1 text-sm shadow-md flex items-center justify-center gap-1">
                    Confirm Mapping
                </button>
                <button onclick="closeMatchModal()" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold py-3 px-6 rounded-xl transition text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentMatchTxId = null;

    function matchTransaction(id, code, amount, sender) {
        currentMatchTxId = id;
        document.getElementById('matchTxCode').innerText = code;
        document.getElementById('matchTxAmount').innerText = 'Ksh ' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
        document.getElementById('matchTxSender').innerText = sender;
        document.getElementById('matchMemberId').value = '';
        
        document.getElementById('matchModal').classList.remove('hidden');
        document.getElementById('matchModal').classList.add('flex');
    }

    function closeMatchModal() {
        document.getElementById('matchModal').classList.add('hidden');
        document.getElementById('matchModal').classList.remove('flex');
        currentMatchTxId = null;
    }

    function submitMatch() {
        const memberId = document.getElementById('matchMemberId').value;
        if (!memberId) {
            alert('Please select a member first.');
            return;
        }
        
        fetch(`/treasurer/sms-parser/${currentMatchTxId}/match`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ user_id: memberId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Transaction mapped successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => alert('Network error.'));
    }

    function rejectTransaction(id) {
        if (confirm('Are you sure you want to reject this incoming unmapped transaction record?')) {
            fetch(`/treasurer/sms-parser/${id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Transaction rejected.');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Network error.'));
        }
    }
</script>
@endpush
@endsection