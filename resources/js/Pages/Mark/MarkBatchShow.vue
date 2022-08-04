<template>
    <Head title="Batch Co Po Result" />

    <BreezeAuthenticatedLayout>
        <template #header> Batch Co Po Result </template>
        <div class="p-4 bg-white rounded-lg shadow-xs">
            <div
                class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md"
            >
                <div class="flex justify-center items-center w-12 bg-green-500">
                    <svg
                        class="w-6 h-6 text-white fill-current"
                        viewBox="0 0 40 40"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"
                        ></path>
                    </svg>
                </div>

                <div class="px-4 flex-1 py-2 -mx-3">
                    <div class="m-3">
                        <h1 class="text-2xl text-bold mb-3">
                            Teacher Name:
                            <span class="text-blue-400 text-normal">{{
                                teacherAssigns.rel_teacher.name
                            }}</span>
                        </h1>
                        <h1 class="text-xl">
                            Batch Name:
                            <span class="text-blue-400">{{
                                teacherAssigns.rel_batch.name
                            }}</span>
                        </h1>
                        <h1 class="text-xl">
                            Course Name:
                            <span class="text-blue-400">{{
                                teacherAssigns.rel_course.c_name
                            }}</span>
                        </h1>
                        <h1 class="text-xl">
                            Course Code:
                            <span class="text-blue-400">{{
                                teacherAssigns.rel_course.c_code
                            }}</span>
                        </h1>
                    </div>
                    <button class="float-right mr-4" @click="downloadPdf">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                            />
                        </svg>
                    </button>
                    <!-- <button
                        class="float-right mr-4"
                        @click="commentStatus = !commentStatus"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                            />
                        </svg>
                    </button> -->
                </div>
            </div>
            <!-- <div
                v-if="commentStatus"
                class="my-3 bg-white rounded-lg shadow-md p-2"
            >
                <form @keyup.enter="commentDone">
                    <textarea
                        v-model="comment"
                        placeholder="Enter Your Comment"
                        name=""
                        id=""
                        cols="30"
                        class="w-full border-1 border-indigo-400 rounded-lg"
                        rows="4"
                    ></textarea>
                    <span
                        class="text-red-500 my-2"
                        v-if="errors && errors.teacher_course"
                    >
                        {{ errors.teacher_course[0] }}</span
                    >
                    <span
                        class="text-red-500 my-2"
                        v-if="errors && errors.comment"
                    >
                        {{ errors.comment[0] }}</span
                    >
                </form>
            </div> -->
            <div id="pdf" style="margin-top:0px !important;">
                <div class="chart_wrapper" style="margin-top:0px !important;">
                    <PieGoogle
                        v-for="(dt, i) in data"
                        :key="i"
                        :name="i"
                        :co_name="dt.co_name"
                        :po_name="dt.po_name"
                        :co_no="dt.co_no"
                        :po_no="dt.po_no"
                        :below_40="dt.below_40"
                        :below_80="dt['79-60']"
                        :below_60="dt['59-40']"
                        :above_80="dt['80']"
                        :results="dt.result"
                        :co_id="dt.co_id"
                        :po_id="dt.po_id"
                        :comments="dt.comment"
                        :teacherAssigns_id="teacherAssigns.id"
                    />
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Pie from "@/Components/Pie.vue";
import PieGoogle from "@/Components/PieGoogle.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Pie,
        PieGoogle,
    },
    data() {
        return {
            commentStatus: false,
            loading: false,
            comment: "",
            errors: null,
        };
    },
    props: ["data", "teacherAssigns", "chart_comment"],
    mounted() {
        this.comment = this.chart_comment? this.chart_comment.comment : "";
    },
    methods: {
        commentDone(e) {
            e.preventDefault();
            axios
                .post(this.route("exam.markComment"), {
                    comment: this.comment,
                    teacher_course: this.teacherAssigns.id,
                })
                .then((response) => {
                    this.errors = null;
                    Swal.fire({ title: "Comment Added", icon: "success" });
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
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
                    link.setAttribute(
                        "download",
                        `co_by_batch${new Date().getMilliseconds()}.pdf`
                    );
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
