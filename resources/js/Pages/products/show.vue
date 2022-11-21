<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    defineProps({
        product: {
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
                                        <strong>Product name:</strong> {{ product.name }}
                                    </div>
                                    <div>
                                        <strong>Product Slug:</strong> {{ product.slug }}
                                    </div>
                                    <div>
                                        <strong>Product Sku:</strong> {{ product.sku }}
                                    </div>
                                    <div>
                                        <strong>Product Price:</strong> {{ product.formatted_price }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
