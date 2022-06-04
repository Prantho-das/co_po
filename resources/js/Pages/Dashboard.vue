<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header> Dashboard </template>
        <div class="flex flex-wrap justify-between flex-shrink-1 gap-y-3 gap-x-1">
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
            <div class="p-4 bg-white rounded-lg shadow-xs">
                You're logged in!
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },
};
</script>
