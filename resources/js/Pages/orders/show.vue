<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    defineProps({
        order: {
            required: true,
            default: []
        },
        transactions: {
            required: true,
            default: []
        },
        title: {
            required: true,
            default: 'Page Plural Name'
        }
    })
</script>

<template>
    <AppLayout :title="title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="overflow-hidden px-4">
                                    <div>
                                        <strong>Tracking Number:</strong> {{ order.details }}
                                    </div>
                                    <div>
                                        <strong>Order Status:</strong> {{ order.status }}
                                    </div>
                                    <div>
                                        <strong>Order Amount:</strong> {{ order.order_total }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full" style="width:100%;">
                                        <thead class="border-b">
                                            <tr>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Trasaction ID
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Order
                                                </th>

                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Payment By
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Amount
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Transaction Date
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="!transactions.length">
                                                <td colspan="6" class="text-center">
                                                    <span class="text-muted">
                                                        {{ "No Data Found" }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="border-b" v-for="(item,index) in transactions" :key="index">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.transaction_number}}
                                                </td>

                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{(order.details)}}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.payment_by}}
                                                </td>

                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.amount}}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.created_at}}
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
