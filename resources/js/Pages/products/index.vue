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
        products: {
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

    const form = useForm({
        'name': '',
        'sku': '',
        'price': '',
    });

    const openCreateModal = () => {
        open_edit_add_modal.value = true;
    };

    const closeModal = () => {
        open_edit_add_modal.value = false;

        form.reset();
        form.clearErrors();
        isUpdate.value = null;
    }

    const edit = (item) => {
        isUpdate.value = item.id,
            form.name = item.name;
        form.sku = item.sku;
        form.price = item.price;
        openCreateModal();
    };

    const submit = () => {
        form.post(route('products.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: (err) => form.setError(err),
            onFinish: (res) => form.reset(res),
        });
    };
    const update = () => {
        form.put(route('products.update', isUpdate.value), {
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
        form.delete(route('products.destroy', isDelete.value), {
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
                <ResponsiveNavLink  class="my-5" as='button' @click="openCreateModal" :active="true">Create
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
                                                    Name
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Slug
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Sku
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Price
                                                </th>
                                                <th scope="col"
                                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="!products.length">
                                                <td colspan="6" class="text-center">
                                                    <span class="text-muted">
                                                        {{ "No Data Found" }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="border-b" v-for="(item,index) in products" :key="index">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <NavLink :href="route('products.show',item.id)">
                                                        {{ item.name }}
                                                    </NavLink>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{item.slug}}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{item.sku}}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    {{item.formatted_price}}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                    <PrimaryButton  @click="edit(item)"> Edit </PrimaryButton> |
                                                    <DangerButton  @click="confirmDeletion(item)"> Delete </DangerButton>
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
                {{isUpdate ? 'Update Product':'Create Product'}}
            </template>

            <template #content>
                <form @submit.prevent="isUpdate ? update() : submit()">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" ref="nameInput" v-model="form.name" type="text" class="mt-1 block w-full"
                            autocomplete="current-name" autofocus required />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="sku" value="Sku" />
                        <TextInput id="sku" ref="skuInput" v-model="form.sku" type="text" class="mt-1 block w-full"
                            autocomplete="current-sku" autofocus required />
                        <InputError class="mt-2" :message="form.errors.sku" />
                    </div>
                    <div>
                        <InputLabel for="price" value="Price" />
                        <TextInput id="price" ref="priceInput" v-model="form.price" type="number"
                            class="mt-1 block w-full" autocomplete="current-price" autofocus required />
                        <InputError class="mt-2" :message="form.errors.price" />
                    </div>
                    <div class="flex justify-end mt-4">
                        <SecondaryButton @click="closeModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            {{isUpdate ? 'Update Product':'Save Product'}}
                        </PrimaryButton>
                    </div>
                </form>
            </template>
        </DialogModal>
        <DialogModal :show="confirming_deletion" @close="closeDeleteModal">
            <template #title>
                Delete {{ singular_title }}
            </template>

            <template #content>
                Are you sure you want to delete {{ singular_title.toLocaleLowerCase() }}? Once your {{ singular_title.toLocaleLowerCase() }} is deleted
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
