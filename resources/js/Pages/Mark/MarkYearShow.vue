<template>
    <Head title="My Result" />

    <BreezeAuthenticatedLayout>
        <template #header> Batch Po Result </template>

        <div
            class="p-3 shadow-md rounded-md mx-auto md:w-4/5 w sm:w-11/12 w-full bg-white"
        >
            <form @submit.prevent="findResult">
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Program Outcome" />
                    <select
                        id="sessionId"
                        v-model="form.po"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="" disabled>
                            Choose a Course
                        </option>
                        <option
                            class="text-black"
                            v-for="(po, i) in pos"
                            :key="i"
                            :value="po.id"
                        >
                            {{ po.po_name }} / {{ po.po_no }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.po">
                        {{ form.errors.po }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Year" />
                    <input
                        placeholder="Enter Year"
                        type="text"
                        v-model="form.year"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                    <h2 class="text-red-500" v-if="form.errors.year">
                        {{ form.errors.year }}
                    </h2>
                </div>
                <BreezeButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Find
                </BreezeButton>
                <BreezeButton v-if="result && result.length > 0"
                class="ml-2 bg-blue-500"
                    :class="{ 'opacity-25': form.processing }"
                    :type="button"
                    @click="downloadPdf"
                >
                    Download
                </BreezeButton>
            </form>
        </div>
        <div
            v-if="result && result.length > 0"
            id="pdf"
            class="overflow-hidden my-8 w-full rounded-lg border shadow-xs"
        >
            <YearChartTable
                v-for="res in result"
                :key="res.batch_id"
                :course_name="res.course_name"
                :batch_name="res.batch_name"
                :student_pos="res.student_po"
                :percentageCount="res.percentageCount"
                :id="res.id"
            />
        </div>
        <div v-if="result && result.length === 0">
            <h2>Noting Found</h2>
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
import YearChartTable from "@/Components/YearChartTable.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Link,
        Head,
        BarChart,
        YearChartTable,
    },
    props: ["pos"],
    data() {
        return {
            result: null,
            po_result: "",
            form: this.$inertia.form({
                year: "",
                po: "",
            }),
        };
    },
    methods: {
        findResult() {
            axios
                .get(
                    this.route("exam.deanYearPoReport", [
                        this.form.year,
                        this.form.po,
                    ])
                )
                .then((res) => {
                    this.result = res.data;
                })
                .catch((err) => console.log(err));
        },
        downloadPdf() {
            let po_name=this.pos.filter((data)=>{
                if(data.id == this.form.po){
                   return data.po_name;
                }
            })
            var html = document.getElementById("pdf").innerHTML;
            let data = new FormData();
            data.append("year", this.form.year);
             data.append("po", po_name[0].po_name);
            data.append("html", html);
            axios
                .post(this.route("exam.markYearDownload"), data, {
                    responseType: "blob",
                })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `admin_year_po_${new Date().getTime()}.pdf`);
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
