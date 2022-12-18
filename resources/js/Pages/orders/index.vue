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
    import NavLink from '@/Components/NavLink.vue';
    import {
        ref
    } from '@vue/reactivity';
    import {
        useForm
    } from '@inertiajs/inertia-vue3';

    defineProps({
        orders: {
            required: true,
            default: []
        },
        products: {
            required: true,
            default: []
        },
        users: {
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
    const isUpdate = ref(null);
    const isDelete = ref(null);
    const open_edit_add_modal = ref(false);
    const confirming_deletion = ref(false);
    const order_total = ref(null);
    const order_items = ref([]);
    const get_total_price = ref(null);
    const order_detail_modal = ref(null);

    const form = useForm({
        user_id: '',
        shipping_address: '',
        products: '',
        status: '',
    });

    const openCreateModal = () => {
        open_edit_add_modal.value = true;
    };

    const closeModal = () => {
        order_items.value = null,
            open_edit_add_modal.value = false;
        form.reset();
        form.clearErrors();
        isUpdate.value = null;
    }

    const orderDetails = (item) => {
        order_total.value = item.order_total;
        order_items.value = item.order_items
        order_detail_modal.value = true;
    };
    const closeOrderDetailsModal = () => {
        order_total.value = null;
        order_items.value = null
        order_detail_modal.value = false;
    };

    const edit = (item) => {
        isUpdate.value = item.id
        order_total.value = item.order_total;
        order_items.value = item.order_items;
        form.user_id = item.user_id;
        form.products = item.order_items.map(x => x.product);
        totalAmount(form.products);
        form.shipping_address = item.shipping_address;
        form.status = item.status;
        openCreateModal();
    };
    const totalAmount = (array) => {
        const amount = array.reduce(
            (partialSum, a) =>
            parseInt(partialSum) +
            (a && a.price ? parseInt(a.price) : 0),
            0
        )
        get_total_price.value = amount
    }
    const submit = () => {
        form.post(route('orders.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: (err) => form.setError(err),
            onFinish: (res) => form.reset(res),
        });
    };
    const update = () => {
        form.put(route('orders.update', isUpdate.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: (err) => form.setError(err),
            onFinish: (response) => form.reset(response),
        });
    };

    const confirmDeletion = (product) => {
        isDelete.value = product.id
        confirming_deletion.value = true;

    };
    const closeDeleteModal = () => {
        isDelete.value = null
        confirming_deletion.value = false;
    }

    const getTotal = ($event) => {
        const pro = form.products;
        totalAmount(pro);
    }

    const deleteItem = () => {
        form.delete(route('orders.destroy', isDelete.value), {
            preserveScroll: true,
            onSuccess: () => closeDeleteModal(),
            onError: () => console.log('Error'),
            onFinish: () => form.reset(),
        });
    };
    const updateStatus = ($event, item) => {
        axios.put('order/update_status/' + item.id, {
            'status': $event.target.value
        }).then((res) => {
            console.log(res);
        }).catch(err => {
            console.log(err);
        });
    };

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
                <ResponsiveNavLink class="my-5" as='button' @click="openCreateModal" :active="true">Create
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
                                                    Tracking No
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Order By
                                                </th>

                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Shipping Address
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Total Amount
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Status
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="!orders.length">
                                                <td colspan="6" class="text-center">
                                                    <span class="text-muted">
                                                        {{ "No Data Found" }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="border-b" v-for="(item,index) in orders" :key="index">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <NavLink :href="route('orders.show',item.id)">
                                                        {{item.details}}
                                                    </NavLink>
                                                </td>

                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.user.name}}
                                                </td>

                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.shipping_address}}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.order_total}}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ item.status }}
                                                </td>

                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    <PrimaryButton :disabled="item.status == 'paid'"
                                                        @click="edit(item)"> Edit
                                                    </PrimaryButton> |

                                                    <PrimaryButton @click="orderDetails(item)"> Order Details
                                                    </PrimaryButton> |

                                                    <DangerButton @click="confirmDeletion(item)" :disabled="item.status == 'paid'"> Delete </DangerButton>
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
        <DialogModal :show="open_edit_add_modal">
            <template #title>
                {{isUpdate ? 'Update':'Create'}} {{ singular_title }}
            </template>

            <template #content>
                <form @submit.prevent="isUpdate ? update() : submit()">
                    <div>
                        <InputLabel for="name" value="User" />
                        <select v-model="form.user_id" required id="countries_disabled"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                            <option value="">Select User</option>
                            <option :value="user.id" v-for="(user,index) in users" :key="index">{{ user.name }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="shipping_address" value="Shipping Address" />
                        <TextInput id="shipping_address" ref="shipping_addressInput" v-model="form.shipping_address"
                            type="text" class="mt-1 block w-full" autocomplete="current-shipping_address" autofocus
                            required />
                        <InputError class="mt-2" :message="form.errors.shipping_address" />
                    </div>
                    <div>

                        <InputLabel for="products" value="products" />
                        <select multiple @change="getTotal" required id="countries_
                        const pro = form.products;multiple" v-model="form.products"
                            class="bg-gray-50 border border-gray-300 pro text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option :value="product" v-for="(product,index) in products" :key="index">
                                {{ product.name }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.products" />
                    </div>
                    <div>
                        <InputLabel for="status" value="Status" />
                        <select v-model="form.status" required id="countries_disabled"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="unpaid">Un Paid</option>
                            <option value="paid">Paid</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.status" />
                    </div>

                    <div class="flex justify-end mt-4">

                        <SecondaryButton @click="closeModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            {{isUpdate ? 'Update Order':'Save Order'}}
                        </PrimaryButton>
                    </div>
                    <div style="float:left;display: inline-block;">
                        Total Amount = {{get_total_price ?? '0.00'}}
                    </div>
                    <br />
                </form>
            </template>
            <template #footer>
                <span style="color:black;" v-if="form.status == 'paid'">Note: Order Will mark as paid and a entry wil be
                    added to the transaction</span>
            </template>
        </DialogModal>
        <DialogModal :show="order_detail_modal">
            <template #title>
                {{ singular_title }} Details
            </template>

            <template #content>
                <table class="min-w-full" style="width:100%;">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                OrderId
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Product Name
                            </th>

                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Quantity
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Price
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                SubTotal
                            </th>
                        </tr>
                    </thead>
                    <tfoot class="border-b">
                        <tr>
                            <th scope="col" colspan="4" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">

                            </th>

                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ order_total }}
                            </th>


                        </tr>
                    </tfoot>
                    <tbody>
                        <tr v-if="!orders.length">
                            <td colspan="6" class="text-center">
                                <span class="text-muted">
                                    {{ "No Data Found" }}
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b" v-for="(item,index) in order_items" :key="index">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ item.id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ item.product.name }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{item.quantity}}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{item.formatted_price}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{item.sub_total}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <template #footer>
                <SecondaryButton @click="closeOrderDetailsModal">
                    Cancel
                </SecondaryButton>
            </template>
        </DialogModal>
        <DialogModal :show="confirming_deletion" @close="closeDeleteModal">
            <template #title>
                Delete {{ singular_title }}
            </template>

            <template #content>
                Are you sure you want to delete {{ singular_title.toLocaleLowerCase() }}? Once your
                {{ singular_title.toLocaleLowerCase() }} is deleted
                data will be permanently deleted.
            </template>

            <template #footer>
                <SecondaryButton @click="closeDeleteModal">
                    Cancel
                </SecondaryButton>

                <DangerButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteItem">
                    Delete {{ singular_title }}
                </DangerButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
