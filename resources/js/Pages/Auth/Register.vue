<template>
    <Head title="Register" />

    <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="md:h-auto md:w-1/2">
            <img
                aria-hidden="true"
                class="object-cover w-full h-full"
                src="/images/create-account-office.jpeg"
                alt="Office"
            />
        </div>

        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
                <h1 class="mb-4 text-xl font-semibold text-gray-700">
                    Create Student Account
                </h1>

                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit">
                    <div>
                        <BreezeLabel for="name" value="Name" />
                        <BreezeInput
                            id="name"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel for="email" value="Email" />
                        <BreezeInput
                            id="email"
                            type="email"
                            class="block w-full mt-1"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                    </div>
                    <div class="mt-4">
                        <BreezeLabel for="roll" value="Roll" />
                        <BreezeInput
                            id="roll"
                            type="number"
                            class="block w-full mt-1"
                            v-model="form.roll"
                            required
                            autocomplete="username"
                        />
                    </div>
                    <div class="mt-4">
                        <BreezeLabel for="reg" value="Reg" />
                        <BreezeInput
                            id="reg"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.reg"
                            required
                            autocomplete="username"
                        />
                    </div>
                    <div class="mt-4">
                        <BreezeLabel for="shift" value="Shift" />
                        <BreezeInput
                            id="shift"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.shift"
                            required
                            autocomplete="username"
                        />
                    </div>
                    <!-- <div class="mt-4">
                        <BreezeLabel for="batch" value="Batch" />
                        <BreezeInput
                            id="batch"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.batch"
                            required
                            autocomplete="username"
                        />
                    </div> -->
                    <div class="mt-4">
                        <BreezeLabel for="department" value="Department" />
                        <select
                            id="department"
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
                            <h2
                                class="text-red-500"
                                v-if="form.errors.department"
                            >
                                {{ form.errors.department }}
                            </h2>
                        </select>
                    </div>
                    <div class="mt-4">
                        <BreezeLabel for="batch" value="Batch" />
                        <select
                            id="batch"
                            v-model="form.batch"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option selected value="" disabled>
                                Choose a Batch
                            </option>
                            <option
                                class="text-black"
                                v-for="(dpt, i) in batches"
                                :key="i"
                                :value="dpt.id"
                            >
                                {{ dpt.name }}
                            </option>
                            <h2
                                class="text-red-500"
                                v-if="form.errors.batch"
                            >
                                {{ form.errors.batch }}
                            </h2>
                        </select>
                    </div>
                    <div class="mt-4">
                        <BreezeLabel for="session" value="Session" />
                        <select
                            id="session"
                            v-model="form.session"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option selected value="" disabled>
                                Choose a Session
                            </option>
                            <option
                                class="text-black"
                                v-for="(dpt, i) in sessions"
                                :key="i"
                                :value="dpt.id"
                            >
                                {{ dpt.session_year }}
                            </option>
                            <h2 class="text-red-500" v-if="form.errors.session">
                                {{ form.errors.session }}
                            </h2>
                        </select>
                    </div>
                    <div class="mt-4">
                        <BreezeLabel for="password" value="Password" />
                        <BreezeInput
                            id="password"
                            type="password"
                            class="block w-full mt-1"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <BreezeLabel
                            for="password_confirmation"
                            value="Confirm Password"
                        />
                        <BreezeInput
                            id="password_confirmation"
                            type="password"
                            class="block w-full mt-1"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link
                            :href="route('student.login')"
                            class="text-sm text-gray-600 underline hover:text-gray-900"
                        >
                            Already registered?
                        </Link>

                        <BreezeButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Register
                        </BreezeButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeGuestLayout from "@/Layouts/Guest.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: "",
                email: "",
                roll: "",
                reg: "",
                shift: "",
                batch: "",
                session: "",
                department: "",
                password: "",
                password_confirmation: "",
                terms: false,
            }),
        };
    },
    props: ["departments", "batches", "sessions"],
    methods: {
        submit() {
            this.form.post(this.route("student.register.store"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
                onSuccess: () =>
                    this.form.reset(),
            });
        },
    },
};
</script>
