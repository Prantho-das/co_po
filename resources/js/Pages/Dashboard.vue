<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header> Dashboard </template>
        <div
            class="flex flex-wrap justify-between flex-shrink-1 gap-y-3 gap-x-4"
        >
            <div
                class="p-6 bg-white rounded-lg shadow-xs flex-1 w-52 text-center"
            >
                <h2 class="text-2xl text-blue-500">Total Student</h2>
                <h6 class="text-3xl mt-3 font-semibold">{{ student }}</h6>
            </div>
            <div
                class="p-6 bg-white rounded-lg shadow-xs flex-1 w-52 text-center"
            >
                <h2 class="text-2xl text-blue-500">Total Teacher</h2>
                <h6 class="text-3xl mt-3 font-semibold">{{ teacher }}</h6>
            </div>
            <div
                class="p-6 bg-white rounded-lg shadow-xs flex-1 w-52 text-center"
            >
                <h2 class="text-2xl text-blue-500">Total Course</h2>
                <h6 class="text-3xl mt-3 font-semibold">{{ course }}</h6>
            </div>
            <div
                class="p-6 bg-white rounded-lg shadow-xs flex-1 w-52 text-center"
            >
                <h2 class="text-2xl text-blue-500">Total Batch</h2>
                <h6 class="text-3xl mt-3 font-semibold">{{ batch }}</h6>
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
    props: ["course", "student", "teacher", "batch"],
};
</script>
