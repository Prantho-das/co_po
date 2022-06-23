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
                    <div class="mx-3">
                        <span class="font-semibold text-blue-500">Info</span>
                        <p class="text-sm text-gray-600">Batch Co Po Result</p>
                    </div>
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
                </div>
            </div>
            <div id="pdf">
                <div
                    class="chart_wrapper"
                    style="
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: space-between;
                        flex-shrink: 1;
                        gap: 1.2rem;
                    "
                >
                    <PieGoogle
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
    props: ["data", "teacherAssigns"],
    methods: {
        downloadPdf() {
            var html = document.getElementById("pdf").innerHTML;
            let data = new FormData();
            data.append("teacherName", this.teacherAssigns.rel_teacher.name);
            data.append("batchName", this.teacherAssigns.rel_batch.name);
            data.append("courseName", this.teacherAssigns.rel_course.c_name);
            data.append("courseCode", this.teacherAssigns.rel_course.c_code);
            data.append("html", html);
            axios
                .post("/test", data, {
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
