<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import FormSection from '@/Components/FormSection.vue';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import ActionSection from '@/Components/ActionSection.vue';
    import DialogModal from '@/Components/DialogModal.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import {
        ref
    } from '@vue/reactivity';
    import {
        useForm
    } from '@inertiajs/inertia-vue3';

    defineProps({
        transactions: {
            required: true,
            default: []
        },
        orders: {
            required: true,
            default: []
        },
        title: {
            required: true,
            default: 'Page Plural Name'
        },
        singular_title: {
            required: true,
            default: 'Page Singular Name'
        },

    })
    const form = useForm({
        order_id: '',
        status: 'paid',
        payment_by: '',
    })
    const is_open = ref(false);

    const openCreateModal = () => {
        is_open.value = true;
    }
    const closeCreateModal = () => {
        is_open.value = false;
    }
    const submit = () => {
        form.post(route('transaction.store'), {
            preserveScroll: true,
            onSuccess: () => closeCreateModal(),
            onError: (err) => form.setError(err),
            onFinish: (res) => form.reset(res),
        });
    }

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
                <ResponsiveNavLink class="my-5" as='button' @click="openCreateModal" :active="true">
                    Create
                    {{ singular_title ?? "" }}
                </ResponsiveNavLink>
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
                                                    {{item.order.details}}
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
        <DialogModal :show="is_open">
            <template #title>
                Create Transaction
            </template>

            <template #content>
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Select Un Paid Orders" />
                        <select v-model="form.order_id" required id="countries_disabled"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Select Un Paid Orders</option>
                            <option :value="order.id" v-for="(order,index) in orders" :key="index">{{order.id }} -
                                {{ order.details }} - {{ order.order_total }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="payment_by" value="Payment by" />
                        <select v-model="form.payment_by" required id="countries_disabled"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="Paypal">Paypal</option>
                            <option value="Stripe">Stripe</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.payment_by" />
                    </div>
                    <div>
                        <InputLabel for="status" value="Status" />
                        <select :disabled="true" v-model="form.status" required id="countries_disabled"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="unpaid">Un Paid</option>
                            <option value="paid">Paid</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.status" />
                    </div>


                    <div class="flex justify-end mt-4">
                        <SecondaryButton @click="closeCreateModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Create transaction
                        </PrimaryButton>
                    </div>
                </form>
            </template>
        </DialogModal>
    </AppLayout>
</template>
