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
    const isUpdate = ref(null);
    const isDelete = ref(null);
    const open_edit_add_modal = ref(false);
    const confirming_deletion = ref(false);
    const order_total = ref(null);
    const order_items = ref([]);

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const openCreateModal = () => {
        open_edit_add_modal.value = true;
    };

    const closeModal = () => {
        order_items.value =  null,
        open_edit_add_modal.value = false;
        form.reset();
        form.clearErrors();
        isUpdate.value = null;
    }

const orderDetails = (item) => {
        order_total.value = item.order_total;
        order_items.value =  item.order_items
        openCreateModal();
    };

    const submit = () => {
        form.post(route('users.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: (err) => form.setError(err),
            onFinish: (res) => form.reset(res),
        });
    };
    const update = () => {
        form.put(route('users.update', isUpdate.value), {
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

    const deleteItem = () => {
        form.delete(route('users.destroy', isDelete.value), {
            preserveScroll: true,
            onSuccess: () => closeDeleteModal(),
            onError: () => console.log('Error'),
            onFinish: () => form.reset(),
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
                                                    Details
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
                                                    {{item.details}}
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
                                                    <select name="" id="" v-model="item.status">
                                                        <option value="unpaid">Un Paid</option>
                                                        <option value="paid">Paid</option>
                                                    </select>
                                                </td>

                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    <PrimaryButton @click="orderDetails(item)"> Order Details
                                                    </PrimaryButton>
                                                    <!-- <DangerButton @click="confirmDeletion(item)"> Delete </DangerButton> -->
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
                <SecondaryButton @click="closeModal">
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
