<template>
    <Head title="Session" />

    <BreezeAuthenticatedLayout>
        <template #header> Session </template>

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
            <form @submit.prevent="sessionCreate" class="mb-4">
                <h3 class="text-lg">Create Session Year Now</h3>
                <div class="md:flex">
                    <div class="my-4 flex-1">
                        <BreezeLabel for="year" value="Year" />
                        <!-- <BreezeInput
                            id="year"
                            type="date"
                            class="block w-full mt-1 p-1.5 border-2 border-indigo-100"
                            v-model="form.year"
                            required
                            autocomplete="off"
                        /> -->
                        <Datepicker
                            range
                            v-model="form.year"
                            lang="en"
                            disabled-start-date="disabledStartDate"
                        />
                        <h2 class="text-red-500" v-if="form.errors.year">
                            {{ form.errors.year }}
                        </h2>
                        <h2 class="text-red-500" v-if="form.errors['year.0']">
                            {{ form.errors["year.0"] }}
                        </h2>
                        <h2 class="text-red-500" v-if="form.errors['year.1']">
                            {{ form.errors["year.1"] }}
                        </h2>
                    </div>
                    <div class="my-4 flex-1">
                        <BreezeLabel for="sessionType" value="Session Type" />
                        <select
                            id="sessionType"
                            v-model="form.session_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option
                                class="text-black"
                                selected
                                value=""
                                disabled
                            >
                                Choose a Session Type
                            </option>
                            <option class="text-black" value="SUMMER">
                                Summer
                            </option>
                            <option class="text-black" value="FALL">
                                Fall
                            </option>
                            <option class="text-black" value="SPRING">
                                Spring
                            </option>
                        </select>
                        <h2
                            class="text-red-500"
                            v-if="form.errors.session_type"
                        >
                            {{ form.errors.session_type }}
                        </h2>
                    </div>
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
                                <th class="px-4 py-3">Session Year</th>
                                <th class="px-4 py-3">Session Type</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            <tr
                                v-for="(session, i) in sessions.data"
                                :key="session.id"
                                class="text-gray-700"
                            >
                                <td class="px-4 py-3 text-sm">
                                    {{ i + 1 }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ session.session_year }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ session.session_type }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9"
                >
                    <pagination :links="sessions.links" />
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
import "vue-datepicker-ui/lib/vuedatepickerui.css";
import VueDatepickerUi from "vue-datepicker-ui";
export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Datepicker: VueDatepickerUi,
    },
    data() {
        return {
            disabledStartDate: {
                to: new Date("08.02.1996"),
                from: new Date("10.02.1996"),
            },
            form: this.$inertia.form({ year: ["", ""], session_type: "" }),
        };
    },
    methods: {
        sessionCreate() {
            this.form.post(this.route("sessions.store"), {
                onSuccess: () => this.form.reset(),
                onError: (errors) => {
                    // console.log(errors['year.0'])
                },
            });
        },
    },
    props: {
        sessions: Object,
    },
    mounted() {
        console.log(this.form.errors);
    },
};
</script>
<style scoped>
.v-calendar .input-field.long {
    width: 400px !important;
}
.v-calendar .input-field input {
    height: 40px !important;
}
</style>
