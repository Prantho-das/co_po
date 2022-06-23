<template>
    <Head title="Course" />

    <BreezeAuthenticatedLayout>
        <template #header> Course Info </template>

        <div class="p-4 bg-white rounded-lg shadow-xs">
            <div
                class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md"
            >
                <div class="flex justify-center items-center w-12 bg-blue-500">
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

                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-blue-500">Info</span>
                        <p class="text-sm text-gray-600">Sample table page</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="batchCreate" class="mb-4">
                <h3 class="text-lg">Create Courses</h3>
                <div class="my-4">
                    <BreezeLabel for="year" value="Course Name" />
                    <BreezeInput
                        id="year"
                        type="text"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.name"
                        required
                        autocomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="year" value="Course Code" />
                    <BreezeInput
                        id="year"
                        type="text"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.code"
                        required
                        autocomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.code">
                        {{ form.errors.code }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Semester" />
                    <select
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
                        <h2 class="text-red-500" v-if="form.errors.semester">
                            {{ form.errors.semester }}
                        </h2>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.semester">
                        {{ form.errors.semester }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="year" value="Course Credit" />
                    <BreezeInput
                        id="year"
                        type="number"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.credit"
                        required
                        autocomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.credit">
                        {{ form.errors.credit }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="courseId" value="Course OutCome" />
                    <Select2 v-model="form.co" :options="coOptions" :settings="{width:'100%',multiple:true}" />
                    <h2 class="text-red-500" v-if="form.errors.co">
                        {{ form.errors.co }}
                    </h2>
                </div>
                <BreezeButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create
                </BreezeButton>
            </form>
            <div
                class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs"
            >
                <div class="overflow-x-auto w-full">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"
                            >
                                <th class="px-4 py-3">*</th>
                                <th class="px-4 py-3">Course Name</th>
                                <th class="px-4 py-3">Course Code</th>
                                <th class="px-4 py-3">Course Credit</th>
                                <th class="px-4 py-3">Course Semester</th>
                                <th class="px-4 py-3">Co Assign</th>
                                <th class="px-4 py-3">Assign Co Po</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            <tr
                                v-for="(course, i) in courses.data"
                                :key="course.id"
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
                                    {{ course.credit }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ course.semester_name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ course.copoAssign }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <Link
                                        class="text-blue-500 font-weight-bold"
                                        :href="route('course.show', course.id)"
                                        >Assign</Link
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9"
                >
                    <pagination :links="courses.links" />
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
import { Link } from "@inertiajs/inertia-vue3";
import Select2 from 'vue3-select2-component';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Link,Select2
    },
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                code: "",
                credit: "",
                semester: "",
                co: [],
            }),
        };
    },
    methods: {
        batchCreate() {
            this.form.post(this.route("course.store"), {
                onSuccess: () => this.form.reset(),
            });
        },
    },
    props: {
        semesters: Array,
        courses: Object,
        cos: Array,
    },
    computed: {
        coOptions() {
            return this.cos.map(co => ({
                id: co.id,
                text: co.co_name,
            }));
        },
    },
};
</script>
