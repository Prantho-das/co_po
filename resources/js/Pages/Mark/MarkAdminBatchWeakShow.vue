<template>
    <Head title="Batch Weak Result" />

    <BreezeAuthenticatedLayout>
        <template #header> Batch Weak Result </template>

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
                    <BreezeLabel for="sessionId" value="Semester" />
                    <select
                        @change="courseInfoBySemester"
                        id="sessionId"
                        v-model="form.semester"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="" disabled>
                            Choose a Semester
                        </option>
                        <option
                            class="text-black"
                            v-for="(semester, i) in semesters"
                            :key="i"
                            :value="semester.id"
                        >
                            {{ semester.name }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.semester">
                        {{ form.errors.semester }}
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
        <div
            v-if="weak"
            class="overflow-hidden my-8 w-full rounded-lg"
        >
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"
                        >
                            <th class="px-4 py-3">*</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Roll</th>
                            <th class="px-4 py-3">Co No</th>
                            <th class="px-4 py-3">Po No</th>
                            <th class="px-4 py-3">Marks</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Percentage</th>
                            <th class="px-4 py-3">Result Info</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <tr
                            v-for="(res, i) in weak"
                            :key="i"
                            class="text-gray-700"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ i + 1 }}
                            </td>
                             <td class="px-4 py-3 text-sm">
                                {{ res.name }}
                            </td>
                             <td class="px-4 py-3 text-sm">
                                {{ res.roll }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ res.rel_co.co_no }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ res.rel_po.po_no }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ res.sum_mark }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{
                                    res.total_mark
                                }}
                            </td>
                             <td class="px-4 py-3 text-sm">
                                {{
                                    res.percentage
                                }}
                            </td>
                            <td class="px-4 py-3 text-sm bg-gray-100">
                                <ul>
                                    <li v-for="info,i in res.marks" :key="i">
                                        <p class="text-bold">{{info.rel_exam.name}}:<span class="text-blue-600 ml-2">{{info.marks}}/{{info.total}}</span></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" class="px-2 py-2">
                                <button class="text-indigo-500 font-weight-bold" @click="downloadPdf">Downlaod Pdf</button>
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
import BreezeLabel from "@/Components/Label.vue";
import { Link, Head } from "@inertiajs/inertia-vue3";
import PieGoogle from "@/Components/PieGoogle.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Link,
        Head,
        PieGoogle
    },
    props: ["departments", "semesters"],
    data() {
        return {
            batches: "",
            students: "",
            courses: "",
            result: "",
            weak:"",
            form: this.$inertia.form({
                department: "",
                batch: "",
                semester: "",
                course: "",
            }),
        };
    },
    methods: {
        getBatches() {
            axios
                .get(this.route("users.studentBatchInfo", this.form.department))
                .then((res) => (this.batches = res.data))
                .catch((err) => console.log(err));
        },
        courseInfoBySemester() {
            axios
                .get(this.route("course.courseInSemester", this.form.semester))
                .then((res) => (this.courses = res.data))
                .catch((err) => console.log(err));
        },
        findResult() {
            axios
                .get(
                    this.route("exam.adminBatchPoorShow", [
                        this.form.batch,
                        this.form.course,
                    ])
                )
                .then((res) => {
                    this.result = res.data.result
                    this.weak=res.data.weak
                })
                .catch((err) => console.log(err));
        },
        downloadPdf() {
            var html = document.getElementById("pdf").innerHTML;
            let data = new FormData();
            data.append("teacherName", this.teacherAssigns.rel_teacher.name);
            data.append("batchName", this.teacherAssigns.rel_batch.name);
            data.append("courseName", this.teacherAssigns.rel_course.c_name);
            data.append("courseCode", this.teacherAssigns.rel_course.c_code);
            data.append("html", html);
            data.append("comment", this.comment);
            axios
                .post(this.route("exam.markBatchDownload"), data, {
                    responseType: "blob",
                })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "test.pdf");
                    document.body.appendChild(link);
                    link.click();
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                });
        },
    },
};
</script>
