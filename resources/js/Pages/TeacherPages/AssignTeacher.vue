<template>
    <Head title="Assign Teacher" />

    <BreezeAuthenticatedLayout>
        <template #header> My Courses </template>

        <!-- <form @submit.prevent="assignTeacher">
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
        </form> -->
        <div
            class="overflow-hidden my-8 w-full rounded-lg border shadow-xs mb-4"
        >
            <h2 class="capitalize text-xl font-weight-bold m-2">
                Running Courses
            </h2>
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"
                        >
                            <th class="px-4 py-3">*</th>
                            <th class="px-4 py-3">Course Name</th>
                            <th class="px-4 py-3">Course Code</th>
                            <th class="px-4 py-3">Batch</th>
                            <th class="px-4 py-3">Semester</th>
                            <th class="px-4 py-3">Exam Assign</th>
                            <th class="px-4 py-3">Satisfy</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <tr
                            v-for="(run, i) in running.data"
                            :key="run.id"
                            class="text-gray-700"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ i + 1 }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ run.rel_course.c_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ run.rel_course.c_code }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ run.rel_batch.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ run.rel_semester.name }}
                            </td>
                             <td class="px-4 py-3 text-sm">
                             {{ run.rel_exam_count }}
                             </td>
                            <Link
                                :href="route('course.teacher.makeExam',run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-green-500 text-white"
                            >
                                Exam Assign
                            </Link>
                             <Link
                                :href="route('exam.markCreate',run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-indigo-500 text-white"
                            >
                                Assign Marks
                            </Link>
                        </tr>
                    </tbody>
                </table>
                <div
                    class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9"
                >
                    <pagination :links="running.links" />
                </div>
            </div>
        </div>
        <div class="overflow-hidden my-8 w-full rounded-lg border shadow-xs">
            <h2 class="capitalize text-xl font-weight-bold m-2">
                Completed Courses
            </h2>
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"
                        >
                            <th class="px-4 py-3">*</th>
                            <th class="px-4 py-3">Course Name</th>
                            <th class="px-4 py-3">Course Code</th>
                            <th class="px-4 py-3">Batch</th>
                            <th class="px-4 py-3">Semester</th>
                             <th class="px-4 py-3">Exam Assign</th>
                            <th class="px-4 py-3">Satisfy By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <tr
                            v-for="(comp, i) in completed.data"
                            :key="comp.id"
                            class="text-gray-700"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ i + 1 }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ comp.rel_course.c_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ comp.rel_course.c_code }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ comp.rel_batch.name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ comp.rel_semester.name }}
                            </td>
                             <td class="px-4 py-3 text-sm">
                             {{ comp.rel_exam_count }}
                             </td>
                            <Link
                                :href="route('course.assignTeacherCreate')"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-green-500 text-white"
                            >
                                Exam List
                            </Link>

                        </tr>
                    </tbody>
                </table>
                <div
                    class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9"
                >
                    <pagination :links="completed.links" />
                </div>
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
    props: ["completed", "running"],
    data() {
        // return {
        //     form: this.$inertia.form({ co: "", po: "", "course": this.course.id }),
        // };
    },
    methods: {
        // assignTeacher() {
        //     this.form.post(this.route("course.assignCoPo"), { onSuccess: () => this.form.reset(), });
        // }
    },
};
</script>
