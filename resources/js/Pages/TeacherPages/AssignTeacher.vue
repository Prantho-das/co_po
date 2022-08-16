<template>
    <Head title="Assign Teacher" />

    <BreezeAuthenticatedLayout>
        <template #header> My Courses </template>

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
                                :href="route('course.teacher.makeExam', run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-green-500 text-white"
                            >
                                Exam Assign
                            </Link>
                            <button
                               @click="courseComplete(run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-teal-500 text-white"
                            >
                                Course Complete
                            </button>
                            <Link
                                v-if="run.rel_exam_count > 0"
                                :href="route('exam.markCreate', run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-indigo-500 text-white"
                            >
                                Assign Marks
                            </Link>
                            <Link
                                :href="route('exam.markBatchShow', run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-blue-500 text-white"
                            >
                                Batch Result
                            </Link>
                            <Link
                             v-if="run.mark_assign_done"
                                :href="route('exam.markEdit', run.id)"
                                class="inline-block m-2 text-md font-weight-bold p-2 rounded bg-pink-500 text-white"
                            >
                                Mark Update
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
    props: ["running"],
    data() {
        // return {
        //     form: this.$inertia.form({ co: "", po: "", "course": this.course.id }),
        // };
    },
    methods: {
        courseComplete(id) {
            if(!confirm('Are You Sure?')) return false;
            this.$inertia.post(this.route('course.teacher.completeCourse', id),{
                onSuccess:(res)=>console.log(res)
            })
        }
    },
};
</script>
