<template>

    <Head title="Create User" />

    <BreezeAuthenticatedLayout>
        <template #header> Create User </template>

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
            <form @submit.prevent="userCreate" class="mb-4">
                <h3 class="text-lg">Create Users Here</h3>
                <div class="my-4">
                    <BreezeLabel for="name" value="Enter Name" class="text-blue-500" />
                    <BreezeInput id="name" type="text" class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.name" required autopomplete="off" />
                    <h2 class="text-red-500" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="email" value="Enter Email" class="text-blue-500" />
                    <BreezeInput id="email" type="email" class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.email" required autopomplete="off" />
                    <h2 class="text-red-500" v-if="form.errors.email">
                        {{ form.errors.email }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="password" value="Enter Password" class="text-blue-500" />
                    <BreezeInput id="password" type="password" class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.password" required autopomplete="off" />
                    <h2 class="text-red-500" v-if="form.errors.name">
                        {{ form.errors.password }}
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
                        <h2 class="text-red-500" v-if="form.errors.department">
                            {{ form.errors.department }}
                        </h2>
                    </select>
                </div>
                <div class="my-4">
                    <BreezeLabel for="phone" value="Enter Phone" class="text-blue-500" />
                    <BreezeInput id="phone" type="tel" class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        v-model="form.phone" required autopomplete="off" />
                    <h2 class="text-red-500" v-if="form.errors.phone">
                        {{ form.errors.phone }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="roleType" value="Role Type" class="text-blue-500" />
                    <select id="roleType" v-model="form.role_type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option class="text-black" selected value="" disabled>
                            Choose a User Type
                        </option>
                        <option class="text-black" value="ADMIN">Admin</option>
                        <option class="text-black" value="TEACHER">
                            Teacher
                        </option>
                        <option class="text-black" value="DEAN">Dean</option>
                        <h2 class="text-red-500" v-if="form.errors.role_type">
                            {{ form.errors.role_type }}
                        </h2>
                    </select>
                </div>
                <div class="my-4">
                    <BreezeLabel for="address" value="Enter Address" class="text-blue-500" />
                    <textarea name="" v-model="form.address" id="address" class="rounded w-full border-indigo-500" rows="5"></textarea>
                    <h2 class="text-red-500" v-if="form.errors.name">
                        {{ form.errors.address }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="avatar" value="Enter Avatar" class="text-blue-500" />
                    <BreezeInput id="avatar" accept="images/*" @input="form.avatar = $event.target.files[0]" type="file"
                        class="block w-full mt-1 p-1.5 border-2 border-rose-100"
                        autopomplete="off" />
                    <h2 class="text-red-500" v-if="form.errors.avatar">
                        {{ form.errors.avatar }}
                    </h2>
                </div>
                <div class="my-4">
                    <BreezeLabel for="designation" value="Enter designation" class="text-blue-500" />
                    <Select2 v-model="form.designation" :settings="{width:'100%',multiple:true, tags: true,tokenSeparators: [',']}" />
                    <h2 class="text-red-500" v-if="form.errors.designation">
                        {{ form.errors.designation }}
                    </h2>
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
import Select2 from 'vue3-select2-component';
export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        Select2
    },
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                email: "",
                password: "",
                department: "",
                phone: "",
                role_type: "",
                avatar: "",
                address:"",
                designation:[],
            }),
        };
    },
    methods: {
        userCreate() {
            this.form.post(this.route("users.store"), {
                onSuccess: () => {
                    this.form.reset()
                },
            });
        },
    },
    props: {
        departments: Array
    },
};
</script>
