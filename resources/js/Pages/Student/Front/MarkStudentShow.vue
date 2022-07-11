<template>
    <Head title="My Result" />

    <BreezeAuthenticatedLayout>
        <template #header> My Result </template>

        <div
            class="p-3 shadow-md rounded-md mx-auto md:w-4/5 w sm:w-11/12 w-full bg-white"
        >
            <form @submit.prevent="findResult">
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
                            <th class="px-4 py-3">Co Name</th>
                            <th class="px-4 py-3">Po Name</th>
                            <th class="px-4 py-3">Marks</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Resutl Info</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <tr
                            v-for="(res, i) in result"
                            :key="i"
                            class="text-gray-700"
                        >
                            <td class="px-4 py-3 text-sm">
                                {{ i + 1 }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ res.rel_co.co_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ res.rel_po.po_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ res.rel_marks_sum_marks }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{
                                    res.rel_marks_sum_total
                                }}
                            </td>
                            <td class="px-4 py-3 text-sm bg-gray-100">
                                <ul>
                                    <li v-for="info,i in res.rel_marks" :key="i">
                                        <p class="text-bold">{{info.rel_exam.name}}:<span class="text-blue-600 ml-2">{{info.marks}}</span></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="px-2 py-2">
                                <button class="text-indigo-500 font-weight-bold" @click="downloadPdf">Downlaod Pdf</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <BarChart :po_result="po_result"/>
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
import BarChart from "@/Components/BarChart.vue";

export default {
    components: {
    BreezeAuthenticatedLayout,
    Pagination,
    BreezeButton,
    BreezeInput,
    BreezeLabel,
    Link,
    Head,
    BarChart
},
    props: ["courses"],
    data() {
        return {
            result: "",
            po_result:"",
            form: this.$inertia.form({
                course:""
            }),
        };
    },
    methods: {
        findResult() {
            axios
                .get(this.route("student.markShowShow",[this.form.course]))
                .then((res) => {
                    this.result = res.data.result
                    this.po_result=res.data.po_result
                })
                .catch((err)=>console.log(err));
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
