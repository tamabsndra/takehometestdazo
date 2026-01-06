<x-dashboard-layout>
    <x-slot:title>Point of Sale</x-slot:title>

    <div x-data="posSystem()" x-init="init()" class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-6">
        
        <!-- Left Column: Products -->
        <div class="flex-1 flex flex-col min-w-0 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Search Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Search products..." 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out"
                    >
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-100">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button 
                            @click="addToCart(product)"
                            class="group relative bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 p-4 text-left flex flex-col h-full focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 line-clamp-2" x-text="product.name"></h3>
                                <p class="text-xs text-gray-500 mt-1" x-text="product.sku || 'No SKU'"></p>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="font-bold text-primary-600" x-text="formatPrice(product.price)"></p>
                                <div class="bg-primary-50 text-primary-600 p-1.5 rounded-lg group-hover:bg-primary-600 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>
                
                <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-full text-gray-500" x-cloak>
                    <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p>No products found</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Cart -->
        <div class="w-full md:w-96 flex flex-col bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Current Order
                </h2>
                <button 
                    @click="clearCart" 
                    class="text-xs text-red-600 hover:text-red-700 font-medium"
                    x-show="cart.length > 0"
                >
                    Clear All
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg group animate-fade-in">
                        <div class="flex-1 min-w-0 pr-3">
                            <h4 class="font-medium text-gray-900 truncate" x-text="item.name"></h4>
                            <p class="text-xs text-primary-600 font-medium" x-text="formatPrice(item.price)"></p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                             <button @click="updateQty(index, -1)" class="p-1 rounded-md hover:bg-white text-gray-500 hover:text-danger focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <span class="w-6 text-center text-sm font-semibold text-gray-900" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="p-1 rounded-md hover:bg-white text-gray-500 hover:text-primary-600 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-40 text-gray-400" x-cloak>
                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="text-sm">Order is empty</p>
                </div>
            </div>

            <!-- Footer & Checkout -->
            <div class="p-4 bg-gray-50 border-t border-gray-200 space-y-3">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-900" x-text="formatPrice(total)"></span>
                </div>
                <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                    <span>Total</span>
                    <span class="text-green-600" x-text="formatPrice(total)"></span>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4">
                     <button 
                        @click="processPayment('cash')"
                        :disabled="cart.length === 0 || processing"
                        class="flex flex-col items-center justify-center py-3 border rounded-lg transition-all duration-200"
                        :class="paymentMethod === 'cash' 
                            ? 'bg-primary-50 border-primary-500 text-primary-700 ring-1 ring-primary-500 shadow-sm' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hover:border-gray-400'"
                    >
                        <svg x-show="paymentMethod === 'cash'" class="w-4 h-4 mb-1 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-xs font-bold uppercase" :class="paymentMethod === 'cash' ? 'text-primary-700' : 'text-gray-500'">CASH</span>
                    </button>
                    <button 
                        @click="processPayment('qris')"
                        :disabled="cart.length === 0 || processing"
                        class="flex flex-col items-center justify-center py-3 border rounded-lg transition-all duration-200"
                        :class="paymentMethod === 'qris' 
                            ? 'bg-primary-50 border-primary-500 text-primary-700 ring-1 ring-primary-500 shadow-sm' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hover:border-gray-400'"
                    >
                        <svg x-show="paymentMethod === 'qris'" class="w-4 h-4 mb-1 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-xs font-bold uppercase" :class="paymentMethod === 'qris' ? 'text-primary-700' : 'text-gray-500'">QRIS</span>
                    </button>
                </div>
                
                <button 
                    @click="checkout"
                    :disabled="cart.length === 0 || processing"
                    class="w-full btn-primary py-3 flex justify-center items-center mt-3 shadow-md hover:shadow-lg transform transition active:scale-95"
                >
                    <span x-show="!processing" class="flex items-center text-base font-bold">
                         Complete Order
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                    <span x-show="processing" x-cloak>
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>

        <!-- Toast Notification -->
        <div 
            x-show="notification.show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="fixed bottom-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
            x-cloak
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg x-show="notification.type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="notification.type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="notification.show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <x-confirmation-modal 
            show="confirmModal.show" 
            alpineTitle="confirmModal.title"
            message="confirmModal.message"
            onClose="confirmModal.show = false"
        >
            <x-slot:footer>
                <button 
                    @click="confirmModal.onConfirm()"
                    type="button" 
                    class="btn-danger w-full sm:w-auto"
                >
                    Confirm
                </button>
                <button 
                    @click="confirmModal.show = false"
                    type="button" 
                    class="btn-secondary w-full sm:w-auto mt-3 sm:mt-0"
                >
                    Cancel
                </button>
            </x-slot:footer>
        </x-confirmation-modal>
    </div>
    </div>

    <script>
        function posSystem() {
            return {
                products: @json($products),
                search: '',
                cart: [],
                paymentMethod: 'cash',
                processing: false,
                notification: {
                    show: false,
                    type: 'success',
                    title: '',
                    message: ''
                },
                confirmModal: {
                    show: false,
                    title: '',
                    message: '',
                    onConfirm: null
                },

                init() {
                    // Logic to load cart from local storage could go here
                },

                get filteredProducts() {
                    if (this.search === '') {
                        return this.products;
                    }
                    return this.products.filter(product => {
                        return product.name.toLowerCase().includes(this.search.toLowerCase()) || 
                               (product.sku && product.sku.toLowerCase().includes(this.search.toLowerCase()));
                    });
                },

                get total() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            qty: 1
                        });
                    }
                },

                updateQty(index, change) {
                    if (this.cart[index].qty + change <= 0) {
                        this.cart.splice(index, 1);
                    } else {
                        this.cart[index].qty += change;
                    }
                },

                clearCart() {
                    this.showConfirm('Clear Order', 'Are you sure you want to clear the current order?', () => {
                        this.cart = [];
                    });
                },

                showConfirm(title, message, callback) {
                    this.confirmModal = {
                        show: true,
                        title: title,
                        message: message,
                        onConfirm: () => {
                            callback();
                            this.confirmModal.show = false;
                        }
                    };
                },

                formatPrice(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                },
                
                processPayment(method) {
                     this.paymentMethod = method;
                },

                showNotification(type, title, message) {
                    this.notification = {
                        show: true,
                        type: type, // 'success' or 'error'
                        title: title,
                        message: message
                    };
                    
                    // Auto hide after 3 seconds
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 4000);
                },

                checkout() {
                    if (this.cart.length === 0) return;
                    
                    this.processing = true;

                    // Send to backend
                    fetch('{{ route('pos.store') }}', { // Assuming route pos.store exists
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: this.cart.map(item => ({
                                product_id: item.id,
                                quantity: item.qty
                            })),
                            payment_method: this.paymentMethod
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            this.showNotification('success', 'Order Completed', 'Redirecting to receipt...');
                            
                            setTimeout(() => {
                                window.location.href = `/sales/${data.sale_id}`;
                            }, 1000);
                        } else {
                            this.showNotification('error', 'Transaction Failed', data.message || 'Unknown error');
                            this.processing = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showNotification('error', 'System Error', 'An error occurred. Please try again.');
                        this.processing = false;
                    });
                }
            }
        }
    </script>
</x-dashboard-layout>
