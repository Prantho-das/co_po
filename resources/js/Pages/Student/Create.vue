<template>
    <Head title="Create Student" />

    <BreezeAuthenticatedLayout>
        <template #header> Create Student </template>
        <div class="p-4 bg-white rounded-lg shadow-xs mb-5">
            <h3 class="text-lg">Import Csv</h3>
            <form @submit.prevent="StudentBulkCreate">
                <div class="flex flex-wrap justify-between gap-4 items-start">
                    <div class="my-4 flex-1">
                        <BreezeLabel
                            for="department"
                            value="Csv"
                            class="text-blue-500"
                        />
                        <BreezeInput
                            id="name"
                            type="file"
                            class="block p-1 mb-1 border border-gray-300"
                            @change="(e) => (student_csv = e.target.files[0])"
                            required
                            autopomplete="off"
                        />
                        <h2
                            class="text-red-500"
                            v-if="errors && errors.student_csv"
                        >
                            {{ errors.student_csv }}
                        </h2>
                        <ol
                            style="list-style-type: decimal; display: flex"
                            class="ml-4"
                        >
                            <li class="text-red-500 mr-5">Name</li>
                            <li class="text-red-500 mr-5">Roll</li>
                            <li class="text-red-500 mr-5">Reg</li>
                            <li class="text-red-500 mr-5">Shift</li>
                            <li class="text-red-500 mr-5">Batch</li>
                        </ol>
                    </div>
                    <div class="my-4 flex-1">
                        <BreezeLabel
                            for="department"
                            value="Department"
                            class="text-blue-500"
                        />
                        <select
                            id="department"
                            v-model="department"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option
                                class="text-black"
                                selected
                                value=""
                                disabled
                            >
                                Choose a department Type
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
                        <h2 class="text-red-500" v-if="errors.department">
                            {{ errors.department }}
                        </h2>
                    </div>
                    <div class="my-4 flex-1">
                        <BreezeLabel
                            for="batch"
                            value="Batch"
                            class="text-blue-500"
                        />
                        <select
                            id="batch"
                            v-model="batch"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option
                                class="text-black"
                                selected
                                value=""
                                disabled
                            >
                                Choose a Batch Type
                            </option>
                            <option
                                class="text-black"
                                v-for="(bt, i) in batches"
                                :key="i"
                                :value="bt.id"
                            >
                                {{ bt.name }}
                            </option>
                        </select>
                        <h2 class="text-red-500" v-if="errors.batch">
                            {{ errors.batch }}
                        </h2>
                    </div>
                    <div class="my-4 flex-1">
                        <BreezeLabel
                            for="session"
                            value="Session"
                            class="text-blue-500"
                        />
                        <select
                            id="session"
                            v-model="session"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option
                                class="text-black"
                                selected
                                value=""
                                disabled
                            >
                                Choose a Student Session
                            </option>
                            <option
                                class="text-black"
                                v-for="(ses, i) in sessions"
                                :key="i"
                                :value="ses.id"
                            >
                                {{ ses.session_year }}
                            </option>
                        </select>
                        <h2 class="text-red-500" v-if="errors.session">
                            {{ errors.session }}
                        </h2>
                    </div>
                    <div class="my-4">
                        <BreezeLabel
                            for="shift"
                            value="Enter shift"
                            class="text-blue-500"
                        />
                        <BreezeInput
                            id="shift"
                            type="tel"
                            class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                            v-model="shift"
                            required
                            autopomplete="off"
                        />
                        <h2 class="text-red-500" v-if="errors.shift">
                            {{ errors.shift }}
                        </h2>
                    </div>
                </div>
                <BreezeButton class="mt-2"> Bulk Create </BreezeButton>
            </form>
        </div>
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
            <form @submit.prevent="StudentCreate" class="mb-4">
                <h3 class="text-lg">Create Students Here</h3>
                <div class="my-4">
                    <BreezeLabel
                        for="name"
                        value="Enter Name"
                        class="text-blue-500"
                    />
                    <BreezeInput
                        id="name"
                        type="text"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.name"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel
                        for="email"
                        value="Enter Email"
                        class="text-blue-500"
                    />
                    <BreezeInput
                        id="email"
                        type="email"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.email"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.email">
                        {{ form.errors.email }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel
                        for="roll"
                        value="Enter roll"
                        class="text-blue-500"
                    />
                    <BreezeInput
                        id="roll"
                        type="number"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.roll"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.roll">
                        {{ form.errors.roll }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel
                        for="reg"
                        value="Enter reg"
                        class="text-blue-500"
                    />
                    <BreezeInput
                        id="reg"
                        type="text"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.reg"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.reg">
                        {{ form.errors.reg }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel
                        for="shift"
                        value="Enter shift"
                        class="text-blue-500"
                    />
                    <BreezeInput
                        id="shift"
                        type="tel"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.shift"
                        required
                        autopomplete="off"
                    />
                    <h2 class="text-red-500" v-if="form.errors.shift">
                        {{ form.errors.shift }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel
                        for="department"
                        value="department"
                        class="text-blue-500"
                    />
                    <select
                        id="department"
                        v-model="form.department"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option class="text-black" selected value="" disabled>
                            Choose a department Type
                        </option>
                        <option
                            class="text-black"
                            v-for="(department, i) in departments"
                            :key="i"
                            :value="department.id"
                        >
                            {{ department.name }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.department">
                        {{ form.errors.department }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel
                        for="batch"
                        value="Batch"
                        class="text-blue-500"
                    />
                    <select
                        id="batch"
                        v-model="form.batch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option class="text-black" selected value="" disabled>
                            Choose a Batch Type
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
                    <BreezeLabel
                        for="session"
                        value="Session"
                        class="text-blue-500"
                    />
                    <select
                        id="session"
                        v-model="form.session"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option class="text-black" selected value="" disabled>
                            Choose a Student Session
                        </option>
                        <option
                            class="text-black"
                            v-for="(session, i) in sessions"
                            :key="i"
                            :value="session.id"
                        >
                            {{ session.session_year }}
                        </option>
                    </select>
                    <h2 class="text-red-500" v-if="form.errors.session">
                        {{ form.errors.session }}
                    </h2>
                </div>
                <!-- <div class="my-4">
                    <BreezeLabel for="avatar" value="Enter Avatar" class="text-blue-500" />
                    <BreezeInput id="avatar" accept="images/*" @input="form.avatar = $event.target.files[0]" type="file"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100" required
                        autopomplete="off" />
                    <h2 class="text-red-500" v-if="form.errors.avatar">
                        {{ form.errors.avatar }}
                    </h2>
                </div> -->
                <BreezeButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
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
            loading: false,
            student_csv: "",
            batch: "",
            session: "",
            department: "",
            shift: "",
            form: this.$inertia.form({
                name: "",
                email: "",
                roll: "",
                reg: "",
                shift: "",
                batch: "",
                session: "",
                department: "",
            }),
        };
    },
    methods: {
        StudentCreate() {
            this.form.post(this.route("users.studentStore"), {
                onSuccess: () => {
                    this.form.reset();
                },
            });
        },
        StudentBulkCreate() {
            this.loading = true;
            let formData = new FormData();
            formData.append("shift", this.shift);
            formData.append("batch", this.batch);
            formData.append("session", this.session);
            formData.append("department", this.department);
            formData.append("student_csv", this.student_csv);
            this.$inertia.post(this.route("users.studentBulk"), formData, {
                onSuccess: () => {
                    this.loading = false;
                    this.student_csv = "";
                    this.batch = "";
                    this.session = "";
                    this.department = "";
                    this.shift = "";
                    document.querySelector('input').value=""
                },
                onError: (error) => {
                    this.loading = false;
                    if (error[0]) {
                        alert("This Registration Number is already exist");
                    }
                },
            });
            this.loading = false;
        },
    },
    props: {
        batches: Array,
        sessions: Array,
        errors: Array,
        departments: Array,
        errors: Array,
    },
};
</script>
