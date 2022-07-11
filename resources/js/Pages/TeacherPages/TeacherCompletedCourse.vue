<template>
    <Head title="Completed Teacher" />

    <BreezeAuthenticatedLayout>
        <template #header> My Courses </template>
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
                            <Link
                                :href="route('exam.markCreate', comp.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-indigo-500 text-white"
                            >
                                Mark Show
                            </Link>
                            <Link
                                :href="route('exam.markBatchShow', comp.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-blue-500 text-white"
                            >
                                Batch Result
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
    props: ["completed"],
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
