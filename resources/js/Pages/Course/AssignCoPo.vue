<template>

    <Head title="Course Co Po Assign" />

    <BreezeAuthenticatedLayout>
        <template #header> Course Co Po Assign </template>

        <div class="p-3 shadow-md rounded mx-auto md:w-4/5 w sm:w-11/12 w-full bg-white">
            <h2 class="mb-2 text-xl text-green-600 font-bold">
                Course Name:{{ course.c_name }}
            </h2>
            <h2 class="mb-2 text-lg">Course Code: {{ course.c_code }}</h2>
            <h2 class="mb-2 text-md">Course Credit: {{ course.credit }}</h2>
            <h2 class="mb-2 text-md">Course Semester: {{ course.semester }}</h2>
        </div>
        <form @submit.prevent="assignCoPo">
            <div class="my-4">
                <BreezeLabel for="sessionId" value="Program Outcome" />
                <select id="sessionId" v-model="form.po"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected value="" disabled>
                        Choose a Program Outcome
                    </option>
                    <option class="text-black" v-for="(po, i) in pos" :key="i" :value="po.id">
                        {{ po.po_name }}
                    </option>
                    <h2 class="text-red-500" v-if="form.errors.po">
                        {{ form.errors.po }}
                    </h2>
                </select>
            </div>
            <div class="my-4">
                <BreezeLabel for="sessionId" value="Course OutCome" />
                <select id="sessionId" v-model="form.co"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected value="" disabled>
                        Choose a Course Outcome
                    </option>
                    <option class="text-black" v-for="(co, i) in cos" :key="i" :value="co.id">
                        {{ co.co_name }}
                    </option>
                    <h2 class="text-red-500" v-if="form.errors.co">
                        {{ form.errors.co }}
                    </h2>
                </select>
            </div>
            <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </BreezeButton>
        </form>
        <div class="overflow-hidden my-8 w-full rounded-lg border shadow-xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                            <th class="px-4 py-3">*</th>
                            <th class="px-4 py-3">Course Name</th>
                            <th class="px-4 py-3">Course Code</th>
                            <th class="px-4 py-3">Co Name</th>
                            <th class="px-4 py-3">Po Name</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <tr v-for="(assign, i) in assigns" :key="assign.id" class="text-gray-700">
                            <td class="px-4 py-3 text-sm">
                                {{ i + 1 }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ course.c_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ course.c_code }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ assign.rel_co.co_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ assign.rel_po.po_name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import BreezeLabel from '@/Components/Label.vue';
import { Link, Head } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Link,
        Head,
    },
    props: ["course", "cos", "pos", "assigns"],
    data() {
        return {
            form: this.$inertia.form({ co: "", po: "", "course": this.course.id }),
        };
    },
    methods: {
        assignCoPo() {
            this.form.post(this.route("course.assignCoPo"), { onSuccess: () => this.form.reset(), });
        }
    }
};
</script>
