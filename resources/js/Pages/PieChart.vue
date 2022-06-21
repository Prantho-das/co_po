<template>
    <Head title="Batch Co Po Result" />

    <BreezeAuthenticatedLayout>
        <template #header> Batch Co Po Result </template>
        <div
            class="flex flex-wrap justify-between flex-shrink-1 gap-y-3 gap-x-4"
        >
            <Pie
                v-for="(dt, i) in data"
                :key="i"
                :name="i"
                :co_name="dt.co_name"
                :po_name="dt.po_name"
                :below_40="dt.below_40"
                :below_80="dt['79-60']"
                :below_60="dt['59-40']"
                :above_80="dt['80']"
            />
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Pie from "@/Components/Pie.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,Pie
    },
    props: ['data'],
};
</script>
