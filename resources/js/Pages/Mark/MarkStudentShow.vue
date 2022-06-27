<template>
    <Head title="Student Result" />

    <BreezeAuthenticatedLayout>
        <template #header> Student Result </template>

        <div
            class="p-3 shadow-md rounded-md mx-auto md:w-4/5 w sm:w-11/12 w-full bg-white"
        >
            <form @submit.prevent="findResult">
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Department" />
                    <select
                        @change="getBatches"
                        id="sessionId"
                        v-model="form.department"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="" disabled>
                            Choose a Department
                        </option>
                        <option
                            class="text-black"
                            v-for="(dpt, i) in departments"
                            :key="i"
                            :value="dpt.id"
                        >
                            {{ dpt.name }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.department">
                        {{ form.errors.department }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Batch" />
                    <select
                    @change="studentInfoByBatch"
                        id="sessionId"
                        v-model="form.batch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="" disabled>
                            Choose a Batch
                        </option>
                        <option
                            class="text-black"
                            v-for="(batch, i) in batches"
                            :key="i"
                            :value="batch.id"
                        >
                            {{ batch.name }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.batch">
                        {{ form.errors.batch }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Roll" />
                    <select
                        id="sessionId"
                        v-model="form.student"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="" disabled>
                            Choose a Roll
                        </option>
                        <option
                            class="text-black"
                            v-for="(student, i) in students"
                            :key="i"
                            :value="student.id"
                        >
                            {{ student.roll }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.roll">
                        {{ form.errors.roll }}
                    </h2>
                </div>
                 <div class="my-4">
                    <BreezeLabel for="sessionId" value="Course" />
                    <select
                        id="sessionId"
                        v-model="form.course"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="" disabled>
                            Choose a Course
                        </option>
                        <option
                            class="text-black"
                            v-for="(course, i) in courses"
                            :key="i"
                            :value="course.id"
                        >
                            {{ course.c_name }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.course">
                        {{ form.errors.course }}
                    </h2>
                </div>
                <BreezeButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Find
                </BreezeButton>
            </form>
        </div>
        <div v-if="result" class="overflow-hidden my-8 w-full rounded-lg border shadow-xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"
                        >
                            <th class="px-4 py-3">*</th>
                            <th class="px-4 py-3">Course Name</th>
                            <th class="px-4 py-3">Course Code</th>
                            <th class="px-4 py-3">Co Name</th>
                            <th class="px-4 py-3">Po Name</th>
                        </tr>
                    </thead>
                    <!-- <tbody class="bg-white divide-y">
                        <tr
                            v-for="(assign, i) in assigns"
                            :key="assign.id"
                            class="text-gray-700"
                        >
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
                                {{
                                    assign.rel_po ? assign.rel_po.po_name : "-"
                                }}
                            </td>
                        </tr>
                    </tbody> -->
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
import BreezeLabel from "@/Components/Label.vue";
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
    props: ["departments",'courses'],
    data() {
        return {
            batches: "",
            students: "",
            result:"",
            form: this.$inertia.form({
                department: "",
                batch:"",
                student:"",
                course:""
            }),
        };
    },
    methods: {
        getBatches() {
            axios
                .get(this.route("users.studentBatchInfo",this.form.department))
                .then((res) =>this.batches=res.data)
                .catch((err)=>console.log(err));
        },
        studentInfoByBatch() {
            axios
                .get(this.route("users.studentInfoByBatch",this.form.batch))
                .then((res) =>this.students=res.data)
                .catch((err)=>console.log(err));
        },
        findResult() {
            axios
                .get(this.route("exam.markStudentShow",[this.form.department,this.form.batch,this.form.student,this.form.course]))
                .then((res) =>this.result=res.data)
                .catch((err)=>console.log(err));
        },
    },
};
</script>
