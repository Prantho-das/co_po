<template>

    <Head title="Assign Teacher Create" />

    <BreezeAuthenticatedLayout>
        <template #header> Assign Teacher Create </template>

        <div class="p-4 bg-white rounded-lg shadow-xs">
            <div class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md">
                <div class="flex justify-center items-center w-12 bg-blue-500">
                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z">
                        </path>
                    </svg>
                </div>

                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-blue-500">Info</span>
                        <p class="text-sm text-gray-600">Sample table page</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="assignTeacher" class="mb-4">
                <h3 class="text-lg">Create Users Here</h3>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Select Teacher" />
                    <select id="sessionId" v-model="form.teacher"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="" disabled>
                            Choose a Teacher
                        </option>
                        <option class="text-black" v-for="(teacher, i) in teachers" :key="i" :value="teacher.id">
                            {{ teacher.name }}
                        </option>
                        <h2 class="text-red-500" v-if="form.errors.teacher">
                            {{ form.errors.teacher }}
                        </h2>
                    </select>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Select Course" />
                    <select id="sessionId" v-model="form.course"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="" disabled>
                            Choose a Program Outcome
                        </option>
                        <option class="text-black" v-for="(course, i) in courses" :key="i" :value="course.id">
                            {{ course.c_name }}
                        </option>
                        <h2 class="text-red-500" v-if="form.errors.course">
                            {{ form.errors.course }}
                        </h2>
                    </select>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Select Batch" />
                    <select id="sessionId" v-model="form.batch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="" disabled>
                            Choose a Batch
                        </option>
                        <option class="text-black" v-for="(batch, i) in batches" :key="i" :value="batch.id">
                            {{ batch.name }}
                        </option>
                        <h2 class="text-red-500" v-if="form.errors.batch">
                            {{ form.errors.batch }}
                        </h2>
                    </select>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Select Semester" />
                    <select id="sessionId" v-model="form.semester"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="" disabled>
                            Choose a Semester
                        </option>
                        <option class="text-black" v-for="(semester, i) in semesters" :key="i" :value="semester.id">
                            {{ semester.name }}
                        </option>
                        <h2 class="text-red-500" v-if="form.errors.semester">
                            {{ form.errors.semester }}
                        </h2>
                    </select>
                </div>
                <div class="my-4">
                    <BreezeLabel for="sessionId" value="Select Semester" />
                    <select id="sessionId" v-model="form.session"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="" disabled>
                            Choose a session
                        </option>
                        <option class="text-black" v-for="(session, i) in sessions" :key="i" :value="session.id">
                            {{ session.session_year }}
                        </option>
                        <h2 class="text-red-500" v-if="form.errors.session">
                            {{ form.errors.session }}
                        </h2>
                    </select>
                </div>
                <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create
                </BreezeButton>
            </form>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Pagination from "@/Components/Pagination.vue";
import BreezeLabel from "@/Components/Label.vue";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
    },
    data() {
        return {
            form: this.$inertia.form({
                teacher: "",
                course: "",
                semester: "",
                batch:"",
                session:""
            }),
        };
    },
    methods: {
        assignTeacher() {
            this.form.post(this.route("course.assignTeacherStore"), {
                onSuccess: () => {
                    this.form.reset()
                },
            });
        },
    },
    props: {
        teachers: Array,
        courses: Array,
        semesters: Array,
        batches: Array,
        sessions: Array
    },
};
</script>
