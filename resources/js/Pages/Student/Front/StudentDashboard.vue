<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header> Dashboard </template>
        <h2>Notification</h2>
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
    // props: ["course", "student", "teacher", "batch"],
};
</script>
