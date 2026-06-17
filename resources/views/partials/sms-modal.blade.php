<div id="smsModal" class="fixed inset-0 bg-brand-navy/60 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-300" x-data="smsParserModal()" x-init="init()">
    <div class="premium-card rounded-2xl max-w-xl w-full mx-4 border border-white/10 shadow-2xl relative overflow-hidden" @click.outside="closeModal()">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
        
        <div class="flex items-center justify-between p-6 border-b border-white/5">
            <h3 class="text-lg font-bold font-title text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-gold-500">sms</span> Parse M-Pesa SMS
            </h3>
            <button @click="closeModal()" class="text-slate-400 hover:text-white text-2xl font-bold transition">&times;</button>
        </div>
        
        <div class="p-6">
            <div x-show="!parsed">
                <p class="text-xs text-slate-400 mb-3">Paste a raw transaction notification message from Safaricom M-Pesa below:</p>
                <textarea x-model="smsText" 
                    class="w-full bg-white/5 border border-white/10 rounded-xl p-4 h-32 focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 outline-none text-white text-sm transition-all" 
                    placeholder="Paste text like: KQA2B3C4D5 Confirmed. Ksh 5,000.00 received from JOHN DOE 0712345678 on 2026-06-15..."></textarea>
                
                <button @click="parseSms()" class="mt-4 w-full py-3 bg-gradient-to-r from-gold-500 to-gold-700 hover:opacity-95 text-brand-navy font-bold rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2" :disabled="loading">
                    <span x-show="!loading" class="flex items-center gap-1"><span class="material-symbols-outlined text-sm font-bold">search</span> Extract Transaction Details</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-brand-navy" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Parsing SMS payload...
                    </span>
                </button>
            </div>
            
            <div x-show="parsed" x-cloak class="space-y-4">
                <h4 class="font-bold text-sm text-white font-title flex items-center gap-1">
                    <span class="material-symbols-outlined text-brand-emerald">check_circle</span> Isolated Transaction Metadata
                </h4>
                
                <div class="bg-white/5 rounded-xl p-4 border border-white/5 space-y-3 text-xs text-slate-300">
                    <div class="flex justify-between items-center py-1 border-b border-white/5">
                        <span class="text-slate-500 font-medium">Extracted Amount</span> 
                        <span class="font-bold text-white text-sm" x-text="'Ksh ' + parsedData.amount"></span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-white/5">
                        <span class="text-slate-500 font-medium">Source Sender</span> 
                        <span class="text-slate-300 font-medium" x-text="parsedData.sender || '—'"></span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-white/5">
                        <span class="text-slate-500 font-medium">Reference Code</span> 
                        <span class="font-mono text-gold-500" x-text="parsedData.transaction_code || '—'"></span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500 font-medium">Transaction Date</span> 
                        <span class="text-slate-400" x-text="parsedData.date || '—'"></span>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button @click="confirmRecord()" class="bg-brand-emerald hover:opacity-90 text-brand-navy font-bold py-3 px-6 rounded-xl transition flex-1 text-sm shadow-md flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm font-bold">check</span> Confirm &amp; Save
                    </button>
                    <button @click="parsed = false; parsedData = {}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold py-3 px-6 rounded-xl transition text-sm">
                        Back
                    </button>
                </div>
                <p class="text-[10px] text-slate-500 text-center mt-2">If unmatched, the transaction goes to holding queue for Treasurer mapping.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function smsParserModal() {
        return {
            smsText: '',
            loading: false,
            parsed: false,
            parsedData: {},
            init() {
                window.openSmsModal = () => {
                    document.getElementById('smsModal').classList.remove('hidden');
                    document.getElementById('smsModal').classList.add('flex');
                };
            },
            closeModal() {
                document.getElementById('smsModal').classList.add('hidden');
                document.getElementById('smsModal').classList.remove('flex');
                this.smsText = '';
                this.parsed = false;
                this.parsedData = {};
            },
            parseSms() {
                if (!this.smsText.trim()) {
                    alert('Please paste an SMS message.');
                    return;
                }
                this.loading = true;
                fetch('{{ route("treasurer.sms-parser.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: this.smsText })
                })
                .then(response => response.json())
                .then(data => {
                    this.loading = false;
                    if (data.success) {
                        this.parsedData = data.data;
                        this.parsed = true;
                    } else {
                        alert('Error parsing SMS: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    this.loading = false;
                    alert('Network error. Please try again.');
                    console.error(error);
                });
            },
            confirmRecord() {
                alert('M-Pesa SMS recorded. View details in SMS Parser page.');
                this.closeModal();
                window.location.reload();
            }
        }
    }
</script>
@endpush